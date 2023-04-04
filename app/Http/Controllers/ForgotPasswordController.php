<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendResetPasswordMail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showForgetPasswordForm()
    {
        return view('auth.forget-password');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $incomingField = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $request->email
        ],['token' => $token,'created_at' => Carbon::now()]);


        $user = User::where('email',$incomingField['email'])->first();


        dispatch(new SendResetPasswordMail([
            "sendto" => $request->email,
            "username" => $user->name,
            "token" => $token
        ]));


        return back()->with('success','We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($token)
    {
        return view('backend.auth.resetpassword',["token"=>$token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:4|confirmed'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                              'email' => $request->email,
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect('/reset-success')->with('success', 'Your password has been changed!');
    }

    public function showResetPasswordSuccess()
    {
        return view('backend.auth.reset-password-success');
    }
}
