<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "Super Administrator",
            "Admin",
            "Manager"
        ];

        array_map(function($role){
            return Role::factory()->create([
                "role_name" => $role,
                "role_code" => "PERC".Str::random(5)
            ]);
        }, $roles);
    }
}
