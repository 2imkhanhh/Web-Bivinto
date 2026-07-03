<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtGuard implements Guard
{
    protected $request;
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!$token && isset($_COOKIE['access_token'])) {
            $token = $_COOKIE['access_token'];
        }
        if ($token) {
            try {
                $secret = env('APP_KEY', 'your-secret-key-here');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));
                $this->user = $this->provider->retrieveById($decoded->sub);
            } catch (\Exception $e) {
                $this->user = null;
            }
        }

        return $this->user;
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function hasValidCredentials($user, $credentials)
    {
        return false;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser()
    {
        return ! is_null($this->user);
    }
}
