<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">
    <script src="https://unpkg.com/@adminkit/core@latest/dist/js/app.js"></script>
    <link rel="stylesheet" href="{{ asset('desain') }}/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<style>
        .texture-bg2 {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('desain/img/bg-v1.png') }}');
            background-size: cover;
            background-position: center;
            /* z-index: 0; */
            /* background-color:blue; */
            /* filter: blur(3px); */
            opacity: 0.99;
        }

        .nav-top {
            height: 50px;
            background-color: #87CEFA;
        }
</style>

    <div class="side-navbar d-flex justify-content-between flex-wrap flex-column" style="z-index: 2" id="sidebar">
        <ul class="nav flex-column text-white w-100">
            <center>
                <img src="{{ asset('desain/img/logo-v1-horizontal.png') }}" alt="logo" width="200px" height="80px" style="margin-top:20px; margin-bottom:20px; " >
            </center>
              <!-- <a href="{{ url('/user') }}"  
                class="nav-link text-white {{ request()->routeIs('/user') ? 'active' : '' }}"> -->
                <li class="d-flex flex-row align-items-center justify-content-center mb-3">
                    <iconify-icon icon="ion:person-circle" width="20px"></iconify-icon>
                    <span class="mx-2">
                        <strong>
                            Welcome {{ Auth::user()->name }}!
                        </strong>
                    </span>
                </li>
            <!-- </a> -->
         
            {{-- {{ Auth::user()->name }} --}}
           
            <a href="{{ url('/dashboard') }}"
                class="nav-link text-white {{ request()->routeIs('/dashboard') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ion:home"></iconify-icon>
                    <span class="mx-2">Home</span>
                </li>
            </a>
            <a href="{{ url('/master_barang') }}" class="nav-link text-white">
                <li>
                    <iconify-icon icon="fluent:list-bar-20-filled"></iconify-icon>
                    <span class="mx-2">Master Barang</span>
                </li>
            </a>
            <a href="{{ url('/transaksi') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ep:sell"></iconify-icon>
                    <span class="mx-2">Transaksi</span>
                </li>
            </a>
            <a href="{{ url('/vendor') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <!-- <iconify-icon icon="ep:sell"></iconify-icon> -->
                    <iconify-icon icon="healthicons:stock-out"></iconify-icon>
                    <span class="mx-2">Master Vendor</span>
                </li>
            </a>
            <a href="{{ url('/activity') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="material-symbols:history"></iconify-icon>
                    <span class="mx-2">Log / Activity</span>
                </li>
            </a>
            <a href="{{ url('logout') }}" class="nav-link text-white">
                <li>
                    <iconify-icon icon="tdesign:logout"></iconify-icon>
                    <span class="mx-2">Logout</span>
                </li>
            </a>
        </ul>
    </div>
    <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" style="width:auto;" id="sidebarLogo">
        <ul class="nav flex-column text-white w-100 mt-3">
            <a class="nav-link text-white" id="menu-btn2"><iconify-icon icon="ion:menu" width="20px"></iconify-icon></a>
            <img src="{{ asset('desain/img/logo-2-effect.png') }}" alt="logo" width="130px" class="mb-3">
          
            <a href="{{ url('/dashboard') }}"
                class="nav-link text-white {{ request()->routeIs('/dashboard') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ion:home" width="20px"></iconify-icon>
                </li>
            </a>
            <a href="{{ url('/master_barang') }}" class="nav-link text-white">
                <li>
                    <iconify-icon icon="fluent:list-bar-20-filled" width="20px"></iconify-icon>
                </li>
            </a>
            <a href="{{ url('/transaksi') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ep:sell" width="20px"></iconify-icon>
                </li>
            </a>
            <a href="{{ url('/vendor') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                <iconify-icon icon="healthicons:stock-out"  width="20px"></iconify-icon>
                    <!-- <iconify-icon icon="ep:sell" width="20px"></iconify-icon> -->
                </li>
            </a>
            <a href="{{ url('/activity') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="material-symbols:history" width="20px"></iconify-icon>
                </li>
            </a>
            <a href="{{ url('logout') }}" class="nav-link text-white">
                <li>
                    <iconify-icon icon="tdesign:logout" width="20px"></iconify-icon>
                </li>
            </a>
        </ul>
    </div>
    <div class="p-1 my-container">
        <div class="texture-bg2">
            <!-- <img src="{{url(asset('desain/img/bg-2.jpg'))}}" height="800px" style="z-index: 0"> -->
        <div>
        <nav class="navbar top-navbar navbar-light px-5 nav-top">
            <a class="btn border-0" id="menu-btn"><i class="bx bx-menu"></i></a>
        </nav>
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
        @yield('konten')
      
    </div>
             
</body>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('desain') }}/js/script.js"></script>
</html>
