<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class SupplierUserProvider
{
    protected $hash;
    protected $model;

    public function __construct($hash, $model)
    {
        $this->hash = $hash;
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return $this->model::find($identifier);
    }

    public function retrieveByCredentials(array $credentials)
    {
        return $this->model::where($credentials)->first();
    }

    // Implement if needed
    // public function retrieveByToken($token)
    // {
    //     // Implement this method if you're using tokens
    // }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Implement this method if you're using remember tokens
    }
}
