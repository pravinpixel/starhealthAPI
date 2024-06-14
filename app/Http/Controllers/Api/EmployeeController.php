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
use App\Models\Department;
use Carbon\Carbon;
use App\Models\Designation;
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
                    'regex:/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@pixel-studios\.com$/'
                ],
                'token' => 'required|unique:employees',
            ]); 
            if($validator->fails()) {
                $this->error = $validator->errors();
                throw new \Exception('validation Error');
            }
            $employee = Employee::where('email', $request->email)->first();
            if ($employee) {
                $otp= $this->generateOtp($employee->id);
                // $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                $employee->session_token=$request->token;
                $employee->save();
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
                $employee = Employee::find($employee->id);
                $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                $employee->save();
            }else{
                $employee = new Employee();
                $employee->email = $request->input('email');
                $employee->session_token=$request->token;
                // $employee->expired_date = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
                $employee->save();
                $otp= $this->generateOtp($employee->id);
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
                $employee = Employee::find($employee->id);
                $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
                $employee->save();
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
                'regex:/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@pixel-studios\.com$/'
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
            $otp= $this->generateOtp($employee->id);
            $employee->save();
            $data=explode('@', $employee->email);
            Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
            $employee = Employee::find($employee->id);
            $employee->expired_date = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');
            $employee->save();
           }else{
            return $this->returnError('Employee not found');
        }
        LogHelper::AddLog('Employee',$employee->id,'Otp Send',$otp,' Resend OTP genarate this '.$employee->email);
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
                'regex:/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@(starhealth|starinsurance)\.in$|^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?@pixel-studios\.com$/'
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
                $token = new Token($employee->token);
                Log::info('Attempting to invalidate token: ' . $employee->token);
                
                try {
                    JWTAuth::setToken($token)->invalidate(true);
                    Log::info('Token invalidated successfully for email: ' . $request->email);
                } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    Log::error('Token invalidation error for email: ' . $request->email . ' - ' . $e->getMessage());
                    return $this->returnError('Token is already invalid');
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    Log::error('JWT Exception for email: ' . $request->email . ' - ' . $e->getMessage());
                    return $this->returnError('JWT Exception: ' . $e->getMessage());
                } catch (\Exception $e) {
                    Log::error('General Exception during token invalidation for email: ' . $request->email . ' - ' . $e->getMessage());
                    return $this->returnError('General Exception: ' . $e->getMessage());
                }
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
                'expires_in' => JWTAuth::factory()->getTTL() * 60
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
            'expires_in' => JWTAuth::factory()->getTTL() * 60
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
    //     if($user->city){
    //     $city = [
    //         [
    //             'id' => null,
    //             'label' => $user->city
    //         ]
    //     ];
    //     $user->city = $city;
       
    //  }
    //  if($user->department){
    //     $department = [
    //         [
    //             'id' => null,
    //             'label' => $user->department
    //         ]
    //     ];
    //     $user->department = $department;
    // }
    // if($user->designation){
    //     $designation = [
    //         [
    //             'id' => null,
    //             'label' => $user->designation
    //         ]
    //     ];
    //     $user->designation = $designation;
    // }
    
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
                    'employee_name' => 'required|string|max:100',
                    'employee_code' => 'required|string|max:10',
                    'mobile_number' => [
                        'required',
                        'size:10',
                        Rule::unique('employees', 'mobile_number')->ignore($id),
                    ],
                      'dob' => 'required|date|max:100',
                      'department' => 'required|string|max:100',
                      'designation' => 'required|string|max:100',
                      'state' => 'required|string|max:100',
                      'city' => 'required|string|max:100',
        
                ],
                [
                    'mobile_number.unique' => 'This Number is Already Registered',
                    'mobile_number.size' => 'Enter 10 Digit Mobile Number',
                ]
            );
                if ($validator->fails()) {
                    $this->error = $validator->errors();
                    throw new \Exception('validation Error');
                }
                // $department = Department::where('name', $request->department)->first();
                // $designation = Designation::where('name', $request->designation)->first();


                // if (!$department) {
                //     $data = new Department();
                //     $data->name = $request->input('department');
                //     $data->save();
                // }
                // if (!$designation) {
                //     $data = new Designation();
                //     $data->name = $request->input('designation');
                //     $data->save();
                // }
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
            //     $validator = Validator::make($request->all(), [
            //         'passport_photo' => 'required',
            //         'profile_photo' => 'required',
        
            //     ]
            // );
            //     if ($validator->fails()) {
            //         $this->error = $validator->errors();
            //         throw new \Exception('validation Error');
            //     }
                if ($request->hasFile('passport_photo')) {    
                    $validator = Validator::make($request->all(), [
                        'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }
                    if($employee->passport_photo != null){
                    $data=explode('storage/', $employee->passport_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $passport_photo=$request->passport_photo;
                    $fileName = "passport_photo_" . uniqid() . "_" . time() . "." . $passport_photo->extension();
                    $path = $passport_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->passport_photo = 'employee/' . $fileName;
                }elseif($employee->passport_photo && $request->passport_photo){
                    $data=explode('storage/', $employee->passport_photo);
                    $employee->passport_photo =$data[1];
                }
                else{
                    if($employee->passport_photo){
                        $data=explode('storage/', $employee->passport_photo);
                        if(file_exists(storage_path('app/public/'.$data[1]))) {
                           unlink(storage_path('app/public/'.$data[1]));
                       }   
                    }
                    $employee->passport_photo = null;
                }
                // if ($request->hasFile('profile_photo')) {
                //     if($employee->profile_photo != null){
                //     $data=explode('storage/', $employee->profile_photo);
                //      if(file_exists(storage_path('app/public/'.$data[1]))) {
                //         unlink(storage_path('app/public/'.$data[1]));
                //     }
                // }
                //     $profile_photo=$request->profile_photo;
                //     $fileName = "profile_photo_" . uniqid() . "_" . time() . "." . $profile_photo->extension();
                //     $path = $profile_photo->move(storage_path("app/public/employee/"), $fileName);
                //     $employee->profile_photo = 'employee/' . $fileName;
                // }
                if ($request->hasFile('profile_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    } 
                    if($employee->profile_photo != null){
                    $data=explode('storage/', $employee->profile_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $profile_photo=$request->profile_photo;
                    $fileName = "profile_photo_" . uniqid() . "_" . time() . "." . $profile_photo->extension();
                    $path = $profile_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->profile_photo = 'employee/' . $fileName;
                }elseif($employee->profile_photo && $request->profile_photo){
                    $data=explode('storage/', $employee->profile_photo);
                    $employee->profile_photo =$data[1];
                }
                else{
                    if($employee->profile_photo){
                        $data=explode('storage/', $employee->profile_photo);
                        if(file_exists(storage_path('app/public/'.$data[1]))) {
                           unlink(storage_path('app/public/'.$data[1]));
                       }   
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
                    $data=explode('storage/', $employee->family_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $family_photo=$request->family_photo;
                    $fileName = "family_photo_" . uniqid() . "_" . time() . "." . $family_photo->extension();
                    $path = $family_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->family_photo = 'employee/' . $fileName;
                }elseif($employee->family_photo && $request->family_photo){
                    $data=explode('storage/', $employee->family_photo);
                    $employee->family_photo =$data[1];
                }
                else{
                    if($employee->family_photo){
                        $data=explode('storage/', $employee->family_photo);
                        if(file_exists(storage_path('app/public/'.$data[1]))) {
                           unlink(storage_path('app/public/'.$data[1]));
                       }   
                    }
                    $employee->family_photo = null;
                }
                $employee->status = $request->input('status');
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
                // $validator = Validator::make($request->all(), [
                //     'employee_name' => 'required|string|max:100',
                //     'employee_code' => 'required|string|max:100',
                //     'profile_photo' => 'required',
                //     'passport_photo' => 'required',
                //     'family_photo' => 'nullable',
                //       'mobile_number' => [
                //         'required',
                //         'unique:employees,mobile_number,'.$id,
                //       ],
                //       'dob' => 'required|date|max:100',
                //       'department' => 'required|string|max:100',
                //       'designation' => 'required|string|max:100',
                //       'state' => 'required|string|max:100',
                //       'city' => 'required|string|max:100',
        
                // ]);
                // if ($validator->fails()) {
                //     $this->error = $validator->errors();
                //     throw new \Exception('validation Error');
                // }
                $employee->employee_name = $request->input('employee_name') ?? $employee->employee_name;
                $employee->dob = $request->input('dob') ?? $employee->dob;
                $employee->department = $request->department ?? $employee->department;
                $employee->designation = $request->designation ?? $employee->designation;
                $employee->state = $request->input('state') ?? $employee->state;
                $employee->city = $request->input('city') ?? $employee->city; 
                $employee->employee_code = $request->input('employee_code') ?? $employee->employee_code;
                $employee->mobile_number = $request->input('mobile_number') ?? $employee->mobile_number;
              
                if ($request->hasFile('passport_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }   
                    if($employee->passport_photo != null){
                    $data=explode('storage/', $employee->passport_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $passport_photo=$request->passport_photo;
                    $fileName = "passport_photo_" . uniqid() . "_" . time() . "." . $passport_photo->extension();
                    $path = $passport_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->passport_photo = 'employee/' . $fileName;
                 }
                // elseif($employee->passport_photo && $request->passport_photo){
                //     $data=explode('storage/', $employee->passport_photo);
                //     $employee->passport_photo =$data[1];
                // }
                // else{
                //     if($employee->passport_photo){
                //         $data=explode('storage/', $employee->passport_photo);
                //         if(file_exists(storage_path('app/public/'.$data[1]))) {
                //            unlink(storage_path('app/public/'.$data[1]));
                //        }   
                //     }
                //     $employee->passport_photo = null;
                // }
                if ($request->hasFile('profile_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }    
                    if($employee->profile_photo != null){
                    $data=explode('storage/', $employee->profile_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $profile_photo=$request->profile_photo;
                    $fileName = "profile_photo_" . uniqid() . "_" . time() . "." . $profile_photo->extension();
                    $path = $profile_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->profile_photo = 'employee/' . $fileName;
                }
                // elseif($employee->profile_photo && $request->profile_photo){
                //     $data=explode('storage/', $employee->profile_photo);
                //     $employee->profile_photo =$data[1];
                // }
                // else{
                //     if($employee->profile_photo){
                //         $data=explode('storage/', $employee->profile_photo);
                //         if(file_exists(storage_path('app/public/'.$data[1]))) {
                //            unlink(storage_path('app/public/'.$data[1]));
                //        }   
                //     }
                //     $employee->profile_photo = null;
                // }
                if ($request->hasFile('family_photo')) {   
                    $validator = Validator::make($request->all(), [
                        'family_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                    ]);
                    if ($validator->fails()) {
                                $this->error = $validator->errors();
                                throw new \Exception('validation Error');
                    }     
                    if($employee->family_photo != null){
                    $data=explode('storage/', $employee->family_photo);
                    if(file_exists(storage_path('app/public/'.$data[1]))) {
                       unlink(storage_path('app/public/'.$data[1]));
                   }   
                  }         
                    $family_photo=$request->family_photo;
                    $fileName = "family_photo_" . uniqid() . "_" . time() . "." . $family_photo->extension();
                    $path = $family_photo->move(storage_path("app/public/employee/"), $fileName);
                    $employee->family_photo = 'employee/' . $fileName;
                }
                // elseif($employee->family_photo && $request->family_photo){
                //     $data=explode('storage/', $employee->family_photo);
                //     $employee->family_photo =$data[1];
                // }
                // else{
                //     if($employee->family_photo){
                //         $data=explode('storage/', $employee->family_photo);
                //         if(file_exists(storage_path('app/public/'.$data[1]))) {
                //            unlink(storage_path('app/public/'.$data[1]));
                //        }   
                //     }
                //     $employee->family_photo = null;
                // }
                 $employee->save();
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
        return $this->returnSuccess(
            $employee,'Profile updated successfully');
    }
}
