<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\User;
use App\Models\History;
use App\Models\Message;
use App\Models\WebsiteLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\VirusTotalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\SafeBrowsingService;
use App\Jobs\SendLoginNotificationMail;

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
        // $users = User::where('id','!=',Auth::id())->get();
        $users = DB::select("select users.id, users.name,users.phone, count(isRead) as unread
        from users LEFT  JOIN  messages ON users.id = messages.from and isRead = 0 and messages.to = " . Auth::id() . "
        where users.id != " . Auth::id() . "
        group by users.id, users.name,users.phone");

        return view('homepage',compact('users'));
    }

    public function getMessage($id)
    {

        $myId = Auth::id();
        Message::where(['from' => $id, 'to' => $myId])->update(['isRead' => 1]);
        $messages = Message::where(function($query) use ($id,$myId){
            $query->where('from',$myId)->where('to',$id);
        })->orWhere(function($query) use ($id,$myId){
            $query->where('from',$id)->where('to',$myId);
        })->get();

        return view('message',compact('messages'));
    }


    public function sendMessage(Request $request)
    {
        $from = Auth::id();
        $to = $request->receiver_id;
        $message = $request->message;

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $message, $match);

        $virusTotalService = new VirusTotalService();
        $isSafe = $virusTotalService->isSafeUrl($match[0]);
        if (!$isSafe) {
            return response()->json("Unsafe url detected",400);
        }


        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->text = $message;
        $data->isRead = 0; // message will be unread when sending message
        $data->save();

        // pusher
        $options = array(
            'cluster' => 'ap2',
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = ['from' => $from, 'to' => $to]; // sending from and to user id when pressed enter
        $pusher->trigger('my-channel', 'my-event', $data);
    }

}
