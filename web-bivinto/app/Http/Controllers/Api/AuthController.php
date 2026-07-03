<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    private $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = env('APP_KEY', 'your-secret-key-here');
    }

    private function generateAccessToken($user)
    {
        $payload = [
            'iss' => env('APP_URL'), // Issuer
            'iat' => time(), // Issued at
            'exp' => time() + (60 * 60), // Expiration time (1 hour)
            'sub' => $user->id, // Subject
            'email' => $user->email,
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    private function generateRefreshToken($user)
    {
        $tokenStr = Str::random(60);
        $expiresAt = Carbon::now()->addDays(30);

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $tokenStr,
            'expires_at' => $expiresAt
        ]);

        return $tokenStr;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/'],
            'password' => 'required|string|min:6',
        ], [
            'phone.regex' => 'Số điện thoại phải bao gồm đúng 10 chữ số và bắt đầu bằng số 0.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Allow login by email or phone
        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Email/SĐT hoặc mật khẩu không chính xác.'], 401);
        }

        $accessToken = $this->generateAccessToken($user);
        $refreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => $user
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        $refreshTokenStr = $request->refresh_token;

        $token = RefreshToken::where('token', $refreshTokenStr)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$token) {
            return response()->json(['error' => 'Refresh token không hợp lệ hoặc đã hết hạn.'], 401);
        }

        $user = $token->user;

        // Xóa token cũ
        $token->delete();

        // Tạo token mới
        $newAccessToken = $this->generateAccessToken($user);
        $newRefreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'bearer',
            'expires_in' => 3600
        ]);
    }

    public function logout(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        RefreshToken::where('token', $request->refresh_token)->delete();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function me(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Không tìm thấy thông tin người dùng hoặc token không hợp lệ.'], 401);
        }

        return response()->json($user);
    }
}
