<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = UserRole::create([
                'name' => 'admin'
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@responsivecenters.com',
            'password' => Hash::make('12345678'),
            'role_id' => $role->id
        ]);

        DB::table('user_roles')->insert([
            'name' => 'CMS',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('user_roles')->insert([
            'name' => 'intake',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
