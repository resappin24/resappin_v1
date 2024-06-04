<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css">
</head>
<style>
    body {
        width: 620px;
        height: 1500px;
        background: url(bg-v2.png) repeat 0 0;
    }

    .texture-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('desain/img/bg-2.jpg') }}');
            background-size: cover;
            background-position: center;
            z-index: -2;
            /* filter: blur(3px); */
            opacity: 0.8;
          
        }
</style>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Please click on the following link to verify your email:</p>

    <a href="{{ url('verification.verify', ['id' => $user->id, 'hash' => $user->verification_token]) }}">Verify Email</a>
</body>
</html>
