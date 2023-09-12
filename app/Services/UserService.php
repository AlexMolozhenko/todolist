<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Search for a user by email. returns an object User
     * @param $email
     * @return User
     */
    static public function getUserByEmail($email) :User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create new user  and add new token for this user
     * @param array $userData
     * @return array
     */
    static public function UserRegister(array $userData){
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password'])
        ]);

        $token = $user->createToken($user->email)->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }

    /**
     * Check User in the system, if user not found method returned message , if user is found then method return user object and his token
     * @param array $userData
     * @return array
     */
    static public function UserLogin(array $userData)
    {
        $user = self::getUserByEmail($userData['email']);
        if (!$user) {
            return [
                'data'=>[
                    'message' => 'user not found '
                ],
                'status'=>401
            ];
        }
        if (!Hash::check($userData['password'], $user->password)) {
            return [
                'data'=>[
                    'message' => 'password is incorrect '
                ],
                'status'=>401
            ];
        }
        $token = $user->createToken($user->email)->plainTextToken;
        return [
            'data'=>[
                'user' => $user,
                'token' => $token
            ],
            'status'=>200
        ];
    }

}
