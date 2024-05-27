<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css">
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Please click on the following link to verify your email:</p>
    <p>Your Token : </p>
    <p><h3> {{ $user->verification_token }} </h3> </p>
    <a href="{{ url('verification.verify', ['id' => $user->id, 'hash' => $user->verification_token]) }}">Verify Email</a>
</body>
</html>
