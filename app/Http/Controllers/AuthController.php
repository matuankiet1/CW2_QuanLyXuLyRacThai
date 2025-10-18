<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendCodeMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }


    // Xử lý logic đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Thử đăng nhập
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard'); // Chuyển hướng đến trang dashboard sau khi thành công
        }

        // Nếu đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị form đăng ký.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý yêu cầu đăng ký.
     */
    public function register(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:15'], // Tùy chọn, có thể là required
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // 2. Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // Gán role_id mặc định nếu cần
        ]);

        // 3. Tự động đăng nhập cho người dùng mới
        Auth::login($user);

        // 4. Chuyển hướng đến trang dashboard
        return redirect('/dashboard'); // Hoặc bất kỳ trang nào bạn muốn
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function sendCode(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);
        $email = strtolower($data['email']);

        // Chỉ gửi nếu user tồn tại, nhưng phản hồi chung (không lộ thông tin)
        if (User::where('email', $email)->exists()) {
            $code = random_int(100000, 999999);
            $hash = Hash::make((string) $code);

            // Mỗi email chỉ có 1 mã đang hiệu lực (theo logic của app)
            DB::table('password_reset_codes')->where('email', $email)->delete();

            DB::table('password_reset_codes')->insert([
                'email' => $email,
                'code_hash' => $hash,
                'attempts' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Gửi mail (dev: Mailtrap; prod: SMTP thật)
            Mail::to($email)->send(new SendCodeMail($code));
        }

        return back()->withInput(['email' => $email])
            ->with(
                'status',
                'Nếu email hợp lệ, mã xác thực đã được gửi. Vui lòng nhập mã xác thực vào thanh dưới đây.'
            );

    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        $email = strtolower($data['email']);
        $code = $data['code'];

        $row = DB::table('password_reset_codes')
            ->where('email', $email)
            ->orderByDesc('id')
            ->first();

        if (!$row) {
            return back()->withInput()->withErrors(['code' => 'Mã không hợp lệ']);
        }

        if ($row->attempts = 0) { // Giới hạn 5 lần nhập sai
            DB::table('password_reset_codes')->where('id', $row->id)->delete();
            return back()->withInput()->withErrors(['code' => 'Bạn đã nhập sai quá số lần. Hãy yêu cầu mã mới.']);
        }

        if (!Hash::check($code, $row->code_hash)) {
            DB::table('password_reset_codes')->where('id', $row->id)->update([
                'attempts' => $row->attempts - 1,
                'updated_at' => now(),
            ]);
            return back()->withInput()->withErrors(['code' => "Mã không hợp lệ, bạn còn {$row->attempts} lần nhập."]);
        }

        // Mã đúng -> cấp session cho phép sang trang đặt mật khẩu
        $ticket = Str::random(64);
        session([
            'pw_reset_email' => $email,
            'pw_reset_ticket' => $ticket,
        ]);

        // Xóa mã ngay sau khi dùng (1 lần)
        DB::table('password_reset_codes')->where('id', $row->id)->delete();

        return redirect()->route('reset_password.form')
            ->with('status', 'Xác thực thành công. Hãy đặt mật khẩu mới.');
    }

    public function showResetPasswordForm()
    {
        $verified = session('pw_reset_email') && session('pw_reset_ticket');
        if (!$verified) {
            return redirect()->route('forgot_password.form')
                ->withErrors(['email' => 'Vui lòng xác thực mã trước khi đặt mật khẩu.']);
        }

        return view('auth.reset_password', [
            'email' => session('pw_reset_email'),
            'status' => session('status'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $email = session('pw_reset_email');

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
        }

        // Hủy session reset
        session()->forget(['pw_reset_email', 'pw_reset_ticket']);

        return redirect()->route('login')->with('status', 'Đặt lại mật khẩu thành công.');
    }
}