<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Sistem Manajemen Surat Sekolah</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3490dc;
            --secondary-color: #38c172;
            --accent-color: #f6993f;
            --text-color: #2d3748;
            --bg-color: #f8fafc;
            --danger-color: #e3342f;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        
        .sidebar {
            background-color: #2c3e50;
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .logo img {
            max-width: 100px;
            height: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .submenu {
            padding-left: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar .dropdown-toggle::after {
            display: none;
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-warning {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .table th {
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .badge-primary {
            background-color: var(--primary-color);
        }
        
        .badge-success {
            background-color: var(--secondary-color);
        }
        
        .badge-warning {
            background-color: var(--accent-color);
        }
        
        .badge-danger {
            background-color: var(--danger-color);
        }
        
        .footer {
            background-color: white;
            padding: 15px 0;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('storage/pengaturan/logo.png') }}" alt="Logo Sekolah" onerror="this.src='{{ asset('images/default-logo.png') }}'">
            <h5 class="mt-2">Sistem Manajemen Surat</h5>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('surat-masuk.index') }}" class="nav-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope-open-text"></i> Surat Masuk
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('surat-keluar.index') }}" class="nav-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane"></i> Surat Keluar
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('disposisi.index') }}" class="nav-link {{ request()->routeIs('disposisi.*') ? 'active' : '' }}">
                    <i class="fas fa-share"></i> Disposisi
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('klasifikasi.index') }}" class="nav-link {{ request()->routeIs('klasifikasi.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Klasifikasi
                </a>
            </li>
            
            @if(Auth::user()->role == 'admin')
            <li class="nav-item">
                <a href="#pengaturanSubmenu" data-bs-toggle="collapse" class="nav-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="collapse {{ request()->routeIs('pengaturan.*') ? 'show' : '' }} list-unstyled submenu" id="pengaturanSubmenu">
                    <li>
                        <a href="{{ route('pengaturan.index') }}" class="nav-link {{ request()->routeIs('pengaturan.index') ? 'active' : '' }}">
                            <i class="fas fa-sliders-h"></i> Umum
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengaturan.users') }}" class="nav-link {{ request()->routeIs('pengaturan.users') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Pengguna
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengaturan.activity-log') }}" class="nav-link {{ request()->routeIs('pengaturan.activity-log') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Log Aktivitas
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(Auth::user()->disposisiKepada()->where('status', 'belum_dibaca')->count() > 0)
                                <span class="badge rounded-pill bg-danger">
                                    {{ Auth::user()->disposisiKepada()->where('status', 'belum_dibaca')->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            @forelse(Auth::user()->disposisiKepada()->where('status', 'belum_dibaca')->latest()->take(5)->get() as $disposisi)
                                <li>
                                    <a class="dropdown-item" href="{{ route('disposisi.show', $disposisi) }}">
                                        <small class="text-muted">{{ $disposisi->tanggal_disposisi->format('d/m/Y') }}</small>
                                        <p class="mb-0">Disposisi baru dari {{ $disposisi->dari->name }}</p>
                                        <small>{{ Str::limit($disposisi->suratMasuk->perihal, 30) }}</small>
                                    </a>
                                </li>
                                @if(!$loop->last)
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                            @empty
                                <li><a class="dropdown-item" href="#">Tidak ada notifikasi baru</a></li>
                            @endforelse
                        </ul>
                    </div>
                    
                    <div class="dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <span>&copy; {{ date('Y') }} Sistem Manajemen Surat Sekolah</span>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap 5 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('.sidebar').toggleClass('active');
                $('.main-content').toggleClass('active');
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>
