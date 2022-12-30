<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;


class AuthRepository
{
    public function registerSeller(array $data)
    {
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ];
//        dd($data);
        return Seller::create($data);
    }

    public function registerUser(array $data)
    {
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ];

        return User::create($data);
    }
}
