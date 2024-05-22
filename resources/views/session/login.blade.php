<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css">
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
    <div class="bg">
        <img src="https://www.rumahmesin.com/wp-content/uploads/2020/12/seblakkering.jpg?x44724" alt="" style="width:180%; height: 150%">
    </div>
    <div class="judul">
        <h1 style="text-align: center; ">Login Apps Penjualan Kerupuk</h1>
    </div>
    <div class="login-page">
        <div class="letakImg">
            <img src="{{ asset('desain') }}/img/1.jpg" class="kerupuk1" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/2.jpg" class="kerupuk2" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/3.jpg" class="kerupuk3" alt="kerupuk">
            <img src="{{ asset('desain') }}/img/4.jpg" class="kerupuk4" alt="kerupuk">
        </div>
        <div class="form mt-3">
            <form action="/" method="post" class="login-form">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @csrf
                <input type="text" name="username" placeholder="Username" />
                <input type="password" name="password" placeholder="Password" />
                <button>login</button>
            </form>
            <div class="regis">
                <p class="regis-text"><a href="/register">Register</a>, Jika belum memiliki account</p>
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
