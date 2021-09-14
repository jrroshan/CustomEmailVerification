<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\VerifyUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function login()
    {
        return view('auth.login');
    }

    public function validateLogin(Request $request)
    {
        $credentials = $request->only('email','password');
        // dd(Auth::attempt(['email' => $request->email, 'password' => $request->password]));
        // if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
        //     $credentials->session()->regenerate();
        //     return redirect('/');
        
        // if (Auth::attempt($credentials,$request->remember)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('home');
        // }
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // dd('hello');
            // dd(Auth::user()->email_verified_at);
            if (Auth::user()->email_verified_at == null) {
                // dd('hello');
                Auth::logout();
                return redirect()->route('login')->with('error', 'Please Verify your email to continue');
            }
            return redirect()->route('home')->with('success', 'logged in successfully');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        VerifyUser::create([
            'token' => Str::random(60),
            'user_id' => $user->id,
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));
        return redirect()->route('login')->with('success', 'Please click on the link sent to your email');
    }

    public function verifyEmail($token)
    {
        $verifiedUser = VerifyUser::where('token', $token)->first();
        if (isset($verifiedUser)) {
            $user = $verifiedUser->user;
            if (!$user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();
                return redirect(route('login'))->with('success', 'Your email has been verified');
            } else {
                return redirect()->back()->with('info', 'Your email has already been verified');
            }
        } else {
            return redirect(route('login'))->with('error', 'Something went wrong!!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
