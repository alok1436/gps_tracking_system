<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name'=>'Comapny','guard_name'=>'Comapny'],
            ['name'=>'Admin','guard_name'=>'Admin'],
            ['name'=>'Driver','guard_name'=>'Driver']
        ]);
    }
}
