<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Please click on the following link to verify your email:</p>
    <a href="{{ url('verification.verify', ['id' => $user->id, 'hash' => $user->verification_token]) }}">Verify Email</a>
</body>
</html>
