<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use App\Models\WebsiteLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Services\SafeBrowsingService;
use App\Jobs\SendLoginNotificationMail;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function performLogin(Request $request)
    {
        $incomingFields = $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt(['phone' => $incomingFields['phone'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/login')->with('failure', 'Invalid login.');
        }
    }


    public function showRegistrationForm()
    {
        return view('auth.registration');
    }

    public function performRegistration(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required|string',
            'email' => ['required',Rule::unique('users','email')],
            'phone' => ['required',Rule::unique('users','phone')],
            'password' => 'required|string'
        ]);

        User::create([
            "email" => $incomingFields['email'],
            "phone" => $incomingFields['phone'],
            "name" => $incomingFields['name'],
            "password" => bcrypt($incomingFields['password'])
        ]);

        if (auth()->attempt(['phone' => $incomingFields['phone'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/login')->with('failure', 'Registration failed.');
        }
    }

    public function logout() {
        auth()->logout();
        return redirect('/login')->with('success', 'You are now logged out.');
    }

    public function showHomePage()
    {
        $users = User::where('id','!=',Auth::id())->get();
        return view('homepage',compact('users'));
    }


}
