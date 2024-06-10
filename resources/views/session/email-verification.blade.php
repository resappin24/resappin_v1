<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap">

</head>
<style>
    /* body {
        width: 620px;
        height: 1500px;
        background: url(bg-v2.png) repeat 0 0;
    } */

    .logo {
        width: 100px;
        height: 80px;
    }

    /* div {
        background-image: public_path('desain/img/bg-v2.png');
    } */

        .container {
            max-width: 600px;
            margin: 20px auto;
            /* background-color: #ffffff; */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         
        }

        h1 {
            color: #333333;
        }

        p {
            color: #666666;
            margin-bottom: 20px;
            font-size:12px;
        }

        .token {
            font-size: 30px;
            color: red;
            padding: 10px;
            text-align: center;
            letter-spacing: 9px;
        }

        .footer {
            margin-top: 30px;
            color: #666666;
        }
        
    .texture-bg {
            position: fixed;
            display:block;
            top: 0;
            left: 0;
            width: 300px;
            height: 300px;
            background-image: url('{{ asset('desain/img/bg-v2.png') }}');
            background-size: cover;
            background-position: center;
            z-index: -1;
            /* filter: blur(3px); */
            opacity: 0.8;
          
        }
</style>
<body>
    <div class="texture-bg"></div>
<div class="container">
    <!-- <img src="{{ asset('desain') }}/img/logo-v2.png" /> -->
    <img src="{{ $message->embed(public_path('desain/img/logo-v2.png')) }}" style="width: 200px; height:150px" alt="Logo" />
    <!-- <img src="{{asset('desain/img/logo-v2.png')}}" style="width: 50%;"> -->
    <h1>Welcome to RESAPPIN </h1>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Please click on the following link to verify your email:</p>

    <!-- <a href="{{ url('verification.verify', ['id' => $user->id, 'hash' => $user->verification_token]) }}">Verify Email</a> -->
    <a href="{{ url('verify-email/'.$user->email) }}">Verify Email : {{ $user->email }}</a>
    </div>
</body>
</html>
