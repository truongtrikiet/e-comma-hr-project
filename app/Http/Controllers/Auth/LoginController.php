<?php

namespace App\Http\Controllers\Auth;

use App\Enum\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\Contracts\SettingKey;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $bannerSetting = $this->settingRepository->findByKey(SettingKey::BANNER_URL['key']);

        return view('auth.login', compact('bannerSetting'));
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected UserRepositoryInterface $userRepository,
    ) {
        $this->middleware('guest')->except('logout');
    }

    /**
     * {@inheritdoc}
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $request->merge([
            'status' => UserStatus::ACTIVE->value,
        ]);

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            $this->userRepository->update(auth()->user(), [
                'login_at' => now(),
            ]);

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'status');
    }
}