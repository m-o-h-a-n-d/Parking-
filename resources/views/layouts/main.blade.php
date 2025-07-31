<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>parking system</title>
    <link rel="icon" href="{{asset('img/image.png')}}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Right navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>


            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
                        <img src="{{ asset('img/avatar5.png') }}" class='img-circle elevation-2' width="40"
                            height="40" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ Auth::user()->name }}
                        </a>



                        <div class="dropdown-divider"></div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        <a class="dropdown-item text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>



                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"> Parking</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link">
                                <i class="nav-icon  fas fa-users"></i>
                                <p>Customers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cars.index') }}" class="nav-link">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Cars</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('slots.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>


                                <p>Slots</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subscriptions.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-dollar-sign"></i>

                                <p>subscriptions</p>
                            </a>
                        </li>


                    </ul>
                </div>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        @yield('sec1')








        <footer class="main-footer">

            <strong>Copyright &copy;2025 Mohaned Ahmed
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // أو top-left لو حبيت
        };



        // زرار الحذف
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "you can't be undone",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // زرار التعديل (اختياري لو حابب تظهر نفس الفكرة للتعديل)
        $('.btn-edit').click(function(e) {
            e.preventDefault();
            let link = $(this).attr('href');

            Swal.fire({
                title: 'هل تريد تعديل هذا العنصر؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، عدل',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        });

        // عرض رسائل Toastr من Laravel Session
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>



    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('js/demo.js') }}"></script>

    @stack('js')
</body>

</html>
