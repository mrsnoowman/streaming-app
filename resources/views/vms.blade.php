<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>VMS Monitoring - Toll Road</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Bismillah-Bocimi.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/variables.css') }}" rel="stylesheet">
    <style>
        /* Import CSS Variables */
        @import url('/css/variables.css');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            min-height: 100vh;
            color: var(--text-secondary);
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
            position: relative;
            background: var(--gradient-bg);
        }

        /* Background image layer - works on all devices including iOS */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/1636870790794.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        /* Overlay gradient */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-bg);
            opacity: 0.6;
            z-index: -1;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        body.theme-vms {
            --primary-color: var(--secondary-green);
            --primary-dark: var(--secondary-dark-green);
            --primary-light: var(--secondary-light-green);
            --accent-color: var(--secondary-light-green);
            --status-color: var(--status-online);
            --status-bg: var(--status-online-bg);
            --shadow-color: var(--shadow-green);
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        img, video {
            max-width: 100%;
            height: auto;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
            width: 100%;
        }

        /* Header */
        .header {
            background: var(--gradient-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg) var(--spacing-2xl);
            margin-bottom: var(--spacing-2xl);
            box-shadow: 0 4px 20px var(--shadow-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
            min-height: 80px;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .logo {
            width: 60px;
            height: 60px;
            background: transparent;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-2xl);
            color: var(--text-primary);
            font-weight: 700;
            box-shadow: none;
            border: none;
            overflow: visible;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 0;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .title {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            margin: 0;
        }

        .subtitle {
            font-size: var(--font-size-sm);
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
        }

        /* Navigation */
        .nav-menu {
            display: flex;
            gap: var(--spacing-sm);
        }

        .nav-btn {
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--gradient-card);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-md);
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: var(--font-size-sm);
            transition: var(--transition-normal);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            min-height: 36px;
        }

        .nav-btn:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .nav-btn.active {
            background: var(--gradient-secondary);
            color: var(--text-primary);
            box-shadow: 0 var(--spacing-xs) var(--spacing-md) var(--shadow-color);
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            top: var(--spacing-xl);
            right: var(--spacing-xl);
            width: 50px;
            height: 50px;
            background: var(--gradient-card);
            border: 1px solid var(--border-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-normal);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px var(--shadow-primary);
            z-index: var(--z-dropdown);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px var(--shadow-primary);
        }

        .theme-toggle i {
            font-size: var(--font-size-lg);
            color: var(--text-primary);
            transition: var(--transition-normal);
        }

        .theme-toggle.dark-mode i {
            transform: rotate(180deg);
        }

        /* Dark Mode Specific Styles */
        .dark-mode::after {
            background-image: url('{{ asset('images/1636870790794.jpg') }}') !important;
        }

        .dark-mode::before {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(17, 17, 17, 0.7) 50%, rgba(26, 26, 26, 0.7) 100%) !important;
            opacity: 0.7 !important;
        }

        .dark-mode .header {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .dark-mode .video-card {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2) !important;
        }

        .dark-mode .video-container {
            background: linear-gradient(135deg, #111111 0%, #000000 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .dark-mode .pagination-btn {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        .dark-mode .nav-btn {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #94a3b8 !important;
        }

        .dark-mode .nav-btn:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
        }

        .dark-mode .nav-btn.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
        }

        /* Video Grid */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: var(--spacing-md);
            margin-top: var(--spacing-2xl);
        }

        .video-card {
            background: var(--gradient-card);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-xl);
            padding: var(--spacing-lg);
            box-shadow: 0 var(--spacing-md) var(--spacing-4xl) var(--shadow-secondary);
            backdrop-filter: blur(20px);
            transition: var(--transition-slow);
            position: relative;
            overflow: hidden;
        }

        .video-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gradient-shimmer);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
        }

        .video-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 var(--spacing-xl) 60px var(--shadow-primary);
            border-color: var(--border-accent);
        }

        .video-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: var(--spacing-md);
        }

        .location-name {
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            text-align: center;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 240px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            margin-bottom: 0;
            border: 1px solid var(--border-primary);
            box-shadow: inset 0 2px var(--spacing-sm) var(--shadow-primary);
        }

        .video-stream {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--radius-lg);
        }

        .video-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: var(--font-size-xl);
            flex-direction: column;
            gap: var(--spacing-md);
            border-radius: var(--radius-lg);
        }

        .video-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10;
            border-radius: var(--radius-lg);
        }

        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .video-stream.loaded + .video-loading {
            display: none;
        }

        /* Fullscreen Button */
        .fullscreen-btn {
            position: absolute;
            bottom: var(--spacing-md);
            right: var(--spacing-md);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-normal);
            z-index: 10;
            opacity: 0;
        }

        .video-container:hover .fullscreen-btn {
            opacity: 1;
        }

        .fullscreen-btn:hover {
            background: rgba(34, 197, 94, 0.9);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        .fullscreen-btn i {
            color: white;
            font-size: 16px;
        }

        /* Fullscreen video styles */
        .video-container:fullscreen {
            background: black;
            border-radius: 0;
        }

        .video-container:fullscreen .video-stream {
            height: 100%;
            width: 100%;
            object-fit: contain;
            border-radius: 0;
        }

        .video-container:fullscreen .fullscreen-btn {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .video-container:fullscreen:hover .fullscreen-btn {
            opacity: 1;
        }

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-3xl);
            padding: var(--spacing-xl);
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: var(--spacing-md) var(--spacing-lg);
            background: var(--gradient-card);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-normal);
            backdrop-filter: blur(10px);
            min-width: 44px;
            text-align: center;
        }

        .pagination-btn:hover {
            background: var(--gradient-secondary);
            transform: translateY(-2px);
            box-shadow: 0 var(--spacing-sm) var(--spacing-xl) var(--shadow-color);
        }

        .pagination-btn.active {
            background: var(--gradient-secondary);
            box-shadow: 0 var(--spacing-xs) var(--spacing-md) var(--shadow-color);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-info {
            color: var(--text-muted);
            font-size: var(--font-size-base);
            margin: var(--spacing-md) 0 0 0;
            width: 100%;
            text-align: center;
            order: 1;
        }

        /* Performance Optimization */
        .video-stream {
            will-change: transform;
        }

        .video-card {
            contain: layout style paint;
        }

        /* Lazy Loading */
        .video-stream[data-src] {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .video-stream.loaded {
            opacity: 1;
        }

        /* Status Indicator */
        .status-indicator {
            position: fixed;
            top: var(--spacing-xl);
            left: var(--spacing-xl);
            background: var(--status-bg);
            color: var(--status-color);
            padding: var(--spacing-sm) var(--spacing-lg);
            border-radius: var(--radius-xl);
            font-size: var(--font-size-sm);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            backdrop-filter: blur(10px);
            box-shadow: 0 var(--spacing-xs) var(--spacing-md) var(--shadow-color);
            z-index: var(--z-dropdown);
        }

        .status-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Loading Indicator */
        .loading-indicator {
            position: fixed;
            top: var(--spacing-xl);
            right: var(--spacing-xl);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.95) 0%, rgba(22, 163, 74, 0.95) 100%);
            color: white;
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            display: none;
            z-index: var(--z-dropdown);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .loading-indicator.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Error State */
        .error-message {
            background: var(--status-offline-bg);
            border: 1px solid var(--status-offline);
            color: var(--status-offline);
            padding: var(--spacing-xl);
            border-radius: var(--radius-lg);
            text-align: center;
            margin: var(--spacing-xl) 0;
            backdrop-filter: blur(10px);
        }

        /* Responsive Design */
        @media (min-width: 1400px) {
            .video-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 16px;
            }
            
            .video-container {
                height: 260px;
            }
        }

        @media (max-width: 1399px) and (min-width: 1200px) {
            .video-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 14px;
            }
            
            .video-container {
                height: 240px;
            }
        }

        @media (max-width: 1199px) and (min-width: 992px) {
            .container {
                padding: 20px;
            }
            
            .video-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
            }
            
            .video-container {
                height: 220px;
            }
            
            .header {
                padding: 28px 32px;
            }
        }

        @media (max-width: 991px) and (min-width: 768px) {
            .container {
                padding: 18px;
            }
            
            .header {
                padding: 24px 28px;
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .logo {
                width: 100px;
                height: 100px;
                font-size: 26px;
            }
            
            .title {
                font-size: 28px;
            }
            
            .video-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            
            .video-container {
                height: 200px;
            }
            
            .nav-menu {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 767px) and (min-width: 576px) {
            .container {
                padding: 16px;
            }

            .header {
                padding: 20px 24px;
                flex-direction: column;
                gap: 18px;
                text-align: center;
            }

            .logo {
                width: 90px;
                height: 90px;
                font-size: 22px;
            }

            .title {
                font-size: 24px;
            }

            .subtitle {
                font-size: 14px;
            }

            .nav-menu {
                width: 100%;
                justify-content: center;
            }

            .nav-btn {
                flex: 1;
                text-align: center;
                padding: 12px 16px;
                font-size: 14px;
            }

            .video-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .video-container {
                height: 180px;
            }

            .location-name {
                font-size: 14px;
            }

            .status-indicator {
                top: 12px;
                left: 12px;
                padding: 6px 12px;
                font-size: 11px;
            }

            .loading-indicator {
                top: 12px;
                right: 12px;
                padding: 8px 16px;
                font-size: 12px;
            }

            .pagination-container {
                gap: 6px;
                padding: 16px;
            }

            .pagination-btn {
                padding: 8px 12px;
                font-size: 12px;
                min-width: 36px;
            }

            .pagination-info {
                font-size: 12px;
                margin: 0 8px;
            }
        }

        @media (max-width: 575px) {
            .container {
                padding: 12px;
            }

            .header {
                padding: 16px 20px;
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .logo {
                width: 80px;
                height: 80px;
                font-size: 20px;
            }

            .title {
                font-size: 20px;
            }

            .subtitle {
                font-size: 13px;
            }

            .nav-menu {
                flex-direction: column;
                gap: 6px;
                width: 100%;
            }

            .nav-btn {
                width: 100%;
                padding: 10px 16px;
                font-size: 13px;
            }

            .nav-btn i {
                display: none;
            }

            .video-card {
                padding: 12px;
            }

            .video-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .video-container {
                height: 200px;
            }

            .location-name {
                font-size: 13px;
            }

            .status-indicator {
                top: 8px;
                left: 8px;
                padding: 4px 8px;
                font-size: 10px;
            }

            .loading-indicator {
                top: 8px;
                right: 8px;
                padding: 6px 12px;
                font-size: 11px;
            }

            .pagination-container {
                gap: 4px;
                padding: 12px;
                flex-wrap: wrap;
            }

            .pagination-btn {
                padding: 6px 10px;
                font-size: 11px;
                min-width: 32px;
            }

            .pagination-info {
                font-size: 11px;
                margin: 0 6px;
                width: 100%;
                text-align: center;
                order: -1;
            }
        }
    </style>
</head>
<body class="theme-vms">
    <!-- Status Indicator -->
    <div class="status-indicator">
        <i class="fas fa-circle"></i>
        VMS Online
    </div>

    <!-- Theme Toggle Button -->
    <div class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
        <i class="fas fa-sun"></i>
    </div>

    <!-- Loading Indicator -->
    <div class="loading-indicator" id="loadingIndicator">
        <i class="fas fa-sync-alt fa-spin"></i>
        Updating...
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('images/Bismillah-Bocimi.png') }}" alt="BOCIMI Logo" />
                </div>
                <div>
                    <div class="title">VMS Monitoring</div>
                    <div class="subtitle">Toll Road Variable Message Signs</div>
                </div>
            </div>
            
            <div class="nav-menu">
                <a href="/" class="nav-btn">
                    <i class="fas fa-camera"></i> CCTV
                </a>
                <a href="/vms" class="nav-btn active">
                    <i class="fas fa-tv"></i> VMS
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #ef4444;" onmouseover="this.style.background='linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; this.style.color='#ffffff';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#ef4444';">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- VMS Section -->
        <div class="video-grid">
            @forelse($vms as $vmsItem)
                <div class="video-card">
                    <div class="video-header">
                        <div class="location-name">{{ $vmsItem->lokasi }}</div>
                    </div>
                    <div class="video-container" id="container-vms-{{ $vmsItem->id }}">
                        @if($vmsItem->status && $vmsItem->http_link)
                            <video class="video-stream" id="vms-{{ $vmsItem->id }}" autoplay muted playsinline preload="metadata" data-src="{{ $vmsItem->http_link }}">
                                <source src="" type="application/x-mpegURL">
                            </video>
                            <div class="video-loading">
                                <div class="spinner"></div>
                            </div>
                            <button class="fullscreen-btn" onclick="toggleFullscreen('container-vms-{{ $vmsItem->id }}')" title="Fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                        @else
                            <div class="video-placeholder">
                                <i class="fas {{ $vmsItem->status ? 'fa-tv' : 'fa-tv-slash' }}" style="font-size: 32px; color: #64748b;"></i>
                                <div>{{ $vmsItem->status ? 'Loading...' : 'VMS Offline' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="error-message">
                    No VMS streams available
                </div>
            @endforelse
        </div>
        
        <!-- VMS Pagination -->
        @if($vms->hasPages())
            <div class="pagination-container">
                @if($vms->onFirstPage())
                    <span class="pagination-btn disabled">«</span>
                @else
                    <a href="{{ $vms->previousPageUrl() }}" class="pagination-btn">«</a>
                @endif
                
                @foreach($vms->getUrlRange(1, $vms->lastPage()) as $page => $url)
                    @if($page == $vms->currentPage())
                        <span class="pagination-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($vms->hasMorePages())
                    <a href="{{ $vms->nextPageUrl() }}" class="pagination-btn">»</a>
                @else
                    <span class="pagination-btn disabled">»</span>
                @endif
                
                <div class="pagination-info">
                    Showing {{ $vms->firstItem() }} to {{ $vms->lastItem() }} of {{ $vms->total() }} VMS
                </div>
            </div>
        @endif
    </div>

    <script>
        // Theme Toggle Functionality
        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const icon = themeToggle.querySelector('i');
            
            // Check for saved theme preference or default to light mode
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });
            
            function applyTheme(theme) {
                console.log('Applying theme:', theme); // Debug log
                if (theme === 'dark') {
                    body.classList.add('dark-mode');
                    icon.className = 'fas fa-moon';
                    themeToggle.title = 'Switch to Light Mode';
                    console.log('Dark mode applied, body classes:', body.className); // Debug log
                } else {
                    body.classList.remove('dark-mode');
                    icon.className = 'fas fa-sun';
                    themeToggle.title = 'Switch to Dark Mode';
                    console.log('Light mode applied, body classes:', body.className); // Debug log
                }
                
                // Force reflow to ensure CSS variables are updated
                body.offsetHeight;
            }
        }

        // Fullscreen function
        function toggleFullscreen(containerId) {
            const container = document.getElementById(containerId);
            const btn = container.querySelector('.fullscreen-btn i');
            
            if (!document.fullscreenElement) {
                container.requestFullscreen().then(() => {
                    btn.classList.remove('fa-expand');
                    btn.classList.add('fa-compress');
                }).catch(err => {
                    console.error('Error attempting to enable fullscreen:', err);
                });
            } else {
                document.exitFullscreen().then(() => {
                    btn.classList.remove('fa-compress');
                    btn.classList.add('fa-expand');
                });
            }
        }

        // Listen for fullscreen change events
        document.addEventListener('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                // Reset all buttons to expand icon
                document.querySelectorAll('.fullscreen-btn i').forEach(icon => {
                    icon.classList.remove('fa-compress');
                    icon.classList.add('fa-expand');
                });
            }
        });

        // Lazy loading and performance optimization
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme toggle
            initThemeToggle();
            // Intersection Observer for lazy loading videos
            const videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const video = entry.target;
                        const src = video.getAttribute('data-src');
                        
                        if (src && !video.classList.contains('loaded')) {
                            loadVideoStream(video, src);
                            videoObserver.unobserve(video);
                        }
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            // Function to load video stream with auto-play (simplified for speed)
            function loadVideoStream(video, src) {
                const source = video.querySelector('source');
                const loadingSpinner = video.nextElementSibling;
                
                if (Hls.isSupported()) {
                    const hls = new Hls({
                        enableWorker: true,
                        lowLatencyMode: true,
                        backBufferLength: 30,
                        maxBufferLength: 60
                    });
                    
                    hls.loadSource(src);
                    hls.attachMedia(video);
                    
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        video.classList.add('loaded');
                        if (loadingSpinner && loadingSpinner.classList.contains('video-loading')) {
                            loadingSpinner.style.display = 'none';
                        }
                        video.play().catch(e => console.log('Auto-play prevented:', e));
                    });
                    
                    hls.on(Hls.Events.ERROR, (event, data) => {
                        if (data.fatal && loadingSpinner && loadingSpinner.classList.contains('video-loading')) {
                            loadingSpinner.style.display = 'none';
                        }
                    });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    source.src = src;
                    video.load();
                    video.classList.add('loaded');
                    if (loadingSpinner && loadingSpinner.classList.contains('video-loading')) {
                        loadingSpinner.style.display = 'none';
                    }
                    video.play().catch(e => console.log('Auto-play prevented:', e));
                }
            }

            // Observe all video elements on current page only
            const videos = document.querySelectorAll('.video-stream[data-src]');
            videos.forEach(video => {
                videoObserver.observe(video);
            });

            // Load all videos on current page immediately for auto-play
            // Since pagination shows 10 items per page, load all of them
            videos.forEach(video => {
                const src = video.getAttribute('data-src');
                if (src) {
                    loadVideoStream(video, src);
                    videoObserver.unobserve(video);
                }
            });

            // Auto-refresh status every 30 seconds
            setInterval(function() {
                const loadingIndicator = document.getElementById('loadingIndicator');
                loadingIndicator.classList.add('show');
                
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }, 30000);

            // Show loading indicator on page load
            setTimeout(function() {
                const loadingIndicator = document.getElementById('loadingIndicator');
                loadingIndicator.classList.add('show');
                
                setTimeout(function() {
                    loadingIndicator.classList.remove('show');
                }, 2000);
            }, 500);
        });
    </script>
</body>
</html>
