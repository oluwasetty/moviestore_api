<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\Resource;
use App\Models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * @OA\Post(path="/login",
     *     tags={"User"},
     *     summary="Logs user into the system",
     *     description="",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         description="Movie object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", ref="#/components/schemas/User/properties/email"),
     *             @OA\Property(property="password", ref="#/components/schemas/User/properties/password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Method Not Allowed",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();
            return Resource::collection([
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ])->additional(['status' => true, 'message' => 'Login Successful'])->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = Auth::login($user);

            return Resource::collection([
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ])->additional(['status' => true, 'message' => 'Registration Successful'])->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(path="/logout",
     *     tags={"User"},
     *     summary="Logs out current logged in user session",
     *     description="",
     *     operationId="logoutUser",
     *     parameters={},
     *     @OA\Response(response="200", description="successful operation"),
     *     security={{"apiAuth": {"write:movies", "read:movies"}}}
     * )
     */
    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            return Resource::collection([
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ])->additional(['status' => true, 'message' => 'Token Refereshed'])->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
