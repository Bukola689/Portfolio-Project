<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAuthenticationSetting;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Google2FAService
{
    private $user;

    private $authenticationSetting;

    private  $google2fa;

    private $enabledMethods;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->authenticationSetting = $this->user->authenticationSetting;
        $this->enabledMethods = $this->authenticationSetting->enabled_2fa_methods;

        $this->isEnabledMethodsEmpty = empty($this->enabledMethods);

      //  $this->google2fa = new Google2FA();
    }

    /**
     * Verify 2FA Key.
     *
     * @param string $token
     *
     * @return bool
     */
    public function validate2faToken(string $token): bool
    {
        try {
            $isValidKey = $this->google2fa->verifyKey($this->authenticationSetting->authenticator_secret_key, $token);

            if (! $isValidKey) {
                throw new HttpException(400, 'Invalid authenticator token.');
            }

            return $isValidKey;
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * Generate Secret Key Data.
     *
     * @return array
     */
    public function generateSecretKeyData(): array
    {
        $secretKey = $this->generateSecretKey();

        $qrImageUri = $this->generateQRImage($secretKey);

        return ['secret_key' => $secretKey, 'qr_code_image' => $qrImageUri];
    }

    /**
     * Generate QRImage uri.
     *
     * @param string $secretKey
     *
     * @return string
     */
    private function generateQRImage(string $secretKey): string
    {
        try {
            return $this->google2fa->getQRCodeUrl(
                'Patricia Business',
                $this->user->email,
                $secretKey
            );
        } catch (Throwable $exception) {
            report($exception);

            abort(500, 'Attempt to generate QrCode image failed.');
        }
    }

    /**
     * Generates and stores secret key.
     *
     * @return string
     */
    private function generateSecretKey(): string
    {
        try {
            if (is_null($this->authenticationSetting->authenticator_secret_key)) {
                $this->authenticationSetting->update(['authenticator_secret_key' => $this->google2fa->generateSecretKey()]);
            }

            return $this->authenticationSetting->authenticator_secret_key;
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * Handle an exception encountered.
     *
     * @param \Throwable $e
     *
     * @return void
     */
    private function handleException(Throwable $e)
    {
        if ($e instanceof HttpException) {
            throw $e;
        }
        report($e);

        throw new HttpException(500, 'An error was encountered performing this action.');
    }
}