<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body>
    <h1>Halo, {{ $user->nama_lengkap }}</h1>
    <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
    <p>Klik link berikut untuk reset password:</p>
    <a href="{{ $url_setpassword }}">Reset Password</a>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
    <p>Hormat kami,</p>
    <p>FORTUNA KOST</p>
</body>
</html>
