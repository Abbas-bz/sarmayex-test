<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PassportLoginService
{
    /**
     * @param  array  $credentials
     * @return mixed
     * @throws \Common\User\V1\Exceptions\LoginFailedException
     * @throws \Exception
     */
    public function login(array $credentials)
    {
        $user = $this->tryLoginUser($credentials);

        if (is_null($user)) {
            throw new LoginFailedException();
        }

        $token = $this->createTokenFor($user);

        return [
            'token_type'   => 'Bearer',
            'expires_at'   => $token->token->expires_at,
            'expires_at_timestamp'   => $token->token->expires_at->timestamp,
            'access_token' => $token->accessToken,
        ];
    }

    public function createTokenFor(User $user)
    {
        return $user->createToken('app');
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @return User|null
     */
    public function tryLoginUser(array $credentials): ?User
    {
        $user = User::where('username', $credentials['username'])->firstOrFail();

        if (Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    public function logout()
    {
        return Auth::user()->token()->revoke();
    }

    public function register($credentials)
    {
        $user = User::create([
            'username' => $credentials['username'],
            'password' => Hash::make($credentials['password'])
        ]);
        $token = $this->createTokenFor($user);

        return [
            'token_type'   => 'Bearer',
            'expires_at'   => $token->token->expires_at,
            'expires_at_timestamp'   => $token->token->expires_at->timestamp,
            'access_token' => $token->accessToken,
        ];
    }
}
