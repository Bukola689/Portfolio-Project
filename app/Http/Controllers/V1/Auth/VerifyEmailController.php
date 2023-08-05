<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {
        $user = User::find($id);

        abort_if(!$user, 403);

        abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);

        if(!$user->hasVerifiedEmail()) {

            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return view('verified-account');
    }

    public function resendNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return  response()->json([
            'message' => 'An Email Verification Has Been Resent To You !'
        ]);
    }
}
