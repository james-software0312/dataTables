<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user_verification_tokens;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthenController extends Controller
{
    //Registration
    public function registration()
    {
        return view('auth.registration');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->email_verified = "false";

        $result = $user->save();
        // fake()->regexify('[A-Za-z0-9]{8}');
        // $verifyNumber = fake()->randomNumber();
        $token = Str::random(64);
        DB::table('user_verification_tokens')->where((['email' => $request->email]))->delete();
        DB::table('user_verification_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Mail::send('emails.email_verified', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject(__('Email Veriy?'));
        });

        if($result) {
            return back() ->with('success', 'Sent email!');
        } else {
            return back() ->with('fail', 'Somethis wrong!');
        }
    }

    public function verifyAccount(Request $request)
    {
        // echo $request->email;
        // $request->validate([
        //     'email' => 'required|email|unique:users,email',
        //     // 'verifyNumber' => 'required|min:1',
        // ]);
        $user = user_verification_tokens::where('email',$request->email)->first();
        if($user->token == $request->token) {
            User::where('email',$request->email)->update(['email_verified' =>'true']);
            // return view('auth.login');
            // return redirect()->route('login');
            return back()->with('success', 'Verified');
        } else {
            return back()->with('fali', 'VerifyNumber is wrong.');
        }
    }

    public function verifying_g($token)
    {
        $user = user_verification_tokens::where('token', $token)->first();

        if (!is_null($user)) {
            $newuser = User::where('email',$user->email)->first();
            $newuser->email_verified = 'true';
            $newuser->save();
            Auth::login($newuser);
        }
        // return view('emails.email_verified_act');
        return redirect()->route('login')->with('error', "Sorry your e-mail cannot be identified");
    
    }

    /////Login
    public function login()
    {
        return view('auth.login');
    }

    public function loginUser(Request $request)
    {
        $request-> validate([
            'email' => 'required|email:users',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', '=',$request->email)->first();
        $credentials = $request->only('email', 'password');
        if($user) {
            if($user->email_verified == 'false') {
                return back()->with('fail', 'You must email Verified.');
            }
            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            } else {
                return back()->with('fail', "Credentials do not match !");
            }
        }else{
            
            return back() -> with('fail', 'This email is not register.');
        }
    }


    ///// Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword_act(Request $request)
    {
        
        $request-> validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        $user = User::where('email', '=',$request->email)->first();
        if($user) {

            $token = Str::random(64);

            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
            DB::table('password_reset_tokens')-> insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);


            Mail::send('emails.forgot_password', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject(__('Forgot your password?'));
            });

            return back()->with('success', "Your Email sent.");
        }else{
            
            return back() -> with('fail', 'This email is not existed.');
        }
    }
    public function reset_password($token)
    {
        
        $resetToken = DB::table('password_reset_tokens')->where(['token' => $token])->first();
        if($resetToken)
            return view('auth.reset-password',['token'=>$token,'email'=>$resetToken->email]);
        else return redirect()->route('forgot-password');
    }
    public function reset_password_act(Request $request)
    {

        $request-> validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $resetToken = DB::table('password_reset_tokens')->where(['token' => $request->token])->first();
        if($resetToken){

            $user = User::where('email',$resetToken->email) ->first();
            $user->password = $request->password;
            $user->save();
            return back() -> with('success', 'changed.');
        }
        else{
            return back() -> with('fail', 'This token is not existed.');
        }
    }
}
