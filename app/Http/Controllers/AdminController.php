<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function fetchOne($adminId)
    {
        $admin = Admin::where("_id", $adminId)->with(['role'])->first();

        if(!isset($admin)){
            return response()->json([
                "error" => false,
                "message" => "Admin not found"
            ]);
        }
        
        return response()->json([
            "error" => false,
            "message" => "OK",
            "result" => $admin
        ]);

    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "email" => "required",
                "password" => "required",
                "role" => "required"
            ]);

            $foundRole = Role::where("role_code", $validated['role'])->first();

            if(!isset($foundRole)){
                return response()->json([
                    "error" => false,
                    "message" => "Role not found"
                ]);
            }

            $admin = new Admin();
            $admin->username = $validated['email'];
            $admin->password = hash('sha256', $validated['password']);
            $admin->role()->associate($foundRole);
            $result = $admin->save();

            if($result){
                return response()->json([
                    "error" => false,
                    "message" => "Admin added",
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
