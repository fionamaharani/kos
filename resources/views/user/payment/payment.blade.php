@extends('layout.layout')
@section('title', 'Payment | Kostiva')

@push('styles')
<style>
    .main_menu .navbar-brand {
        color: #ffffce;
    }

    .main_menu .main-menu-item ul li .nav-link {
        color: #ffffce;
    }

    .main_menu .main-menu-item ul li .nav-link:hover {
        color: #6987af;
    }

    .main_menu .d-flex .nav-item {
        color: #ffffce;
    }

    .main_menu .d-flex .nav-item:hover {
        color: #6987af;
    }

</style>
@endpush

@section('content')
<!--================Home Banner Area =================-->
<!-- breadcrumb start-->
<section class="breadcrumb breadcrumb_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb_iner">
                    <div class="breadcrumb_iner_item">
                        <h2>Payment Confirmation</h2>
                        <p>Home <span>-</span> Payment Confirmation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb start-->

<!--================ confirmation part start =================-->
<section class="confirmation_part padding_top">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="billing_details">
            <div class="row">
                <div class="col-lg-12">
                    <div class="confirmation_tittle">
                        <?php
                        // dd($detailPenyewa);

                        if($isFirstPayment){
                            echo "
                                <p>Silahkan lakukan pembayaran pertama untuk aktivasi proses booking kamar kos. <br> 
                                <span>Pembayaran paling lambat pada 
                                 ".\Carbon\Carbon::parse($detailPenyewa->tanggal_booking)->addDays(2)->format('d M Y') ."</span></p>";
                        }
                        elseif(\Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->diffInDays(\Carbon\Carbon::now()) > 0){
                            echo "
                                <p>Menghimbau untuk segera melakukan pembayaran karena masa tagihan akan segera berakhir. <br>
                                <span>Pembayaran paling lambat pada 
                                 ".\Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->subDay()->format('d M Y') ."</span></p>";
                        }else{
                            echo " 
                                <p>Terima kasih telah melakukan pembayaran. <br> 
                                <span>Harap lakukan pembayaran sebelum masa tagihan berakhir pada ".\Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->subDay()->format('d M Y') ."</span>
                                </p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="single_confirmation_details">
                        <h4>{{$isFirstPayment ? 'Pembayaran Pertama':'Status Langganan'}}</h4>
                        <ul>
                            {{-- saat baru pertama kali bayar kos --}}
                            @if($isFirstPayment)
                                <li>
                                    <p>Tanggal Pemesanan</p>
                                    <span>:
                                        {{\Carbon\Carbon::parse($detailPenyewa->tanggal_booking)->format('d M Y')}}</span>
                                </li>
                                <li>
                                    <p>Periode Penempatan</p>
                                    @php
                                        $bulan = DB::table('ms_tipe_kos')->where('id_tipe_kos', $detailPenyewa->tipe_kos)->value('bulan');
                                    @endphp
                                    <span>: {{ \Carbon\Carbon::parse($payment->periode_tagihan)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($payment->periode_tagihan)->subDay()->addMonth($bulan)->format('d M Y') }}</span>
                                </li>
                                <li>
                                    <p>Tanggal Jatuh Tempo</p><span>:
                                        {{ \Carbon\Carbon::parse($detailPenyewa->tanggal_booking)->addDays(2)->format('d M Y') }}</span>
                                </li>
                                <li>
                                    <p>Nama Lengkap</p>
                                    <span>: {{$detailPenyewa->nama}}</span>
                                </li>
                                <li>
                                    <p>No HP (WhatsApp)</p>
                                    <span>: {{$detailPenyewa->no_telepon}}</span>
                                </li>
                                <li>
                                    <p>Alamat Lengkap</p>
                                    <span>: {{$detailPenyewa->alamat}}</span>
                                </li>
                                <li>
                                    <p>Tipe Kos</p>
                                    <span>:
                                        {{ DB::table('ms_tipe_kos')->where('id_tipe_kos', $detailPenyewa->tipe_kos)->value('deskripsi') }}</span>
                                </li>
                            @else
                                <li>
                                    <p>Masa Penempatan</p>
                                    @php
                                        $bulan = DB::table('ms_tipe_kos')->where('id_tipe_kos', $detailPenyewa->tipe_kos)->value('bulan');
                                    @endphp
                                    <span>:
                                    {{ \Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->subDay()->addMonths($bulan)->format('d M Y') }}
                                    </span>

                                </li>
                                <li>
                                    <p>Tanggal Jatuh Tempo</p>
                                    <span>: {{ \Carbon\Carbon::parse($detailPenyewa->tanggal_jatuh_tempo)->subDay()->format('d M Y') }}</span>
                                </li>
                                <li>
                                    <p>Nama Lengkap</p>
                                    <span>: {{$detailPenyewa->nama}}</span>
                                </li>
                                <li>
                                    <p>Status</p>
                                    <span>: {{$detailPenyewa->status_penyewaan == 1 ? 'Aktif' : 'Tidak Aktif'}}</span>
                                </li>
                                <li>
                                    <p>Tipe Langganan</p>
                                    <span>: {{ DB::table('ms_tipe_kos')->where('id_tipe_kos', $detailPenyewa->tipe_kos)->value('deskripsi') }}</span>
                                </li>
                            @endif
                        </ul>
                        <button type="button" class="btn btn-secondary"
                                style="display: flex; margin-left: auto; margin-top: 20px;"
                                onclick="showPembayaran(this)" >{{($isFirstPayment ? 'Bayar' : 'Perpanjang')}}</button>

                        
                    </div>

                    <div class="order_details_iner">
                        <div class="order_header">
                            <h3>Riwayat Pembayaran</h3>
                        </div>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">No Kamar</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Tanggal Bayar</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $history = DB::table('payments')->where('id_penyewa', $payment->id_penyewa)->get();
                                //    dd($history);
                                   foreach($history as $h){
                                        $status = '';
                                        if($h->status_verifikasi == '1' && $h->metode_pembayaran != null){
                                            $status = "Lunas";
                                        }elseif($h->status_verifikasi == null && $h->metode_pembayaran == null){
                                            $status = "Belum Bayar";
                                        }elseif($h->status_verifikasi == '0' && $h->metode_pembayaran != null){
                                            $status = "Ditolak";
                                        }elseif($h->status_verifikasi == null && $h->metode_pembayaran != null){
                                            $status = "Menunggu Konfirmasi";
                                        }   

                                        $tipeKos = DB::table('penyewa')->where('id_penyewa', $h->id_penyewa)->value('tipe_kos');
                                        $bulan = DB::table('ms_tipe_kos')->where('id_tipe_kos', $tipeKos)->value('bulan');

                                        echo "
                                        <tr>
                                                <th><span>".$h->id_kamar."</span></th>
                                                <th>".\Carbon\Carbon::parse($h->periode_tagihan)->format('d M Y')." - ".\Carbon\Carbon::parse($h->periode_tagihan)->subDay()->addMonths($bulan)->format('d M Y')."</th>
                                                <th>".\Carbon\Carbon::parse($h->created_at)->format('d M Y')."</th>
                                                <th>".number_format($h->total_tagihan, 0, ',', '.') ."</th>
                                                <th>".$status."</th>
                                         </tr>
                                        ";
                                   }
                                ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4" id="pembayaran" style="display: none;">
                    <div class="order_box"  >
                        <h2>OPSI PEMBAYARAN</h2>
                        <form action="{{ route('tagihan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option5" name="metode_pembayaran" value="Tunai"
                                        required />
                                    <label for="f-option5">Pembayaran Tunai</label>
                                    <div class="check"></div>
                                </div>
                                <p>
                                    Datang secara langsung ke Kostiva untuk melakukan pembayaran secara tunai.
                                </p>
                            </div>
                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option6" name="metode_pembayaran" value="Transfer"
                                        required />
                                    <label for="f-option6">Pembayaran Non Tunai</label>
                                    <div class="check"></div>
                                </div>
                                <p>
                                    Lusiana <br>
                                    BCA 0130743048 <br>
                                    BRI 378701034178537 <br><br>
                                    <label for="bukti_tf">Upload Bukti Pembayaran</label>
                                    <input type="file" class="file-upload" id="bukti_tf" name="bukti_tf"
                                        accept=".jpg, .jpeg, .png" disabled />
                                </p>
                            </div>
                            <input type="hidden" name="id_penyewa" value="{{ $payment->id_penyewa }}">
                            <!-- <input type="hidden" name="id_kamar" value="{{ $detailPenyewa->id_kamar }}"> -->
                            <div class="password text-center">
                                <button class="btn_3" type="submit">BAYAR</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!--================ confirmation part end =================-->


{{-- @push('scripts') --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function showPembayaran(button) {
        if ($("#pembayaran").is(":visible")) {
            $("#pembayaran").hide();
            $(button).text("Perpanjang");
        } else {
            $("#pembayaran").show();
            $(button).text("Batal");
        }
    }


    $(document).ready(function () {

        $('input[type="radio"]').click(function () {
            if ($(this).attr('id') == 'f-option5') {
                $('#bukti_tf').attr('required', false);
                $('#bukti_tf').attr('disabled', true);
                $('#bukti_tf').value = '';
            } else {
                $('label[for="bukti_tf"]').show();
                $('#bukti_tf').show();
                $('#bukti_tf').attr('required', true);
                $('#bukti_tf').attr('disabled', false);
                // $('#bukti_tf').removeAttr('disabled');
            }
        });
    });

</script>

@if (session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
@if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif


<script>
    const navbar = document.querySelector('.main_menu');
    const navLinks = document.querySelectorAll('.nav-link, .navbar-brand, .navbar-toggler'); 

    window.addEventListener('scroll', () => {
        const bannerHeight = document.querySelector('.breadcrumb, .breadcrumb_bg').offsetHeight;

        if (window.scrollY > bannerHeight) {
            navbar.style.background = '#7cafc8'; 
            navLinks.forEach(link => {
                link.style.color = '#fff'; 
            });
        } else {
            navbar.style.background = 'transparent'; 
            navbar.style.boxShadow = 'none';
            navLinks.forEach(link => {
                link.style.color = '#e5e5d2'; 
            });
        }
    });

</script>
@endsection