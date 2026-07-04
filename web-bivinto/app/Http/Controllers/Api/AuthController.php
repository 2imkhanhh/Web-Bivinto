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
            'message' => 'Đăng nhập thành công',
            'user' => $user
        ])->cookie('access_token', $accessToken, 60, '/', null, false, true)
          ->cookie('refresh_token', $refreshToken, 60 * 24 * 30, '/', null, false, true);
    }

    public function refresh(Request $request)
    {
        $refreshTokenStr = $request->cookie('refresh_token');

        if (!$refreshTokenStr) {
            return response()->json(['error' => 'Không tìm thấy refresh token.'], 401);
        }

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
            'message' => 'Làm mới token thành công'
        ])->cookie('access_token', $newAccessToken, 60, '/', null, false, true)
          ->cookie('refresh_token', $newRefreshToken, 60 * 24 * 30, '/', null, false, true);
    }

    public function logout(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if ($refreshToken) {
            RefreshToken::where('token', $refreshToken)->delete();
        }

        return response()->json(['message' => 'Đăng xuất thành công'])
            ->withoutCookie('access_token')
            ->withoutCookie('refresh_token');
    }

    public function me(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Không tìm thấy thông tin người dùng hoặc token không hợp lệ.'], 401);
        }

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.regex' => 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 số).',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'
        ]);

        // Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->phone = $request->phone;

        // Cập nhật mật khẩu nếu có
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password')) {
                return response()->json(['error' => 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu mới.'], 400);
            }

            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Mật khẩu hiện tại không chính xác.'], 400);
            }

            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $user->save();

        return response()->json([
            'message' => 'Cập nhật hồ sơ thành công',
            'user' => $user
        ]);
    }
}
