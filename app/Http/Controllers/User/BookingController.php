<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MsTipeKos;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Cloudinary;
// use Cloudinary\Configuration\Configuration;


class BookingController extends Controller
{
    public function showCheckout(Request $request)
    {
        $tipeKos = MsTipeKos::orderBy('bulan')->get();
        $selectedTipeKos = $request->query('tipe_kos'); // ambil dr query

        return view('user.booking.booking', compact('tipeKos', 'selectedTipeKos'));
    }

    public function store(Request $request)
    {
        
        // $request->validate([
        //     'tipe_kos' => 'required|exists:ms_tipe_kos,id_tipe_kos',
        //     'nama_lengkap' => 'required',
        //     'no_hp' => 'required',
        //     'email' => 'required|email',
        //     'alamat' => 'required',
        //     'ktp' => 'required|mimes:jpg,jpeg,png|max:2048',
        //     'periode_penempatan' => 'required',
        //     'note' => 'nullable',
        // ]);
        // $ktp = $request->file('ktp');
        // $fileName =$request->email.'-'.time().'.'.$ktp->extension();
        // $filePath = $ktp->storeAs('ktp', $fileName, 'local');
        // Booking::create([
        //     'nama_lengkap' => $request->nama_lengkap,
        //     'no_hp' => $request->no_hp,
        //     'email' => $request->email,
        //     'tipe_kos' => $request->tipe_kos,
        //     'alamat' => $request->alamat,
        //     'periode_penempatan' => $request->periode_penempatan,
        //     'note' => $request->note,
        //     'ktp' => $fileName,
        // ]);
        // return redirect()->route('dashboard')->with('success', 'Booking berhasil!');
        

        $request->validate([
            'tipe_kos' => 'required|exists:ms_tipe_kos,id_tipe_kos',
            'nama_lengkap' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
            'ktp' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'periode_penempatan' => 'required',
            'note' => 'nullable',
        ]);
        $ktpFile = $request->file('ktp');
        $cloudinary = new Cloudinary();
        $uploadedKtp = $cloudinary->uploadApi()->upload($ktpFile->getRealPath(), [
            'folder' => 'kos/ktp',
            'public_id' => $request->email . '-' . time(),
            'overwrite' => true,
        ]);

        $uploadedKtpUrl = $uploadedKtp['secure_url']; 
        // dd($uploadedKtpUrl);
        // Simpan data booking ke database
        Booking::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'tipe_kos' => $request->tipe_kos,
            'alamat' => $request->alamat,
            'periode_penempatan' => $request->periode_penempatan,
            'note' => $request->note,
            'ktp' => $uploadedKtpUrl, // simpan URL hasil upload
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking berhasil!');
    }
}
