<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
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

            // Kiểm tra role và chuyển hướng phù hợp
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('admin/home');
            } else {
                return redirect()->route('home');
            }
        } else { // Thêm bởi Lê Tâm: Kiểm tra nếu user tồn tại nhưng đăng nhập sai kiểu (Đăng ký tài khoản bằng Google nhưng lại đăng nhập loại thường - local )
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->auth_provider != 'local') {
                return redirect()->route('login')->with('status', [
                    'type' => 'error',
                    'message' => 'Email này đã đăng ký bằng ' . strtoupper($user->auth_provider) .
                        '. Vui lòng đăng nhập bằng cách đó.'
                ]);
            }
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
        // 1. Validate dữ liệu đầu vào với rules nghiêm ngặt hơn
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-ZÀ-ỹ\s]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone' => ['nullable', 'string', 'max:15', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'name.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng.',
            'email.regex' => 'Email không đúng định dạng.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'password.mixed_case' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa và 1 chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất 1 chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt.',
        ]);

        // 2. Tạo người dùng mới - CHỈ VỚI ROLE USER
        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone ? trim($request->phone) : null,
            'password' => Hash::make($request->password),
            'role' => 'user', // CỐ ĐỊNH LÀ USER, KHÔNG CHO PHÉP THAY ĐỔI
            'auth_provider' => 'local',
            'email_verified_at' => now(), // Tự động xác thực email cho user đăng ký
        ]);

        // 3. Tự động đăng nhập cho người dùng mới
        Auth::login($user);

        $request->session()->regenerate();

        // 4. Chuyển hướng đến trang chủ với thông báo thành công
        return redirect()->route('home')->with('status', [
            'type' => 'success',
            'message' => 'Đăng ký tài khoản thành công! Chào mừng bạn đến với EcoWaste.'
        ]);
    }

    public function redirectToProvider($provider)
    {
        if ($provider === 'google') {
            return Socialite::driver('google')->redirect();
        }

        if ($provider === 'facebook') {
            return Socialite::driver('facebook')->redirect();
        }

        abort(404);
    }

    public function handleProviderCallback(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'facebook']), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            // dd('Không thể xác thực bằng ' . $provider . '. ' . $e->getMessage());
            // return redirect()->route('login')->withErrors([
            //     'oauth' => 'Không thể xác thực bằng ' . $provider . '. Vui lòng thử lại.',
            // ]);
            return redirect()->route('login')->with('status', [
                'type' => 'error',
                'message' => 'Không thể xác thực bằng ' . strtoupper($provider) . '. Vui lòng kiểm tra và thử lại.'
            ]);
        }

        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName() ?? $socialUser->getNickname();


        // 1) Tìm đúng user theo (auth_provider, provider_id)
        $user = User::where('auth_provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if (!$user && $email) {
            // 2) Có email trùng?
            $byEmail = User::where('email', $email)->first();

            if ($byEmail) {
                // Tài khoản đã được tạo từ provider khác => Chặn (1 user = 1 provider)
                if ($byEmail->auth_provider !== $provider) {
                    return redirect()->route('login')->with('status', [
                        'type' => 'error',
                        'message' => 'Email này đã đăng ký bằng ' . strtoupper($byEmail->auth_provider) .
                            '. Vui lòng đăng nhập bằng cách đó.'
                    ]);
                }

                // Nếu trùng provider mà chưa có provider_id -> cập nhật
                if (!$byEmail->provider_id) {
                    $byEmail->update(['provider_id' => $providerId]);
                }

                $user = $byEmail;
            }
        }

        // 3) Nếu chưa có ai => tạo mới
        if (!$user) {
            if (!$email) {
                return redirect()->route('login')->with('status', [
                    'type' => 'error',
                    'message' => 'Tài khoản ' . ucfirst($provider) . ' không cung cấp email. Vui lòng dùng tài khoản khác.'
                ]);
            }

            $user = User::create([
                'name' => $name ?: 'User ' . Str::random(6),
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
                'role' => 'user',
                'auth_provider' => $provider,
                'provider_id' => $providerId,
                'email_verified_at' => now(),
            ]);
        }

        // 4) Login và chuyển hướng theo role
        Auth::login($user, true);
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin/home');
        } else {
            return redirect()->intended('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Start lại session để đảm bảo token mới được tạo
        $request->session()->start();
        return redirect()->route('login')->with('status', [
            'type' => 'success',
            'message' => 'Log out successfully!'
        ]);
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
        $user = User::where('email', $email)->first();

        // Chỉ gửi nếu user tồn tại và auth provider là local, nhưng phản hồi chung (không lộ thông tin)
        if ($user && $user->auth_provider == 'local') {
            $code = random_int(100000, 999999);
            $hash = Hash::make((string) $code);

            // Mỗi email chỉ có 1 mã đang hiệu lực
            DB::table('password_reset_codes')->where('email', $email)->delete();

            DB::table('password_reset_codes')->insert([
                'email' => $email,
                'code_hash' => $hash,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Gửi mail
            Mail::to($email)->send(new SendCodeMail($code));
        }

        return back()->withInput(['email' => $email])
            ->with(
                'valid',
                'Nếu email hợp lệ, <strong>mã xác thực</strong> đã được gửi. Vui lòng kiểm tra và nhập vào thanh dưới đây.'
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

        $left = 0;

        if ($row->attempts == 1) { // Khi đã nhập sai 5 lần
            DB::table('password_reset_codes')->where('id', $row->id)->delete();
            return back()->withInput()->withErrors(['code' => 'Bạn đã nhập sai quá số lần. Hãy yêu cầu mã mới.']);
        }

        if (!Hash::check($code, $row->code_hash)) {
            DB::table('password_reset_codes')->where('id', $row->id)->update([
                'attempts' => max(0, $row->attempts - 1),
                'updated_at' => now(),
            ]);
            $left = DB::table('password_reset_codes')->where('id', $row->id)->value('attempts');
            return back()->withInput()->withErrors(['code' => "Mã không hợp lệ, bạn còn {$left} lần nhập."]);
        }

        // Mã đúng -> cấp session cho phép sang trang đặt mật khẩu
        $ticket = Str::random(64);
        session([
            'pw_reset_email' => $email,
            'pw_reset_ticket' => $ticket,
        ]);

        // Xóa mã ngay sau khi dùng (1 lần)
        DB::table('password_reset_codes')->where('id', $row->id)->delete();

        return redirect()->route('reset_password.form');
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
        if ($user && $user->auth_provider == 'local') {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
        }

        // Hủy session reset
        session()->forget(['pw_reset_email', 'pw_reset_ticket']);

        return redirect()->route('login')->with('status', [
            'type' => 'success',
            'message' => 'Đặt lại mật khẩu thành công!'
        ]);
    }

    public function searchUsers(Request $request)
    {
        $keyword = $request->get('q', '');
        return response()->json(User::where('name', 'like', "%{$keyword}%")->pluck('name'));
    }
}