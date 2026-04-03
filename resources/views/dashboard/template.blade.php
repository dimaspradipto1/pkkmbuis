<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PKKMB UIS - Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/logo_ibsi.png" rel="icon">
    <link href="assets/img/logo_ibsi.png" rel="uis">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    {{--  datatables CSS  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">

    <style>
        :root {
            --uis-green: #00A551;
            --uis-green-dark: #087C39;
            --uis-yellow: #FFF742;
            --bg-light: #f6f9ff;
            --card-edge: rgba(0, 165, 81, 0.1);
        }

        body {
            background-color: var(--bg-light) !important;
            color: #444444;
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar: Professional Light Style */
        .sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0;
            box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
        }

        .sidebar-nav .nav-link {
            background: transparent !important;
            color: #012970 !important;
            font-weight: 600;
            border-radius: 8px;
            margin: 5px 15px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .sidebar-nav .nav-link i {
            color: var(--uis-green) !important;
            font-size: 1.1rem;
        }

        .sidebar-nav .nav-link:hover {
            background: #f6f9ff !important;
            color: var(--uis-green-dark) !important;
        }

        .sidebar-nav .nav-link:not(.collapsed) {
            background: #f6f9ff !important;
            color: var(--uis-green) !important;
            border-left: 4px solid var(--uis-green);
        }

        /* Header: Clean UIS Style */
        .header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
        }

        .header .logo span {
            color: #012970 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header .toggle-sidebar-btn {
            color: var(--uis-green) !important;
        }

        /* Clean White Cards */
        .card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1) !important;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: var(--uis-green) !important;
        }

        .card-title {
            color: #012970 !important;
            font-weight: 600 !important;
            font-size: 1.1rem !important;
            padding: 20px 0 15px 0;
        }

        .card-title span { color: #899bbd !important; font-weight: 400; font-size: 0.9rem; }

        /* Stats Section */
        .info-card h6 {
            color: #012970 !important;
            font-weight: 700 !important;
            font-size: 1.8rem !important;
        }

        .card-icon {
            width: 64px; height: 64px;
            border-radius: 50% !important;
            background: #f6f9ff !important;
        }

        .sales-card .card-icon { color: var(--uis-green) !important; }
        .revenue-card .card-icon { color: #f9b115 !important; }

        /* Professional Tables */
        .table {
            color: #444444 !important;
        }

        .table thead th {
            background: #f6f9ff !important;
            color: #012970 !important;
            border-bottom: 2px solid #e2e8f0 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            border-bottom: 1px solid #ebeef4 !important;
            vertical-align: middle;
            padding: 12px;
        }

        /* Badge Branding */
        .badge {
            font-weight: 500;
            padding: 5px 12px;
        }

        .bg-success { background-color: var(--uis-green) !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f6f9ff; }
        ::-webkit-scrollbar-thumb { background: #cfd4da; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--uis-green); }
    </style>




</head>

<body>

    @include('dashboard.header')
    @include('dashboard.sidebar')

    <main id="main" class="main">
        @include('sweetalert::alert')
        @yield('content')
    </main><!-- End #main -->

    @include('dashboard.footer')



    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- datatables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://kit.fontawesome.com/63b8672806.js" crossorigin="anonymous"></script>

    @stack('scripts')

</body>

</html>
