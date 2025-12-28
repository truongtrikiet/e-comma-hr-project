<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\Contracts\SettingKey;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Show the reset password form for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        $bannerSetting = $this->settingRepository->findByKey(SettingKey::BANNER_URL['key']);

        return view('auth.reset-password', compact('bannerSetting'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
    ) {
        $this->middleware('guest')->except('reset-password');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'max:20',
                Password::min(8)->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }
}