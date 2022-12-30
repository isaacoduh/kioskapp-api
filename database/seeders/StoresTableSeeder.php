<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->delete();
        $data = [
            '0' => array(
                'title' =>'Rango Mart',
                'description' => 'Sales of home improvement products',
                'type' => 'retail',
                'seller_id' => 1
            )
        ];

        foreach ($data as $key => $item) {
            Store::create($item);
        }
    }
}
