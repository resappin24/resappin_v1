<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    @if($errors->any())
        <script>
            console.log('Error')
            var errorMessage = @json($errors->all());
            var formattedErrorMessage = errorMessage.join(' & ');
            Swal.fire({
                icon: 'error',
                title: 'Register gagal',
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
            background-image: url('{{ asset('desain/img/bg-2.jpg') }}');
            background-size: cover;
            background-position: center;
            z-index: -2;
            /* filter: blur(3px); */
            opacity: 0.8;
          
        }
    </style>

<div class="texture-bg">
    </div>
    <div class="judul mt-5">
        <h1 style="text-align: center; ">REGISTER RESAPPIN</h1>
    </div>
    <div class="login-page">
        <!-- <div class="letakImg">
            <img src="{{ asset('desain') }}/img/1.jpg" class="kerupuk1" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/2.jpg" class="kerupuk2" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/3.jpg" class="kerupuk3" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/4.jpg" class="kerupuk4" alt="kerupuk">
        </div> -->
        <div class="form-reg">
            <form action="/register" method="post">
                @csrf
                <input type="text" name="name" placeholder="Name" />
                <input type="text" name="username" placeholder="Username" />
                <input type="text" name="email" placeholder="Email" />
                <input type="password" name="password" placeholder="Password" />
                <button>Register</button>
            </form>
            <div class="regis">
                <p class="regis-text"><a href="/">Login</a>, Jika sudah memiliki account</p>
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
