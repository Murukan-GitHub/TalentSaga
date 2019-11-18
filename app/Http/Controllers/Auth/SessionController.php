<?php

namespace App\Http\Controllers\Auth;

use App\Models\OauthUser;
use Auth;
use View;
use Redirect;
use Session;
use Socialite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(Request $request)
    {
        return view('session.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'email' => 'required_without:username|email',
            'username' => 'required_without:email',
            'password' => 'required',
        ];

        $this->validate($request, $rules);
        $credentials = [($request->has('email') ? 'email' : 'username'), 'password'];

        if (!auth()->attempt($request->only($credentials))) {
            $this->notifyError(trans('notification.wrongemailpassword'));
            return redirect()->back()->withInput();
        }

        $user = auth()->user();
        if ($user && $user->status != User::STATUS_ACTIVE) {
            auth()->logout();
            $this->notifyError(trans('notification.notactivatedyet'));
            return redirect()->back()->withInput();
        }
        $user->updateLastvisit();

        return redirect()->route('sessions.login');
    }

    public function guest(Request $request)
    {
        $email = $request->get('email');
        if ($user = User::whereEmail($email)->where('status', '<>', User::STATUS_UNREGISTERED)->first()) {
            $this->notifyMessage(trans('notification.alreadyregistered'));
            return redirect()->route('sessions.login', compact('email'));
        }

        $user = User::firstOrCreate(['email' => $email, 'status' => User::STATUS_UNREGISTERED]);
        auth()->loginUsingId($user->id);

        if (session()->has('redirectTo')) {
            return redirect()->to(session()->pull('redirectTo'));
        }

        return redirect()->intended(route('frontend.home'));
    }

    public function destroy(Request $request)
    {
        $successUpdate = Auth::user()->updateLastvisit();
        if ($successUpdate) {
            Auth::logout();
        } 
        return redirect()->route('frontend.home');
    }

    public function auth($app)
    {
        return Socialite::driver($app)->redirect();
    }

    public function redirectAuth(Request $request, $app)
    {
        $socialite = Socialite::driver($app)->user();
        $socialiteUser = User::where('email', $socialite->email)->first();
        
        $oauthData = [
            'provider' => $app,
            'oauth_id' => $socialite->id,
        ];

        $oauth = OauthUser::firstOrNew($oauthData);

        $oauthData['graph'] = $socialite;
        $oauth->fill($oauthData);

        if ($user = auth()->user()) {
            $user->oauths()->save($oauth);
            return redirect()->route('sessions.login');
        }
        
        $oauth->save();

        $user = $oauth->user ?: ($socialiteUser ?: $user);

        // if no user means = need registration
        if ($user == null) {
            $registrationData = [
                'name' => $socialite->name,
                'email' => $socialite->email,
                'username' => $socialite->nickname ?: getUsernameByEmail($socialite->email),
                'oauth_id' => $oauth->id,
            ];
            $date = new Carbon;
            $user = new User;
            $initiatedData = [
                'password' => bcrypt($user->generateReffCode()),
                'role' => User::USER,
                'birthdate'=>  $date,
                'registration_date' => $date,
                'last_visit' => $date,
                'status' => User::STATUS_ACTIVE
            ];
            $userData = array_merge($registrationData, $initiatedData);
            $user->fill($userData);
            $user->save();

            // return redirect()->route('frontend.user.registration')->withInput($registrationData);
        }

        if ($user->isActive()) {
            auth()->loginUsingId($user->id);
        } else {
            $this->notifyError(trans('notification.notactivatedyet', ['email' => $user->email]));
        }
        return redirect()->route('sessions.login');
    }

    public function notify($type, $msg = null)
    {
        $msg && session()->put($type, $msg);
    }

    public function notifySuccess($msg = null)
    {
        $this->notify('success', $msg);
    }

    public function notifyMessage($msg = null)
    {
        $this->notify('message', $msg);
    }

    public function notifyError($msg = null)
    {
        $this->notify('error', $msg);
    }
}
