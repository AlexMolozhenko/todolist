<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Registers a new user and returns user data and token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="oleksii"),
     *              @OA\Property(property="email", type="string", example="oleksii@oleksii.com"),
     *              @OA\Property(property="password", type="string", example="password123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=14),
     *                  @OA\Property(property="name", type="string", example="oleksii"),
     *                  @OA\Property(property="email", type="string", example="oleksii@oleksii.com"),
     *                  @OA\Property(property="created_at", type="string", format="datetime", example="2023-09-12T14:56:20.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime", example="2023-09-12T14:56:20.000000Z")
     *              ),
     *              @OA\Property(property="token", type="string", example="15|yzpxP554W4izIVAJwo1F737uF9VzbMr1bdO9L26E")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $registerData = UserService::UserRegister($fields);

        return response()->json($registerData, 201);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      summary="Log in an existing user",
     *      description="Logs in a user and returns user data and token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="oleksii@oleksii.com"),
     *              @OA\Property(property="password", type="string", example="password123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=14),
     *                  @OA\Property(property="name", type="string", example="oleksii"),
     *                  @OA\Property(property="email", type="string", example="oleksii@oleksii.com"),
     *                  @OA\Property(property="created_at", type="string", format="datetime", example="2023-09-12T14:56:20.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="datetime", example="2023-09-12T14:56:20.000000Z"),
     *              ),
     *              @OA\Property(property="token", type="string", example="15|yzpxP554W4izIVAJwo1F737uF9VzbMr1bdO9L26E")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="user not found or password is incorrect"
     *      ),
     * )
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $loginData = UserService::UserLogin($fields);

        return response()->json($loginData['data'], $loginData['status']);
    }

    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getCurrentUser",
     *      tags={"User"},
     *      summary="Get current user",
     *      description="Returns data of the currently authenticated user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *              @OA\Property(property="email_verified_at", type="string", example="null"),
     *              @OA\Property(property="created_at", type="string", format="datetime",example="2023-09-10T16:20:30.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="datetime",example="2023-09-10T16:20:30.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function getUser(Request $request){
        return $request->user();
    }
}
