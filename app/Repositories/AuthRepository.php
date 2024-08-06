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
        $otp = random_int(10000, 999999);
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verification_code' => $otp
        ];

        return User::create($data);
    }
}
