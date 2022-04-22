<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function fetchAll()
    {
        $roles = [];

        try {
            $roles = Role::get()->toArray();
            return response()->json([
                "error" => false,
                "message" => "OK",
                "result" => $roles
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function fetchOne($roleId)
    {
        try {
            $role = Role::where("_id", $roleId)
                ->with('permissions')
                ->first();

            if (!isset($role)) {
                return response()->json([
                    "error" => true,
                    "message" => "Role not found"
                ]);
            }

            return response()->json([
                "error" => false,
                "message" => "OK",
                "result" => $role
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "role_name"     => "required",
                "permissions"   => "required|array",
                "permissions.*" => "string"
            ]);

            $role = new Role();
            $role->role_name = $validated['role_name'];
            $role->role_code = "PERC" . Str::random(5);

            $result = $role->save(); //save role document
            $role->permissions()->sync($validated['permissions']); //save array of permission refs in role document

            if ($result) {
                return response()->json([
                    "error" => false,
                    "message" => "Role added",
                    "result" => "CREATED"
                ]);
            }

            return response()->json([
                "error" => true,
                "message" => "Something went wrong",
                "result" => "FAILED"
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
