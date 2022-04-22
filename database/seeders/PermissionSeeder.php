<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Str;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "Create-Users",
            "Create-Roles",
            "Create-Permissions",
            "Fetch-Users",
            "Fetch-Roles",
            "Fetch-Permissions"
        ];

        array_map(function($permission){
            return Permission::factory()->create([
                "permission_name" => $permission,
                "permission_code" => "PERC".Str::random(5)
            ]);
        }, $permissions);
    }
}
