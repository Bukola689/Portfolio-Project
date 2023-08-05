<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangedMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserAuthenticationSetting;
use App\Notifications\ResetPasswordNotification;
use App\Rules\ValidPassword;
use App\Services\Google2FAService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        //dd('ok');
        $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', new ValidPassword()],
            'otp' => ['nullable', 'numeric', 'digits:6'],
        ]);

         $reset = PasswordReset::verifyToken($request->token);
      
        //  if (! $reset) {
        //      return "Invalid token or expired token";
            
        //  }

        $user = User::whereEmail($reset->email)->first();

        if (! $user) {
            return "User not found";
        }

        if ($user->is_locked) {
            return "This action cannot be initiated for a locked account.";
        }

        if ($this->isGoogleAuthEnabled($user) && is_null($request->otp)) {
            return "otp is required to proceed.";
        }

        if (Hash::check($request->password, $user->password)) {
            return "Sorry you can't use your old password";
        }

        if (! is_null($request->otp)) {
            $google2FAService = new Google2FAService($user);

            $google2FAService->validate2faToken($request->otp);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return "Password reset successfully";
    }

    /**
     * Verifies Password Reset Token.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPasswordToken(string $token): JsonResponse
    {
        $passwordReset = PasswordReset::verifyToken($token);

        if (! $passwordReset) {
            return $this->badRequestResponse('Invalid token or expired token');
        }

        if (! $user = User::firstWhere(['email' => $passwordReset->email])) {
            return $this->notFoundResponse('User not found');
        }

        if ($user->is_locked) {
            return $this->forbiddenResponse('This action cannot be initiated for a locked account.');
        }

        return $this->okResponse(
            'Token verified successfully.',
            ['requires_2fa' => $this->isGoogleAuthEnabled($user)]
        );
    }

    /**
     * Check if Google auth is enabled.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    private function isGoogleAuthEnabled(User $user): bool
    {
        $enabled2faMethods = $user->enabled_2fa_methods;

        return $user->has_2fa_enabled && ! is_null($enabled2faMethods) ? in_array(
            UserAuthenticationSetting::TWO_FACTOR_AUTH_METHODS['GOOGLE_AUTH'],
            $enabled2faMethods
        ) : false;
    }
}
