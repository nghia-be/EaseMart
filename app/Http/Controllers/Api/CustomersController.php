<?php

namespace App\Http\Controllers\Api;

use random;
use Exception;
use App\Models\User;
use App\Models\Customers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;


class CustomersController extends Controller
{

    

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customers::where('email', $request->email)->first();

        // Kiểm tra OTP đã được xác thực chưa
        // if (!$customer->otp_verified || $customer->otp_code !== (int)$request->otp_code) {
        //     return response()->json(['error' => 'Mã OTP không hợp lệ hoặc chưa xác thực'], 400);
        // }

        // Cập nhật mật khẩu mới
        $customer->password = Hash::make($request->password);
        $customer->otp_code = null; // Xóa mã OTP sau khi đổi mật khẩu thành công
        $customer->otp_verified = false;
        $customer->otp_expires_at = null;
        $customer->save();

        return response()->json(['success' => 'Đổi mật khẩu thành công']);
    }

    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'otp_code' => 'required|numeric',
        ]);

        $customer = Customers::where('email', $request->email)->first();

        // Kiểm tra OTP và thời gian hết hạn
        if ($customer->otp_code !== (int)$request->otp_code || now()->greaterThan($customer->otp_expires_at)) {
            return response()->json(['error' => 'Mã OTP không hợp lệ hoặc đã hết hạn'], 400);
        }

        // Nếu mã OTP đúng, cho phép đổi mật khẩu
        $customer->otp_verified = true;
        $customer->save();

        return response()->json(['success' => 'Xác thực OTP thành công']);
    }

    public function sendOtp(Request $request)
    {
        try {
            
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        $otp = rand(1000, 9999); // Tạo mã OTP 6 chữ số
        
        // Lưu OTP vào bảng `customers`
        $customer = Customers::where('email', $request->email)->first();
        $customer->otp_code = $otp;
        $customer->otp_expires_at = now()->addMinutes(10); // OTP hết hạn sau 10 phút
        $customer->save();
        
        // Gửi OTP qua email
        
            Mail::raw("Mã OTP của bạn là: $otp", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Quên mật khẩu - Mã OTP');
            });
            return response()->json(['success' => 'Mã OTP đã được gửi thành công!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể gửi email: ' . $e->getMessage()], 500);
        }
        

        // return response()->json(['success' => 'OTP đã được gửi đến email của bạn']);
    }


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
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8', // Thêm quy tắc xác thực cho mật khẩu xác nhận
        ]);
        // Tạo người dùng mới
        $customers = Customers::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        // Tạo token API
        $token = $customers->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công!',
            'customers' => $customers,
            'token' => $token,
        ], 201);

    } catch (ValidationException $e) {
        // Xử lý lỗi xác thực
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xác thực.',
            'errors' => $e->validator->errors(), // Trả về lỗi xác thực
        ], 422);
    }catch (QueryException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi không muốn vui long kiểm tra lại',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function getCustomers()
{
    return Customers::all();
}
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $customers = Customers::where('email', $request->email)->first();

    if (!$customers || !Hash::check($request->password, $customers->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
    if ($customers->status == 0) {
        return response()->json([
            'message' => 'Tài khoản của bạn hiện đã bị chặn. Vui lòng liên hệ nghiatd12@fpt.com để hỗ trợ.'
        ], 403);
    }

    $token = $customers->createToken('API Token')->plainTextToken;

    return response()->json([
        'customers' => $customers,
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
        $customersId = Auth::id();
        $request->validate([
        'name' => 'string|max:255',
        'email' => 'email|max:255|unique:customers,email,'.$customersId,
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date|before:today',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
    ]);
    // Find customers by ID
    $customers = Customers::find($customersId);
    if (!$customers) {
        return response()->json(['message' => 'Customers not found'], 404);
    }

    // Update customers fields
    $customers->name = $request->input('name');
    $customers->email = $request->input('email');
    $customers->phone = $request->input('phone');
    $customers->address = $request->input('address');
    $customers->birth_date = $request->input('birth_date');
    $customers->gender = $request->input('gender');
    $customers->address = $request->input('address');

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($customers->image) {
            // Xóa ảnh cũ từ storage
            Storage::disk('public')->delete(str_replace('/storage/', '', $customers->image));
        }
        // Lưu hình ảnh mới vào thư mục upload/customers
        $path = $request->file('image')->store('upload/customers', 'public'); // Đường dẫn lưu vào storage
        $customers->image = '/storage/' . $path; // Cập nhật đường dẫn vào DB
    }
   $customers->save();
    return response()->json(['message' => 'Cập nhập thông tin thành công', 'customers' => $customers], 200);
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
    $customersId = Auth::id();
    
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8',
    ]);
    $customers = Customers::find($customersId);
    if (!$customers) {
        throw new Exception("Không có người dùng này");
    }

    if (!Hash::check($request->input('current_password'), $customers->password)) {
        throw new Exception("Mật khẩu hiện tại không đúng");
    }

    // Cập nhật mật khẩu mới
    $customers->password = Hash::make($request->input('new_password'));
    $customers->save();
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
