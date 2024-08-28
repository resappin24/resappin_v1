<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('desain') }}/css/login.css">
</head>

<body>
    @if (Session::has('success'))
        <script>
            console.log('Success')
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ Session::get('success') }}',
                showConfirmButton: false,
            });
        </script>
    @elseif($errors->any())
        <script>
            console.log('Error')

            var errorMessage = @json($errors->all());
            var formattedErrorMessage = errorMessage.join(' & ');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: formattedErrorMessage,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <style>
        .texture-bg-v2 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('desain/img/bg-v2.png') }}');
            background-size: cover;
            background-position: center;
            z-index: -2;
            /* filter: blur(3px); */
            opacity: 0.8;
          
        }

        .texture-bg-v1 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('desain/img/bg-v1.png') }}');
            background-size: cover;
            background-position: center;
            z-index: -2;
            /* filter: blur(3px); */
            opacity: 0.99;
          
        }

        .title{
            position: fixed;
            margin-top: 20px;
            width: 350px;
            height: 350px;
            background-image: url('{{ asset('desain/img/admin-login.png') }}');
            background-size: cover;
            background-position: center;
            /* filter: blur(3px); */
            
        }

        .title2 {
            margin-top: -30px;
            margin bottom : 50px;
            font-size: 15px;
            font-family: 'boldJosefin', sans-serif;
        }

        .img-url {
            /* content:url('{{ asset('desain/img/resappin-logo-trans.png') }}'); */
            content : url('{{ asset('desain/img/logo-v1.png') }}');
            height: 130px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
            width: 180px;
            margin-bottom: 30px;
            display: block;
        }
        .center {
            text-align: center;
            font-family: 'boldJosefin', sans-serif;
        }

        a {
            color: blue;
        }

        .form-forgot {
           margin-left: 60px;
        }

        .btn {
            color: #FFFFFF;

        }

    </style>
    <div class="texture-bg-v1">
    </div>

    <div class="container-verify">
  <div class="form-container-forgot">
    <div>
        <div class="logo">
             </div> 
             @if(session('failed'))
             <div>
                 <h3 class="title2">
              Sorry, your email has not been regitered yet.
                </h3>
                <p>Please re-check your email. Thankyou. </p>
    </div>
            @else
            <div>
                <h4 class="title2">
                    We have sent reset password link to your email. <br/>
                    Please check your email. Thankyou.
                </h4>
            </div>
            @endif

    </div>

    <div class="center mt-5"><a href="{{ url('/') }}">>> Back to Login Page</a></div>
    
    </div>
 
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>

</html>
