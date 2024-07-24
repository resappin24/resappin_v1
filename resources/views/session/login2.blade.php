<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css"> -->
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

    <style>
        .texture-bg {
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

        .logo{
            position: fixed;
            /* margin-top: 10px; */
            margin-left: 10px;
            /* max-width: 220px;
            max-height: 170px; */
            width: 200px;
            height: 150px;
            /* background-image: url('{{ asset('desain/img/admin-login.png') }}'); */
            background-image: url('{{ asset('desain/img/resappin-logo-trans.png') }}');
            background-size: contain;
            /* background-position: center; */
            /* filter: blur(3px); */
            /* z-index:2; */
            /* display: block; */
            /* margin-left: auto;
            margin-right: auto; */
          
        }

        h2 {
            margin-top: 10px;
            margin-bottom: 40px;
            margin-left:-250px;
            color : black;
            text-align: left;
            font-family: 'boldJosefin', sans-serif;
            border-bottom: 3px solid #000000;
        }

        .form-login {
            margin-top: 80px;
            margin-left: -100px;
            width: 200px;
            /* position: relative;
            z-index: 9999; */
            /* background: #FFFFFF; */
            font-size: 12pt;
        }

        .form {
            position: relative;
            z-index: 3;
            /* background: #FFFFFF; */
            width: 360px;
            max-height: 200px;
            /* margin: 200 auto 100px; */
            padding: 25px;
            margin-left: -55px;
            /* margin-top: 80px; */
            font-family: 'regJosefin', sans-serif;
            margin-bottom: 10px;
         
            /* box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24); */
        }
        /*body{
            font-family: 'CustomFont', sans-serif;
        }*/

    </style>
    <div class="texture-bg" >
    </div>
    
    <!-- <div class="judul mt-5">
        <h1 style="text-align: center; ">Admin Dashboard</h1>
    </div>
    <div class="subjudul mt-3 mb-4">
        <h2 style="text-align: center; ">Login</h2>
    </div> -->
<div class="container-login">
  <div class="form-container">
    <div>
        <div class="logo">
             </div> 
    </div>
   
   <h2>LOGIN</h2> 
    
            <div class="form-login">
            <div class="form">
                <form action="/" method="post">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @csrf
                    <input type="text" name="username" placeholder="Username" class="mb-2" />
                    <input type="password" name="password" placeholder="Password" class="mb-2" />
                    <button class="mb-2">login</button>
                </form>
                <div>
                    <p class="forgot-text"><a class="forgot-text" href="/forgot-password">Forgot Password?</a></p>
                </div>
                <div class="regis">
                    <p class="regis-text"><a href="/register">Register</a>, Jika belum memiliki account</p>
                </div>
            </div> <!--end class form-login-->
            </div>
            </div>
  
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>

</html>
