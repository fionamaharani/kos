<!doctype html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/luck.png') }}" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
    <!-- animate CSS -->
    <link rel="stylesheet" href={{ asset('css/animate.css') }}>
    <!-- style CSS -->
    <link rel="stylesheet" href={{ asset('css/style.css') }}>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e3f6ff; 
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">                        
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="btn btn-link d-md-none rounded-circle mr-3">
                            <span class="menu_icon"><i class="fa fa-bars"></i></span>
                        </button>
                        <a class="navbar-brand" href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/luck.png') }}" style="width: 35px; margin-right: 20px;"> Kostiva
                        </a>
                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dashboard') }}">Beranda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="facility-link" href="#">Fasilitas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="https://wa.me/6281354190343?text=Halo,%20ingin%20tanya%20seputar%20kos">Kontak</a>
                                </li>
                                <?php
                                    $user = Auth::user();
                                    if ($user) {
                                        echo '
                                            <li class="nav-item">
                                                <a class="nav-link" href="' . route('tagihan') . '">Tagihan</a>
                                            </li>
                                        ';
                                    } else {
                                        echo '
                                            <li class="nav-item">
                                                <a class="nav-link" href="' . route('booking') . '">Pesan Kamar</a>
                                            </li>
                                        ';
                                    }
                                ?>
                            </ul>
                            <div class="d-flex">
                                <div class="nav-item">
                                    <?php
                                        $user = Auth::user();
                                        if ($user) {
                                            echo '
                                                <a class="nav-link" href="' . route('logout') . '">LOGOUT</a>
                                            ';
                                        } else {
                                            echo '
                                                <a class="nav-link" href="' . route('login') . '">LOGIN</a>
                                            ';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <main>
         @yield('content') <!-- The content of individual pages will be injected here -->
    </main>
    
    <!--::footer_part start::-->
    <footer class="footer_part mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Bagian Peta -->
                <div class="col-md-6">
                    <div class="single_footer_part">
                        <h4>Lokasi Kami</h4>
                        <div class="maps_container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1148.3001655459595!2d110.49470678989275!3d-7.34160484265857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a79c376ec6c21%3A0x6b978b8638843e71!2sMbak%20Lusi%201!5e0!3m2!1sen!2sid!4v1733029132564!5m2!1sen!2sid" 
                                width="100%" height="100" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
                <!-- Bagian Info -->
                <div class="col-md-4 info_section">
                    <div class="single_footer_part">
                        <h4>Alamat Lengkap</h4>
                        <p>Indonesia</p>
                    </div>
                    <div class="single_footer_part">
                        <h4>Jam Operasional</h4>
                        <p>Senin - Minggu: 08.00-18.00</p>
                    </div>
                    <div class="single_footer_part">
                        <h4>Kontak</h4>
                        <p>WhatsApp: +6281354190343</p>
                    </div>
                    <div class="single_footer_part">
                        <h4>Social</h4>
                        <div class="footer_icon social_icon">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright -->
        <div class="copyright_part">
            <div class="container">
                <p class="text-center">© 2024 Kostiva. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    
    <!--::footer_part end::-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const navbar = document.querySelector('.main_menu');
        const navLinks = document.querySelectorAll('.nav-link, .navbar-brand, .navbar-toggler'); 

        window.addEventListener('scroll', () => {
            const bannerHeight = document.querySelector('.banner_part').offsetHeight;

            if (window.scrollY > bannerHeight) {
                navbar.style.background = '#7cafc8';
                navLinks.forEach(link => {
                    link.style.color = '#fff';
                });
            } else {
                navbar.style.background = 'transparent'; 
                navbar.style.boxShadow = 'none';
                navLinks.forEach(link => {
                    link.style.color = '#000'; 
                });
            }
        });

        @yield('scripts')
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const facilityLink = document.getElementById("facility-link");

            facilityLink.addEventListener("click", function(e) {
                e.preventDefault();

                // Cek apakah user sedang berada di dashboard
                const isDashboard = window.location.pathname === "{{ route('dashboard', [], false) }}";

                if (isDashboard) {
                    // Scroll ke bagian #facility di halaman saat ini
                    const facilitySection = document.querySelector("#facility");
                    if (facilitySection) {
                        facilitySection.scrollIntoView({ behavior: "smooth" });
                    }
                } else {
                    // Redirect ke dashboard dengan anchor ke #facility
                    window.location.href = "{{ route('dashboard') }}#facility";
                }
            });
        });
    </script>

</body>

</html>