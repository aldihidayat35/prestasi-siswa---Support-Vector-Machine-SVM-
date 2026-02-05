<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Prediksi Prestasi Akademik')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #3699FF;
            --secondary-color: #E4E6EF;
            --success-color: #1BC5BD;
            --danger-color: #F64E60;
            --warning-color: #FFA800;
            --info-color: #8950FC;
            --dark-color: #181C32;
            --light-color: #F3F6F9;
            --sidebar-width: 265px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e1e2d 0%, #1a1a27 100%);
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h4 {
            color: #fff;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            padding: 0 25px;
            margin-bottom: 10px;
        }

        .menu-section-title {
            color: #565674;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .menu-item {
            margin-bottom: 5px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: #9899ac;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 0.5rem;
            margin: 0 15px;
        }

        .menu-link:hover,
        .menu-link.active {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        .menu-link.active {
            background: var(--primary-color);
        }

        .menu-link i {
            font-size: 1.25rem;
            margin-right: 12px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid var(--secondary-color);
        }

        .header-left h5 {
            margin: 0;
            color: var(--dark-color);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--secondary-color);
            padding: 1.5rem;
        }

        .card-title {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Stats Cards */
        .stat-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .stat-icon.primary {
            background: rgba(54, 153, 255, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(27, 197, 189, 0.1);
            color: var(--success-color);
        }

        .stat-icon.warning {
            background: rgba(255, 168, 0, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.danger {
            background: rgba(246, 78, 96, 0.1);
            color: var(--danger-color);
        }

        .stat-info h3 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-info p {
            margin: 0;
            color: #7e8299;
            font-size: 0.875rem;
        }

        /* Badges */
        .badge-rendah { background: var(--danger-color); }
        .badge-sedang { background: var(--warning-color); }
        .badge-tinggi { background: var(--success-color); }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #187de4;
            border-color: #187de4;
        }

        /* Form Controls */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(54, 153, 255, 0.25);
        }

        /* Tables */
        .table th {
            font-weight: 600;
            color: var(--dark-color);
            border-bottom-width: 1px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill me-2"></i>SVM Predict</h4>
        </div>

        <nav class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                <!-- Admin Menu -->
                <div class="menu-section">
                    <div class="menu-section-title">Dashboard</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Manajemen Data</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Data User</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.students.index') }}" class="menu-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Data Siswa</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.learning-activities.index') }}" class="menu-link {{ request()->routeIs('admin.learning-activities.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i>
                        <span>Aktivitas Belajar</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.academic-scores.index') }}" class="menu-link {{ request()->routeIs('admin.academic-scores.*') ? 'active' : '' }}">
                        <i class="bi bi-award"></i>
                        <span>Nilai Akademik</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Machine Learning</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.datasets.index') }}" class="menu-link {{ request()->routeIs('admin.datasets.*') ? 'active' : '' }}">
                        <i class="bi bi-database"></i>
                        <span>Dataset</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.ml-models.index') }}" class="menu-link {{ request()->routeIs('admin.ml-models.*') ? 'active' : '' }}">
                        <i class="bi bi-cpu"></i>
                        <span>Model SVM</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.predictions.index') }}" class="menu-link {{ request()->routeIs('admin.predictions.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Prediksi</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Dokumentasi</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.svm-explanation') }}" class="menu-link {{ request()->routeIs('admin.svm-explanation') ? 'active' : '' }}">
                        <i class="bi bi-book"></i>
                        <span>Penjelasan SVM</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.tutorial') }}" class="menu-link {{ request()->routeIs('admin.tutorial') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard"></i>
                        <span>Tutorial Aplikasi</span>
                        <span class="badge bg-success ms-auto">Baru</span>
                    </a>
                </div>
            @else
                <!-- Guru Menu -->
                <div class="menu-section">
                    <div class="menu-section-title">Dashboard</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('guru.dashboard') }}" class="menu-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Data Siswa</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('guru.learning-activities.index') }}" class="menu-link {{ request()->routeIs('guru.learning-activities.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i>
                        <span>Input Aktivitas</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('guru.predictions.index') }}" class="menu-link {{ request()->routeIs('guru.predictions.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Hasil Prediksi</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Bantuan</div>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.tutorial') }}" class="menu-link {{ request()->routeIs('admin.tutorial') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard"></i>
                        <span>Tutorial</span>
                    </a>
                </div>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <h5>@yield('header-title', 'Dashboard')</h5>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ ucfirst(auth()->user()->role->name) }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    @stack('scripts')
</body>
</html>
