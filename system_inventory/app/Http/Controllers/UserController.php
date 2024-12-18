<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(){
        $users = User::all();

        return response()->json(['data' => $users]);
    }

    public function store(Request $request){
        $request->validate([
            'email' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|max:255',
        ]);

        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        return response()->json(['data' => $user]);
    }
}