<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // === LOGIN ===
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('whatsapp_number', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return back()->withErrors(['whatsapp_number' => 'Nomor WhatsApp atau password salah']);
    }

    // === REGISTER ===
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'whatsapp_number' => 'required|string|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Simpan data registrasi di session untuk verifikasi OTP
        $registrationData = $request->only(['name', 'whatsapp_number', 'password']);
        $request->session()->put('registration_data', $registrationData);

        // Kirim OTP ke WhatsApp (untuk registrasi, set isRegistration=true)
        $result = $this->otpService->sendOtp($request->whatsapp_number, true);

        if ($result['success']) {
            return redirect()->route('register.verify')->with('status', $result['message']);
        }

        return back()->withErrors(['whatsapp_number' => $result['message']]);
    }

    // === REGISTER OTP VERIFICATION ===
    public function showRegisterVerify(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['general' => 'Silakan isi form registrasi terlebih dahulu']);
        }

        return view('auth.register-verify')->with([
            'whatsapp_number' => $registrationData['whatsapp_number'],
        ]);
    }

    public function verifyRegisterOtp(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['general' => 'Sesi registrasi telah kadaluarsa. Silakan daftar kembali.']);
        }

        $request->validate([
            'otp_code' => 'required|string|digits:6',
        ]);

        // Verify OTP
        $otpResult = $this->otpService->verifyOtp($registrationData['whatsapp_number'], $request->otp_code);

        if (!$otpResult['success']) {
            return back()->withErrors(['otp_code' => $otpResult['message']]);
        }

        // Buat user account
        $user = User::create([
            'name' => $registrationData['name'],
            'whatsapp_number' => $registrationData['whatsapp_number'],
            'password' => Hash::make($registrationData['password']),
        ]);

        // Hapus data registrasi dari session
        $request->session()->forget('registration_data');

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    // === LOGOUT ===
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar dari akun!');
    }

    // === FORGOT PASSWORD ===
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['whatsapp_number' => 'required|string']);

        // Send OTP via WhatsApp
        $result = $this->otpService->sendOtp($request->whatsapp_number);

        if ($result['success']) {
            // Store whatsapp number in session for verification
            $request->session()->put('reset_whatsapp_number', $request->whatsapp_number);
            return redirect()->route('password.reset')->with('status', $result['message']);
        }

        return back()->withErrors(['whatsapp_number' => $result['message']]);
    }

    // === RESET PASSWORD ===
    public function showResetPasswordForm(Request $request)
    {
        $whatsapp_number = $request->session()->get('reset_whatsapp_number');

        if (!$whatsapp_number) {
            return redirect()->route('password.request')->withErrors(['whatsapp_number' => 'Silakan minta kode OTP terlebih dahulu']);
        }

        return view('auth.passwords.reset')->with([
            'whatsapp_number' => $whatsapp_number,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string',
            'otp_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verify OTP
        $otpResult = $this->otpService->verifyOtp($request->whatsapp_number, $request->otp_code);

        if (!$otpResult['success']) {
            return back()->withErrors(['otp_code' => $otpResult['message']]);
        }

        // Find user by whatsapp number
        $user = User::where('whatsapp_number', $request->whatsapp_number)->first();

        if (!$user) {
            return back()->withErrors(['whatsapp_number' => 'Nomor WhatsApp tidak terdaftar']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Clear session
        $request->session()->forget('reset_whatsapp_number');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}

