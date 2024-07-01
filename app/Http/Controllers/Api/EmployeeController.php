<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerfiy;
use App\Mail\Thankyou;
use OTPHP\TOTP;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\GenarateOtp;
use Tymon\JWTAuth\Token;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public function emailverfiy(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9._%+-]+@pixel-studios\.com$/'
                ],
                'token' => 'required|unique:employees',
            ]); 
            if($validator->fails()) {
                $this->error = $validator->errors();
                throw new \Exception('validation Error');
            }
            $employee = Employee::where('email', $request->email)->first();
            if ($employee) {
                if($employee->status == "completed"){
                    return $this->returnError(false,'We had already received your entry and it is in review now',400,400);
                }
                $oneMinuteAgo = Carbon::now()->subMinute();
            $oneHourAgo = Carbon::now()->subHour();
            
            // Count OTPs generated within the last minute
            $onemiutecount = GenarateOtp::where('email', $employee->email)
                                         ->where('created_at', '>=', $oneMinuteAgo)
                                         ->count();
            
            // Count OTPs generated within the last hour
            $onehourcount = GenarateOtp::where('email', $employee->email)
                                        ->where('created_at', '>=', $oneHourAgo)
                                        ->count(); 
            if($onemiutecount < 3 && $onehourcount < 30 ){
                $otp= $this->generateOtp($employee->id);
                $employee->session_token=$request->token;
                $employee->save();
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
                $employee = Employee::find($employee->id);
                $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                $employee->save();
                $otpstore =new GenarateOtp();
                $otpstore->email=$employee->email;
                $otpstore->otp=$employee->otp;
                $otpstore->save();
                }else{
                    return $this->returnError('Your otp limit reached.Please try after somethime.');
                    }

            }else{
                $employee = new Employee();
                $employee->email = $request->input('email');
                $employee->session_token=$request->token;
                $employee->save();
                $otp= $this->generateOtp($employee->id);
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
                $employee = Employee::find($employee->id);
                $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                $employee->save();
                $otpstore =new GenarateOtp();
                $otpstore->email=$employee->email;
                $otpstore->otp=$employee->otp;
                $otpstore->save();
            }
            LogHelper::AddLog('Employee',$employee->id,'Otp Send',$otp,'OTP genarate this '.$employee->email);
            return $this->returnSuccess(
               [],'OTP send successfully');
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
    }
    public static function generateOtp($id)
    {
        $totp = TOTP::create();
        $secret_key = $totp->getSecret();
        $timestamp = time();
        $otp = TOTP::create($secret_key);
        $otp->setDigits(4);
        $code = $otp->at($timestamp);
        $employee = Employee::find($id);
        $employee->otp=$code;
        $employee->save();
        return $code;
    }
    public function resendOtp(Request $request){
        try {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9._%+-]+@pixel-studios\.com$/'
            ],
            'token' => 'required|unique:employees',
        ]);
        if($validator->fails()) {
            $this->error = $validator->errors();
            throw new \Exception('validation Error');
        }
        $employee = Employee::where('email', $request->email)->first();
        if ($employee->session_token != $request->token) {
            Log::error('Session token mismatch for email: ' . $request->email);
            return $this->returnError('Session token is wrong');
        } 
        if ($employee) {
            $oneMinuteAgo = Carbon::now()->subMinute();
            $oneHourAgo = Carbon::now()->subHour();
            
            // Count OTPs generated within the last minute
            $onemiutecount = GenarateOtp::where('email', $employee->email)
                                         ->where('created_at', '>=', $oneMinuteAgo)
                                         ->count();
            
            // Count OTPs generated within the last hour
            $onehourcount = GenarateOtp::where('email', $employee->email)
                                        ->where('created_at', '>=', $oneHourAgo)
                                        ->count(); 
                                                   
            if($onemiutecount < 3 && $onehourcount < 30){
                    $otp= $this->generateOtp($employee->id);
                    $employee->save();
                    $data=explode('@', $employee->email);
                    Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
                    $employee = Employee::find($employee->id);
                    $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                    $employee->save();
                    $otpstore =new GenarateOtp();
                    $otpstore->email=$employee->email;
                    $otpstore->otp=$employee->otp;
                    $otpstore->save();
                    LogHelper::AddLog('Employee',$employee->id,'Otp Send',$otp,' Resend OTP genarate this '.$employee->email);
                }else{
                    return $this->returnError('Your otp limit reached.Please try after somethime.');
                }
           }else{
            return $this->returnError('Employee not found');
        }
        return $this->returnSuccess(
           [],'Resend OTP send successfully');
    } catch (\Throwable $e) {
        return $this->returnError($this->error ?? $e->getMessage());
    }
    }
    
    // Usage example:
   
    
    public function otpverfiy(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validate request inputs
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9._%+-]+@pixel-studios\.com$/'
                ],
                'otp' => 'required|size:4',
                'token' => 'required',
            ]);
            
            if ($validator->fails()) {
                $this->error = $validator->errors();
                Log::error('Validation Error: ' . json_encode($validator->errors()));
                throw new \Exception('Validation Error');
            }
            
            // Fetch the employee by email
            $employee = Employee::where('email', $request->email)->first();
            if (!$employee) {
                Log::error('Employee not found for email: ' . $request->email);
                return $this->returnError('Employee not found');
            }
            
            // Check if OTP has expired
            if (Carbon::parse($employee->expired_date)->lt(Carbon::now())) {
                Log::error('OTP has expired for email: ' . $request->email);
                return $this->returnError('OTP has expired');
            }
            
            if ($employee->session_token != $request->token) {
                Log::error('Session token mismatch for email: ' . $request->email);
                return $this->returnError('Session token is wrong');
            } else {
                $employee->session_token = null;
            }
            
            // Verify OTP
            if ($employee->otp == $request->otp) {
                $employee->otp_verified = true;
                if (!$employee->status) {
                    $employee->status = 'basic';
                }
                
                // Invalidate old token if exists
                if ($employee->token) {
                    try {
                        $token = new Token($employee->token);
                        JWTAuth::setToken($token)->invalidate(true);
                        Log::info('Token invalidated successfully for email: ' . $request->email);
                    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                        Log::error('Token invalidation error for email: ' . $request->email . ' - ' . $e->getMessage());
                        // Handle the case where token is already invalid
                        // You can choose to continue or return an error response
                    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                        Log::error('Token expiration error for email: ' . $request->email . ' - ' . $e->getMessage());
                        // Handle token expired case
                        // Set token to null after expiry
                        $employee->token = null;
                    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                        Log::error('JWT Exception for email: ' . $request->email . ' - ' . $e->getMessage());
                        return $this->returnError('JWT Exception: ' . $e->getMessage());
                    } catch (\Exception $e) {
                        Log::error('General Exception during token invalidation for email: ' . $request->email . ' - ' . $e->getMessage());
                        return $this->returnError('General Exception: ' . $e->getMessage());
                    }
                    // Regardless of success or failure, set token to null
                    $employee->token = null;
                }
                
                // Generate new token
                $newToken = JWTAuth::fromUser($employee);
                $employee->token = $newToken;
                
                // Save employee data
                $employee->save();
                
                DB::commit();
                Log::info('OTP verified and new token generated for email: ' . $request->email);
                return $this->respondWithToken($newToken);
            } else {
                DB::rollback();
                Log::error('Invalid OTP entered for email: ' . $request->email);
                return $this->returnError('Enter Valid OTP');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Exception in otpverfiy: ' . $e->getMessage());
            return $this->returnError($e->getMessage());
        }
    }
    

    public function createRandomToken(Request $request) {
        try {
           
            // ...
            $token = Str::random(60);
    
            return $this->returnSuccess([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 120
            ], 'Random token create sucessfully');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Log the exception for debugging
            Log::error('JWT Exception: ' . $e->getMessage());
    
            return response()->json(['error' => 'Could not create token.'], 500);
        }
    }
    
    protected function respondWithToken($token)
    {
        return $this->returnSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL()
        ], 'OTP verified successfully.');
    }
    public function getEmployee()
    {
        $user = Auth::guard('api')->user(); 
        if($user->state){
            $state = [
                [
                    'id' => $user->state_id ?? null,
                    'label' => $user->state
                ]
            ];
            $user->state = $state;
        }
        return $this->returnSuccess($user, 'Employee data successfully retrieved');
    }
    public function save(Request $request)
    {
        try {
            $user=Auth::guard('api')->user(); 
            $id = $user->id;
            $employee=Employee::find($id);
            if($request->status == "upload"){
                $validator = Validator::make($request->all(), [
                    'employee_name' => [
                        'required', 
                        'string', 
                        'max:100',
                        function ($attribute, $value, $fail) {
                            if ($value !== strip_tags($value)) {
                                $fail('The ' . $attribute . ' field must not contain HTML tags.');
                            }
                            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
                                $fail('The ' . $attribute . ' field must not contain special characters.');
                            }
                        },
                    ],
                    'employee_code' => 'required|string|max:10',
                    'mobile_number' => [
                        'required',
                        'digits:10',
                        Rule::unique('employees', 'mobile_number')->ignore($id),
                    ],
                    'dob' => 'required|date',
                    'department' => [
                        'required', 
                        'string', 
                        'max:100',
                        function ($attribute, $value, $fail) {
                            if ($value !== strip_tags($value)) {
                                $fail('The ' . $attribute . ' field must not contain HTML tags.');
                            }
                            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
                                $fail('The ' . $attribute . ' field must not contain special characters.');
                            }
                        },
                    ],
                    'designation' => [
                        'required', 
                        'string', 
                        'max:100',
                        function ($attribute, $value, $fail) {
                            if ($value !== strip_tags($value)) {
                                $fail('The ' . $attribute . ' field must not contain HTML tags.');
                            }
                            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
                                $fail('The ' . $attribute . ' field must not contain special characters.');
                            }
                        },
                    ],
                    'state' => [
                        'required', 
                        'string', 
                        'max:100',
                        function ($attribute, $value, $fail) {
                            if ($value !== strip_tags($value)) {
                                $fail('The ' . $attribute . ' field must not contain HTML tags.');
                            }
                            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
                                $fail('The ' . $attribute . ' field must not contain special characters.');
                            }
                        },
                    ],
                    'city' => [
                        'required', 
                        'string', 
                        'max:100',
                        function ($attribute, $value, $fail) {
                            if ($value !== strip_tags($value)) {
                                $fail('The ' . $attribute . ' field must not contain HTML tags.');
                            }
                            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
                                $fail('The ' . $attribute . ' field must not contain special characters.');
                            }
                        },
                    ],
                ], [
                    'mobile_number.unique' => 'This Number is Already Registered',
                    'mobile_number.digits' => 'Enter a 10 Digit Mobile Number',
                    'employee_code.integer' => 'Employee code must be an integer.',
                    'employee_code.max' => 'Employee code must not exceed 10 digits.'
                ]);
            
                if ($validator->fails()) {
                    $this->error = $validator->errors();
                    throw new \Exception('Validation Error');
                }
                $employee->employee_name = $request->input('employee_name');
                $employee->dob = $request->input('dob');
                $employee->department = $request->input('department');
                $employee->designation = $request->input('designation');
                $employee->state = $request->input('state');
                // $employee->state_id = $request->input('state_id');
                $employee->city = $request->input('city'); 
                $employee->employee_code = $request->input('employee_code');
                $employee->mobile_number = $request->input('mobile_number');
                $employee->status = $request->input('status');
               $message='Profile Details updated successfully';
            }elseif($request->status == "summary"){
                if ($request->hasFile('passport_photo')) {    
                    $validator = Validator::make($request->all(), [
                        'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    $validator->after(function ($validator) use ($request) {
                        if ($request->file('passport_photo')) {
                            $fileName = $request->file('passport_photo')->getClientOriginalName();
                            $extension = $request->file('passport_photo')->getClientOriginalExtension();
                
                            // Check if the file name contains more than one extension separator
                            if (substr_count($fileName, '.') > 1) {
                                $validator->errors()->add('passport_photo', 'Double file extension is not allowed.');
                            }
                        }
                    });
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }
                    if($employee->passport_photo != null){
                        $data=explode('.com/', $employee->passport_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                  }         
                    $passport_photo=$request->passport_photo;
                    $fileName = "passport_photo_" . uniqid() . "_" . time() . "." . $passport_photo->getClientOriginalExtension();
                      $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($passport_photo));
                    $employee->passport_photo = $filePath;
                }
                if ($request->hasFile('profile_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    $validator->after(function ($validator) use ($request) {
                        if ($request->file('profile_photo')) {
                            $fileName = $request->file('profile_photo')->getClientOriginalName();
                            $extension = $request->file('profile_photo')->getClientOriginalExtension();
                
                            // Check if the file name contains more than one extension separator
                            if (substr_count($fileName, '.') > 1) {
                                $validator->errors()->add('profile_photo', 'Double file extension is not allowed.');
                            }
                        }
                    });
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    } 
                    $profile_photo=$request->profile_photo;
                    $fileName = "profile_photo_" . uniqid() . "_" . time() . "." . $profile_photo->extension();
                    $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($profile_photo));
                    $employee->profile_photo = $filePath;
                }
                if ($request->hasFile('family_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'family_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    $validator->after(function ($validator) use ($request) {
                        if ($request->file('family_photo')) {
                            $fileName = $request->file('family_photo')->getClientOriginalName();
                            $extension = $request->file('family_photo')->getClientOriginalExtension();
                
                            // Check if the file name contains more than one extension separator
                            if (substr_count($fileName, '.') > 1) {
                                $validator->errors()->add('family_photo', 'Double file extension is not allowed.');
                            }
                        }
                    });
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }   
                    $family_photo=$request->family_photo;
                    $fileName = "family_photo_" . uniqid() . "_" . time() . "." . $family_photo->extension();
                    $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($family_photo));
                    $employee->family_photo = $filePath;
                }
                $employee->status = $request->input('status');
                if( !$employee->profile_photo || !$employee->passport_photo){
                    return $this->returnError('please check employee profile photo and passport photo');
                }
                $message='Photo uploaded successfuly';
            }else{
                $employee->status = $request->input('status');
                $employee->employee_status ='register';
                Mail::to( $employee->email)->send(new Thankyou($employee->employee_name));
                $message = 'Profile Registered successfully';
            }
            $employee->save();
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
        return $this->returnSuccess(
            $employee,$message);
    }
    public function employeelogout()
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->returnSuccess([], 'Successfully logged out');
        } catch (JWTException $e) {
            return $this->returnError('Failed to log out, please try again.');
        }
    }
    public function update(Request $request)
    {
        try {
            $user=Auth::guard('api')->user(); 
            $id = $user->id;
            $employee=Employee::find($id);
            if($request->input('mobile_number')){
                $validator = Validator::make($request->all(), [
                      'mobile_number' => [
                        'nullable',
                        'unique:employees,mobile_number,'.$id,
                      ],
                    ], [
                        'mobile_number.unique' => 'This Number is Already Registered',
                    ]
            );
                if ($validator->fails()) {
                    $this->error = $validator->errors();
                    throw new \Exception('validation Error');
                }
            }
               
                $employee->employee_name = $request->input('employee_name') ?? $employee->employee_name;
                $employee->dob = $request->input('dob') ?? $employee->dob;
                $employee->department = $request->department ?? $employee->department;
                $employee->designation = $request->designation ?? $employee->designation;
                $employee->state = $request->input('state') ?? $employee->state;
                $employee->city = $request->input('city') ?? $employee->city; 
                $employee->employee_code = $request->input('employee_code') ?? $employee->employee_code;
                $employee->mobile_number = $request->input('mobile_number') ?? $employee->mobile_number;
              if(!$request->input('employee_name')){
                if($request->input('status')=="summary"){
                if ($request->hasFile('passport_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }   
                    if($employee->passport_photo != null){
                        $data=explode('.com/', $employee->passport_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                  }         
                    $passport_photo=$request->passport_photo;
                    $fileName = "passport_photo_" . uniqid() . "_" . time() . "." . $passport_photo->extension();
                    $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($passport_photo));
                    $employee->passport_photo = $filePath;
                 }
                elseif($employee->passport_photo && $request->passport_photo){
                    $data=explode('.com/', $employee->passport_photo);
                    $employee->passport_photo =$data[1];
                }elseif($employee->passport_photo && $request->passport_photo == null){
                    $data=explode('.com/', $employee->passport_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                   $employee->passport_photo = null;
                }
                if ($request->hasFile('profile_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }    
                    if($employee->profile_photo != null){
                        $data=explode('.com/', $employee->profile_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                  }         
                    $profile_photo=$request->profile_photo;
                    $fileName = "profile_photo_" . uniqid() . "_" . time() . "." . $profile_photo->extension();
                    $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($profile_photo));
                    $employee->profile_photo = $filePath;
                }
                elseif($employee->profile_photo && $request->profile_photo){
                    $data=explode('.com/', $employee->profile_photo);
                    $employee->profile_photo =$data[1];
                }elseif($employee->profile_photo && $request->profile_photo == null){
                    $data=explode('.com/', $employee->profile_photo);
                    if (Storage::disk('s3')->exists($data[1])) {
                        Storage::disk('s3')->delete($data[1]);
                    }
                   $employee->profile_photo = null;
                }
                if ($request->hasFile('family_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'family_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }     
                    if($employee->family_photo != null){
                        $data=explode('.com/', $employee->family_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                  }         
                    $family_photo=$request->family_photo;
                    $fileName = "family_photo_" . uniqid() . "_" . time() . "." . $family_photo->extension();
                    $filePath = 'employee/' . $fileName;
                    Storage::disk('s3')->put($filePath, file_get_contents($family_photo));
                    $employee->family_photo = $filePath;
                }
                elseif($employee->family_photo && $request->family_photo){
                    $data=explode('.com/', $employee->family_photo);
                    $employee->family_photo =$data[1];
                }
                elseif($employee->family_photo && $request->family_photo == null){
                    $data=explode('.com/', $employee->family_photo);
                        if (Storage::disk('s3')->exists($data[1])) {
                            Storage::disk('s3')->delete($data[1]);
                        }
                   $employee->family_photo = null;
                }
            }
            }
                 $employee->save();
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
        return $this->returnSuccess(
            $employee,'Profile updated successfully');
    }

}
