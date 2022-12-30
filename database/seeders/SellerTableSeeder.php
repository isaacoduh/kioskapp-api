<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sellers')->delete();
        $data = [
            'name'=> "Seller Musa",
            'email'=> 'seller@xselar.com',
            'password'=> Hash::make('password')
        ];
        Seller::create($data);

    }
}
