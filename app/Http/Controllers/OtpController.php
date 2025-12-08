<?php

namespace App\Http\Controllers; // Pastikan namespace Anda benar: App\Http\Controllers atau App\Http\Controllers\Auth

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // <-- PERBAIKAN UNTUK ERROR "Auth not found"
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Menghasilkan, menyimpan, dan mengirimkan kode OTP via email.
     */
    public function sendOtp(Request $request)
    {
        // 1. Validasi Input Email
        $request->validate([
            'email' => 'required|email|exists:users,email', // Pastikan email ada
        ]);
        
        // --- PERBAIKAN: Definisikan $user di sini ---
        $user = User::where('email', $request->email)->first();

        // Cek jika user tidak ditemukan (meskipun harusnya sudah dicek oleh exists:users,email)
        if (!$user) {
            return back()->with('error', 'Pengguna tidak ditemukan.');
        }
        // ------------------------------------------

        // 2. Generate dan Simpan OTP
        $otp = strval(random_int(100000, 999999));
        
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5); 
        $user->save();
        
        // 3. Kirim Email
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim OTP ke ' . $user->email . ': ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim kode OTP.');
        }

        return back()->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek kotak masuk Anda.');
    }

    /**
     * Memverifikasi kode OTP yang dimasukkan pengguna.
     */
    public function verifyOtp(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);
        
        // --- PERBAIKAN: Definisikan $user di sini ---
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }
        // ------------------------------------------
        
        // 2. Periksa Kecocokan Kode
        if ($user->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // 3. Periksa Kedaluwarsa Waktu
        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            // Hapus kode yang sudah expired
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save(); 
            
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Minta kode baru.']);
        }

        // 4. Verifikasi Berhasil
        
        $user->otp_code = null;
        $user->otp_expires_at = null;

        // Tandai email sudah terverifikasi
        $user->email_verified_at = Carbon::now(); 
        $user->save();

        return redirect()->route('home')->with('success', 'Verifikasi email berhasil! Selamat datang.');
    }

    /**
     * Menampilkan halaman formulir verifikasi OTP dan mengirim kode pertama.
     */
public function showVerificationForm(Request $request)
    {
        $user = Auth::user();

        // 1. Jika pengguna sudah terverifikasi, redirect ke home.
        if ($user->hasVerifiedEmail()) {
            return redirect(RouteServiceProvider::HOME); 
        }

        // 2. Tentukan apakah kode harus dikirim:
        //    a) Jika ini adalah registrasi baru (dideteksi dari session 'email')
        //    b) ATAU jika waktu kedaluwarsa sudah terlewat
        
        $should_send_otp = session('email') != null; // Cek registrasi baru

        // Jika bukan registrasi baru, cek apakah kode yang ada sudah expired
        if (!$should_send_otp && $user->otp_expires_at) {
            // Perhatian: Hanya panggil isAfter jika kolom otp_expires_at TIDAK NULL
            if (Carbon::now()->isAfter($user->otp_expires_at)) {
                $should_send_otp = true; // Kode lama sudah expired
            }
        } 
        // Kasus ketika $user->otp_expires_at IS NULL (paling sering terjadi pada login pertama
        // setelah register), kita anggap perlu kirim OTP jika belum ada.
        elseif (!$should_send_otp && is_null($user->otp_expires_at)) {
             $should_send_otp = true;
        }

        // 3. Kirim kode OTP jika diperlukan
        if ($should_send_otp) {
            
            // Generate dan simpan OTP
            $otp = strval(random_int(100000, 999999));
            $user->otp_code = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(5); 
            $user->save();
            
            // Kirim Email dan flash status
            try {
                Mail::to($user->email)->send(new OtpMail($otp));
                session()->flash('status', 'Kode verifikasi baru telah dikirimkan ke email Anda.');
            } catch (\Exception $e) {
                Log::error('Gagal mengirim OTP: ' . $e->getMessage());
                session()->flash('error', 'Gagal mengirim kode verifikasi. Coba lagi.');
            }
        } else {
             // Jika kode masih valid, tampilkan pesan bahwa kode sudah dikirim
            session()->flash('status', 'Kode verifikasi sudah dikirim, silakan cek kotak masuk Anda.');
        }

        // Tampilkan view verifikasi
        return view('auth.verify', [
            'email' => $user->email,
        ]);
    }
}