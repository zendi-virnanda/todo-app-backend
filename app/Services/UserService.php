<?php

namespace App\Services;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly User $user)
    {

    }

    public function me()
    {
        return Auth::user();
    }

    /**
     * Creates a new user in the database.
     *
     * @param array $data An array containing the user's name, email, and password.
     * @return array An array containing the user's name.
     */
    public function register($data){
        $insert = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
        $user=$this->user->create($insert);
        $success['name'] = $user->name;
        return $success;
    }

    /**
     * Logs a user into the application.
     *
     * @param array $data An array containing the user's login credentials.
     * @return array|null An array containing the user's token and name or null if the login failed.
     */
    public function login($data){
        if(Auth::attempt($data)){
            $user = Auth::user();
            $success['token'] = $user->createToken('auth_user_token')->plainTextToken;
            $success['name'] = $user->name;
            return $success;
        }
        else{
            return null;
        }
    }

    public function logout(){
        // Delete all tokens
        Auth()->user()->tokens()->delete();
        // Logout
        auth()->guard('web')->logout();
        return true;
    }


}
