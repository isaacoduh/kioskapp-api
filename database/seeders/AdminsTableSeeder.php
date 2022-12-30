<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
        $data = [
            'name'=> "Admin Doe",
            'email'=> 'base_admin@xselar.com',
            'password'=> Hash::make('password'),
            'role' => 'base'
        ];
        Admin::create($data);
    }
}
