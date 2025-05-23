<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Mail\SetPasswordMail;
use App\Models\{Penyewa, MsTipeKos, Kamar, Users, Payment};

use Cloudinary\Cloudinary;


class PenyewaController extends Controller
{
    public function penyewa()
    {
        // $penghuniAktif = Penyewa::whereNull('tanggal_berakhir')->get();
        $penghuniAktif = Penyewa::where('status_penyewaan', 1)
            ->whereNull('tanggal_berakhir')
            ->get();
        $penghuniRiwayat = Penyewa::whereNotNull('tanggal_berakhir')->get();
        $msTipe = MsTipeKos::orderBy('id_tipe_kos', 'asc')->get();

        return view('admin.admin-penyewa.adminpenyewa', compact('penghuniAktif', 'penghuniRiwayat', 'msTipe'));
    }

    public function updatePenyewa(Request $request)
    {
        $request->validate([
            'id_penyewa' => 'required|integer',
            'nama' => 'required|string',
            'no_telepon' => 'required|string',
            'tipe_kos' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_menyewa' => 'required|date',
            'tanggal_berakhir' => 'nullable|date',
            'no_kamar' => 'required',
            'status_penyewaan' => 'required|boolean',
            'ktp' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ]);

        $penyewa = Penyewa::findOrFail($request->id_penyewa);
        
        // if ($request->hasFile('ktp')) {
        //     $fileName = $request->email . '-' . time() . '.' . $request->file('ktp')->extension();
        //     $filePath = $request->file('ktp')->storeAs('ktp', $fileName);
        //     Storage::delete("ktp/$penyewa->ktp");
        //     $penyewa->ktp = $fileName;
        // }

        if ($request->hasFile('ktp')) {
            // Ambil file KTP dari request
            $ktpFile = $request->file('ktp');
            
            // Inisialisasi Cloudinary
            $cloudinary = new Cloudinary();
            
            // Upload file ke Cloudinary
            $uploadedKtp = $cloudinary->uploadApi()->upload($ktpFile->getRealPath(), [
                'folder' => 'kos/ktp',  // Tentukan folder penyimpanan di Cloudinary
                'public_id' => $request->email . '-' . time(),  // Nama unik berdasarkan email dan waktu
                'overwrite' => true,  // Menimpa jika sudah ada file dengan public_id yang sama
            ]);
    
            // Ambil URL dari file yang diupload
            $uploadedKtpUrl = $uploadedKtp['secure_url'];
            
            // Hapus KTP lama jika ada
            if ($penyewa->ktp) {
                $cloudinary->uploadApi()->destroy('kos/ktp/' . $penyewa->ktp);
            }
    
            // Simpan URL KTP baru di database
            $penyewa->ktp = $uploadedKtpUrl;
        }

        if (!$request->status_penyewaan) {
            $penyewa->tanggal_berakhir = Carbon::now()->toDateString();
            Kamar::where('id_kamar', $penyewa->no_kamar)->update(['status' => 'F']);
            Users::where('email', $penyewa->email)->delete();
        }

        if ($penyewa->no_kamar != $request->no_kamar) {
            Kamar::where('id_kamar', $penyewa->no_kamar)->update(['status' => 'F']);
            Kamar::where('id_kamar', $request->no_kamar)->update(['status' => 'T']);
            $penyewa->no_kamar = $request->no_kamar;
        }

        $penyewa->update($request->except(['ktp', 'tanggal_berakhir']));
        
        return redirect()->back()->with('success', 'Data penyewa berhasil diperbarui.');
    }

    public function detailPenyewa($id)
    {
        return response()->json(Penyewa::findOrFail($id));
    }

    public function tambahPenyewa(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string',
            'no_telepon' => 'required|string',
            'tipe_kos' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_menyewa' => 'required|date',
            'no_kamar' => 'required',
            'status_penyewaan' => 'required|boolean',
            'ktp' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
        

        DB::beginTransaction();

        try {
            $tanggalMenyewa = Carbon::parse($request->tanggal_menyewa);
            $tanggalBooking = $tanggalMenyewa->copy(); 
            $tanggalJatuhTempo = $tanggalMenyewa->copy(); 

            // Simpan file KTP
            $fileName = $request->email . '-' . time() . '.' . $request->file('ktp')->extension();
            $request->file('ktp')->storeAs('ktp', $fileName);

            $penyewa = Penyewa::create([
                'email' => $request->email,
                'nama' => $request->nama,
                'no_telepon' => $request->no_telepon,
                'no_kamar' => $request->no_kamar,
                'tipe_kos' => $request->tipe_kos,
                'alamat' => $request->alamat,
                'ktp' => $fileName,
                'status_penyewaan' => 1,
                'tanggal_booking' => $tanggalBooking->toDateString(),
                'tanggal_menyewa' => $tanggalMenyewa->toDateString(),
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo->toDateString(),
                'tanggal_berakhir' => null,
            ]);

            // Update status kamar menjadi Terisi (T)
            Kamar::where('id_kamar', $request->no_kamar)->update(['status' => 'T']);

            // Buat akun user
            Users::create([
                'email' => $penyewa->email,
                'id_penyewa' => $penyewa->id_penyewa,
                'password' => null, // kosongkan agar harus setel ulang 
                'role' => 'user',
            ]);

            Mail::to($penyewa->email)->send(new SetPasswordMail($penyewa, 'approved', null));

            $hargaKos = DB::table('ms_tipe_kos')->where('id_tipe_kos', $request->tipe_kos)->value('harga');

            // Tambah data pembayaran pertama
            Payment::create([
                'id_penyewa' => $penyewa->id_penyewa,
                'periode_tagihan' => $penyewa->tanggal_menyewa, 
                'id_kamar' => $penyewa->no_kamar,
                'total_tagihan' => $hargaKos,
                'metode_pembayaran' => 'Tunai',
                'tanggal_pembayaran' => $tanggalJatuhTempo->toDateString(),
                'bukti_pembayaran' => null,
                'status_verifikasi' => 1
            ]);

            $langganan = MsTipeKos::findOrFail($penyewa->tipe_kos);

            // Update tanggal jatuh tempo berdasarkan tipe kos
            $penyewa->update([
                'tanggal_jatuh_tempo' => Carbon::parse($penyewa->tanggal_menyewa)->addMonths($langganan->bulan)->format('Y-m-d'),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Penyewa baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menambahkan penyewa: ' . $e->getMessage());
        }
    }


}