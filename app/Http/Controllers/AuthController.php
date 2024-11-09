<?php

namespace App\Http\Controllers;

use random;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)), // Tạo mật khẩu ngẫu nhiên
            ]
        );

        // Tạo token cho người dùng
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = User::firstOrCreate(
            ['email' => $facebookUser->getEmail()],
            [
                'name' => $facebookUser->getName(),
                'password' => bcrypt(Str::random(16)), // Tạo mật khẩu ngẫu nhiên
            ]
        );

        // Tạo token cho người dùng
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user' => $user,
            'token' => $token,
        ]);
    }



    public function register(Request $request)
{
    try {
        // Xác thực yêu cầu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Thêm quy tắc xác thực cho mật khẩu xác nhận
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Tạo token API
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công!',
            'user' => $user,
            'token' => $token,
        ], 201);

    } catch (ValidationException $e) {
        // Xử lý lỗi xác thực
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xác thực.',
            'errors' => $e->validator->errors(), // Trả về lỗi xác thực
        ], 422);
    }
}



public function getUser()
{
    return User::all();
}
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ], 200);
}
public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Đăng xuất thành công!',
    ], 200);
}

public function update(Request $request)
{
    try
    { 
        $userId = Auth::id();
        $request->validate([
        'name' => 'string|max:255',
        'email' => 'email|max:255|unique:users,email,'.$userId,
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date|before:today',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
    ]);
    
    // Find user by ID
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Update user fields
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    $user->date_of_birth = $request->input('date_of_birth');
    $user->address = $request->input('address');

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($user->image) {
            // Xóa ảnh cũ từ storage
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->image));
        }
        // Lưu hình ảnh mới vào thư mục upload/users
        $path = $request->file('image')->store('upload/users', 'public'); // Đường dẫn lưu vào storage
        $user->image = '/storage/' . $path; // Cập nhật đường dẫn vào DB
    }
   $user->save();
    return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }catch (ValidationException $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xác thực.',
            'errors' => $e->validator->errors(), // Trả về lỗi xác thực
        ], 422);
    }catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi không muốn vui long kiểm tra lại',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function updatePassword(Request $request)
{
    try
    {
        
    $userId = Auth::id();
    
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);
    $user = User::find($userId);
    if (!$user) {
        throw new Exception("Không có người dùng này");
    }

    if (!Hash::check($request->input('current_password'), $user->password)) {
        throw new Exception("Mật khẩu hiện tại không đúng");
    }

    // Cập nhật mật khẩu mới
    $user->password = Hash::make($request->input('new_password'));
    $user->save();
    return response()->json(['message' => 'Đổi mật khẩu thành công'], 200);
    }catch (ValidationException $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xác thực.',
            'errors' => $e->validator->errors(), // Trả về lỗi xác thực
        ], 422);
    }catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi không muốn vui long kiểm tra lại',
            'error' => $e->getMessage(),
        ], $e ->getCode()?:500);
    }
}

}
