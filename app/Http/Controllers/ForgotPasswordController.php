<?php

namespace App\Http\Controllers;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
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
            return $this->returnSuccess(true);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        

    }
   
}
