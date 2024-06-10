<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Hash;


class ForgotPasswordController extends Controller
{
    public function ForgotPasswordForm()
    {
        return view('auth.forgot');
    }
    public function submitForgotPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        try {
            $admin = User::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json(['message' => 'Admin not found.'], 404);
        }
        $password = mt_rand(100000, 999999);
        $admin->password=bcrypt($password);
        $admin->save();
        Mail::to($admin->email)->send(new ForgotPassword($password));
        return $this->returnSuccess(
            $password,'Forgot password mail send successfully');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        

    }
   
}
