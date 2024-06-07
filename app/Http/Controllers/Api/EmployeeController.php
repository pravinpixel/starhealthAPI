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

class EmployeeController extends Controller
{
    public function emailverfiy(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|email',
            ]);
            if($validator->fails()) {
                $this->error = $validator->errors();
                throw new \Exception('validation Error');
            }
            $employee = Employee::where('email', $request->email)->first();
            if ($employee) {
                $otp= $this->generateOtp($employee->id);
                $employee->expired_date = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
                $employee->save();
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
            }else{
                $employee = new Employee();
                $employee->email = $request->input('email');
                $employee->expired_date = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
                $employee->save();
                $otp= $this->generateOtp($employee->id);
                $data=explode('@', $employee->email);
                Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
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
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|email',
        ]);
        if($validator->fails()) {
            $this->error = $validator->errors();
            throw new \Exception('validation Error');
        }
        $employee = Employee::where('email', $request->email)->first();
        if ($employee) {
            $otp= $this->generateOtp($employee->id);
            $employee->expired_date = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
            $employee->save();
            $data=explode('@', $employee->email);
            Mail::to( $employee->email)->send(new EmailVerfiy($otp,$data[0]));
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
    public function otpverfiy(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|email',
                'otp' => 'required',
            ]);
            if ($validator->fails()) {
                throw new \Exception('Validation Error');
            }
            $employee = Employee::where('email', $request->email)->first();
            if (!$employee) {
                return $this->returnError('Employee not found');
            }
            if (Carbon::parse($employee->expired_date)->lt(Carbon::now())) {
                return $this->returnError('OTP has expired');
            }
            if ($employee->otp == $request->otp) {
                $employee->otp_verified =true;
                if(!$employee->status){
                    $employee->status ='basic';
                }
                $employee->save();
                $token = JWTAuth::fromUser($employee);
                DB::commit();
                return $this->respondWithToken($token);
            } else {
                DB::rollback();
                return $this->returnError('OTP is Wrong');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return $this->returnError($e->getMessage());
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
        if($user->city){
        $city = [
            [
                'id' => null,
                'label' => $user->city
            ]
        ];
        $user->city = $city;
       
     }
     if($user->department){
        $department = [
            [
                'id' => null,
                'label' => $user->department
            ]
        ];
        $user->department = $department;
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
                    'employee_name' => 'required|string|max:100',
                    'employee_code' => 'required|string|max:100',
                    'mobile_number' => [
                        'required',
                        'unique:employees,mobile_number,'.$id,
                      ],
                      'dob' => 'required|date|max:100',
                      'department' => 'required|string|max:100',
                      'designation' => 'required|string|max:100',
                      'state' => 'required|string|max:100',
                      'city' => 'required|string|max:100',
        
                ]);
                if ($validator->fails()) {
                    $this->error = $validator->errors();
                    throw new \Exception('validation Error');
                }
                $department = Department::where('name', $request->department)->first();

                if (!$department) {
                    $data = new Department();
                    $data->name = $request->input('department');
                    $data->save();
                }
                $employee->employee_name = $request->input('employee_name');
                $employee->dob = $request->input('dob');
                $employee->department = $request->input('department');
                $employee->designation = $request->input('designation');
                $employee->state = $request->input('state');
                $employee->state_id = $request->input('state_id');
                $employee->city = $request->input('city'); 
                $employee->employee_code = $request->input('employee_code');
                $employee->mobile_number = $request->input('mobile_number');
                $employee->status = $request->input('status');
               
            }elseif($request->status == "summary"){
                if ($request->hasFile('passport_photo')) {
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
                if ($request->hasFile('profile_photo')) {
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
                if ($request->hasFile('family_photo')) {    
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
            }else{
                $employee->status = $request->input('status');
                $employee->employee_status ='register';
                Mail::to( $employee->email)->send(new Thankyou($employee->employee_name));
            }
            $employee->save();
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
        return $this->returnSuccess(
            $employee,'Employee created successfully');
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
                $employee->employee_name = $request->input('employee_name');
                $employee->dob = $request->input('dob');
                $employee->department = $request->input('department');
                $employee->designation = $request->input('designation');
                $employee->state = $request->input('state');
                $employee->city = $request->input('city'); 
                $employee->employee_code = $request->input('employee_code');
                $employee->mobile_number = $request->input('mobile_number');
              
                if ($request->hasFile('passport_photo')) {
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
                if ($request->hasFile('profile_photo')) {
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
                if ($request->hasFile('family_photo')) {    
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
                 $employee->save();
        } catch (\Throwable $e) {
            return $this->returnError($this->error ?? $e->getMessage());
        }
        return $this->returnSuccess(
            $employee,'Employee updated successfully');
    }
}
