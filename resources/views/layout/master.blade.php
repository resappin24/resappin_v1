<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
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

        .bg-demo{
            background-image: url('{{ asset('desain/img/demo_opa.png') }}');
            background-position: center;
        }

        .nav-top {
            height: 50px;
            position: relative;
            background-color: #87CEFA;
        }


       .strong { /* css dropdown top navbar */
        position: fixed;
            top: -5px;
            left: 1030;
            z-index: 5;
        }

        .dropdown-submenu {
            position: relative;
            /* background-color: lightblue; */
        }

         .dropdown-submenu .dropdown-menu {
            position: relative;
            top: 1;
            left: 20%;
            color: white;
            width: 80%;
            border: 0;
         
            background-color: #54a1d8;
        }

        .icon_dropdown {
            margin-left: 120px;
        }

</style>

    <div class="side-navbar d-flex justify-content-between flex-wrap flex-column bg-demo" style="z-index: 2" id="sidebar">
        <ul class="nav flex-column text-white w-100">
            <center>
                <img src="{{ asset('desain/img/logo-v1-horizontal.png') }}" alt="logo" width="200px" height="80px" style="margin-top:20px; margin-bottom:20px; " >
            </center>
              <!-- <a href="{{ url('/user') }}"  
                class="nav-link text-white {{ request()->routeIs('/user') ? 'active' : '' }}"> -->
                <!-- <li class="d-flex flex-row align-items-center justify-content-center mb-3">
                    <iconify-icon icon="ion:person-circle" width="20px"></iconify-icon>
                    <span class="mx-2">
                        <strong>
                            Welcome {{ Auth::user()->name }}!
                        </strong>
                    </span>
                </li> -->
            <!-- </a> -->
         
            {{-- {{ Auth::user()->name }} --}}
           
            <a href="{{ url('/dashboard') }}"
                class="nav-link text-white {{ request()->routeIs('/dashboard') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ion:home"></iconify-icon>
                   <span class="mx-2">Home</span>
                </li>
            </a>
            <li class="dropdown-submenu">
                <a class="test nav-link text-white" href="#">  <iconify-icon icon="fluent:list-bar-20-filled"></iconify-icon><span class="mx-2">Master<i class="fa fa-caret-down icon_dropdown"></i></span> </a>
                <ul class="dropdown-menu">
                    <a href="{{ url('/master_product') }}" class="nav-link text-white">  <li> Master Product</li></a>
                    <a href="{{ url('/vendor') }}" class="nav-link text-white">  <li> Master Supplier/Vendor</li></a>
                    <a href="{{ url('/kategori') }}" class="nav-link text-white">  <li> Master Category</li></a>
                </ul>
              </li>
            <a href="{{ url('/transaksi') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <iconify-icon icon="ep:sell"></iconify-icon>
                    <span class="mx-2">Transaksi</span>
                </li>
            </a>
            <a href="{{ url('/prod_category') }}"
                class="nav-link text-white {{ request()->routeIs('/activity') ? 'active' : '' }}">
                <li>
                    <!-- <iconify-icon icon="ep:sell"></iconify-icon> -->
                    <iconify-icon icon="healthicons:stock-out"></iconify-icon>
                    <span class="mx-2">Product Category</span>
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
        <nav class="top-navbar navbar-light nav-top">
            <a class="btn border-0" id="menu-btn"><i class="bx bx-menu"></i></a>
            <span class="mx-2">
            <ul class="nav flex-column text-white w-10 strong">
                <li class="dropdown-submenu">
                <a class="test nav-link text-white" href="#"> <iconify-icon icon="ion:person-circle" width="30px" class="mt-2"></iconify-icon>
                        <strong>
                            Welcome {{ Auth::user()->name }}!
                        </strong>
                        </a>
                        <ul class="dropdown-menu">
                        <a href="{{ url('/master_product') }}" class="nav-link text-white">  <li>Profile</li></a>
                        <a href="{{ url('/vendor') }}" class="nav-link text-white">  <li> Logout </li></a>
                        </ul>
        </li>
        </ul>
                    </span>
     
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
      
    </div>
             <div>  @yield('konten')
                </div>
    <script>
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
</script>

</body>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('desain') }}/js/script.js"></script>
</html>
