<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    //generate incremental user id
    public function generateUserId() {
        $lastUser = User::orderBy('user_id', 'desc')->first();
        if(!$lastUser){
            return '1';
        }
        $lastUserId = $lastUser->user_id;
        $nextUserId = strval(intval($lastUserId) + 1);
        while(User::where('user_id', $nextUserId)->exists()){
            $nextUserId = strval(intval($nextUserId) + 1);
        }
        return strval($nextUserId);
    }

    //register a user
    public function register(Request $request) {
        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            // If an existing user with the same email is found, return a response indicating that the email is already taken.
            return response()->json(['message' => 'Email is already taken'], 422);
        }

        $user = new User();
        $user->user_id = $this->generateUserId();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role', 'user');
        $user->save();
        return response()->json(['result' => $user]);
    }


    //login a user
    public function login(Request $request){
        $user = User::where('email', $request->input('email'))->first();
        if(!$user|| !Hash::check($request->password, $user->password)){
            return ["error"=>"Email or password is incorrect"];
        }
        return $user;
    }

    // get all user
    public function getAllUser(){
        $user = User::all();
        return response()->json(['result' => $user]);
    }

    // delete a user
    public function deleteUser($user_id){
        $user = User::where('user_id', $user_id)->first();
        if(!$user){
            return response()->json(['result' => "User not found"], 404);
        }
        $user->delete();
        return response()->json(['result' => $user]);
    }

    

}
