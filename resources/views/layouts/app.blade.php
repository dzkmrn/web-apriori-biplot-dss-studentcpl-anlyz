<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Analisis CPL - Telkom University')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-green: #38A169;
            --primary-green-dark: #2F855A;
            --primary-green-light: #68D391;
            --telkom-gray: #718096;
            --telkom-gray-dark: #4A5568;
            --telkom-gray-light: #EDF2F7;
            --telkom-blue: #3182CE;
            --telkom-success: #38A169;
            --telkom-warning: #D69E2E;
            --sidebar-width: 250px;
        }

        body {
            background-color: #F7FAFC;
            font-family: 'Inter', sans-serif;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 45px;
            height: px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo-text {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary-green);
            letter-spacing: -1px;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        
        .university-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            line-height: 1.2;
        }
        
        .system-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin: 0;
            line-height: 1.2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-weight: 600;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--telkom-gray-dark) 0%, #2D3748 100%);
            min-height: calc(100vh - 80px);
            padding: 0;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1), 2px 0 4px -1px rgba(0, 0, 0, 0.06);
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 2px 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .sidebar-menu i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            background: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(229, 62, 62, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-green-dark), #22543D);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(229, 62, 62, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--telkom-success), #2F855A);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--telkom-warning), #B7791F);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-info {
            background: linear-gradient(135deg, var(--telkom-blue), #2C5282);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background: var(--telkom-gray-light);
            border-top: none;
            font-weight: 600;
            color: var(--telkom-gray-dark);
        }
        
        .table tbody tr:hover {
            background-color: rgba(229, 62, 62, 0.05);
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
        }

        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            color: white;
            border-radius: 8px 8px 0 0;
        }

        /* Utility classes */
        .text-primary {
            color: var(--primary-green) !important;
        }

        .bg-primary {
            background-color: var(--primary-green) !important;
        }
        
        /* Badge styles */
        .badge {
            border-radius: 6px;
            font-weight: 500;
        }
        
        .bg-success {
            background-color: var(--telkom-success) !important;
        }
        
        .bg-warning {
            background-color: var(--telkom-warning) !important;
        }
        
        .bg-danger {
            background-color: var(--primary-green) !important;
        }
        
        .bg-info {
            background-color: var(--telkom-blue) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
            }
            
            .main-content {
                margin: 10px;
                padding: 15px;
            }
            
            .logo-container {
                gap: 8px;
            }
            
            .logo-container img {
                height: 35px;
            }
            
            .brand-text .university-text {
                font-size: 0.75rem;
            }
            
            .brand-text .system-title {
                font-size: 1rem;
            }
        }
        
        /* Table Responsive Improvements */
        .table-responsive {
            border-radius: 8px;
        }
        
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table th {
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Improve button groups in tables */
        .btn-group-vertical .btn {
            margin-bottom: 2px;
        }
        
        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }
        
        /* Badge improvements */
        .badge {
            font-size: 0.75rem;
            line-height: 1.2;
        }
        
        /* Small screen table adjustments */
        @media (max-width: 992px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn-sm {
                font-size: 0.7rem;
                padding: 0.25rem 0.4rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <nav class="main-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo-container">
                    <div class="logo-icon">
                    <img style="width: 150px; height: 40px;" src="{{ asset('images/sistem_analis_cpl.png') }}" alt="Logo" class="img-fluid">
                </div>
                    <div class="brand-text">
                        <div class="university-text">Sistem</div>
                        <div class="system-title">Analisis CPL</div>
                    </div>
                </div>
                <div class="user-info">
                    <span>Hello {{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    @auth
                    <div class="dropdown">
                        <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('data-mahasiswa.index') }}" class="{{ request()->routeIs('data-mahasiswa*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Data Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('cpl.index') }}" class="{{ request()->routeIs('cpl*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        Data CPL
                    </a>
                </li>
                <li>
                    <a href="{{ route('profil-lulusan.index') }}" class="{{ request()->routeIs('profil-lulusan*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i>
                        Profil Lulusan
                    </a>
                </li>
                <li>
                    <a href="{{ route('analisis.index') }}" class="{{ request()->routeIs('analisis*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Analisis Hasil
                    </a>
                </li>
                <li>
                    <a href="{{ route('reset-data.index') }}" class="{{ request()->routeIs('reset-data*') ? 'active' : '' }}">
                        <i class="fas fa-trash-restore"></i>
                        Reset Data
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('scripts')
</body>
</html> 