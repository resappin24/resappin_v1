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
            opacity: 0.8;
          
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
            margin-top: 0px;
            margin-bottom: 20px;
            width: 500px;
            font-family: 'boldJosefin', sans-serif;
            margin-left: 150px;
        }

        .img-url {
            /* content:url('{{ asset('desain/img/resappin-logo-trans.png') }}'); */
            content : url('{{ asset('desain/img/logo-v1.png') }}');
            height: 130px;
            margin-top: 0px;
            margin-left: auto;
            margin-right: auto;
            width: 180px;
            margin-bottom: 30px;
            display: block;
            
        }
        .center {
            text-align: center;
        }

        a {
            color: blue;
        }

    </style>
    <div class="texture-bg">
    </div>
    <div class="container-verify">
    <div class="form-container-verify">
      <div>
        <h3 class="title2">Email Verification Success ! </h3>
      
    </div>
    <div><img class ="img-url"></div>
    <div class="center">Directing you to Dashboard in 5 4 3 2 1...</div>
    <!-- <div class="center"><a href="{{ url('/dashboard') }}">>> Go to Dashboard</a></div> -->
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
