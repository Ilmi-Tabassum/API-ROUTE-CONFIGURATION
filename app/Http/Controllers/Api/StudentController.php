<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\ActualValueIsNotAnObjectException;

class StudentController extends Controller
{
// Register API
    public function register(Request $request)
    {
//validation
        $request->validate([
            "name" =>"required",
            "email" =>"required|email|unique:students",
            "password"=>"required|confirmed"

        ]);
//create data
        $student = new Student();
        $student->name= $request->name;
        $student->email=$request->email;
        $student->password= Hash::make($request->password);
        $student->phone_no= $request->phone_no ?? " ";
        $student->save();

//send response
        return response()->json([
            "status"=>1,
            "message"=>"Student Registered"
        ]);
    }

//Login API
    public function login(Request $request)
    {
//        //validation
        $request->validate([

            "email" => "required|email",
            "password" => "required"

        ]);
//        //Check Student
//
        $student = Student::where("email", "=", $request->email)->first();

        if (isset($student->id)) {
            if (Hash::check($request->password, $student->password)) {

//
//                //Create Token
                $token = $student->createToken("auth_token")->plainTextToken;
//
//                //Send response
                return response()->json([
                    "status" => 1,
                    "message" => "Student Logged in successfully",
                    "access_token" => $token
                ]);
//
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "password didnot match"

                ], 404);
            }
        }else{
            return response()->json([
                "status" => 0,
                "message" => "Email not found"

            ], 404);
        }
    }
   // }

//PROFILE API

    public function profile()
    {
        return response()->json([
            "status" =>1,
            "message"=>"Student Profile information",
            "data"=>auth()->user()//by the help of auth function we can find individuals details
        ]);

    }

//LogOut API
    public function logout() //we will delete scantum token
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" =>1,
            "message"=>"student logged Out Successfully"
        ]);
    }
}
