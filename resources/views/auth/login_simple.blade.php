<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CCTV Toll Road Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Bismillah-Bocimi.png') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Dark Mode Specific Styles */
        .dark-mode {
            color: #e2e8f0 !important;
        }

        .dark-mode::after {
            background-image: url('{{ asset('images/1636870790794.jpg') }}') !important;
        }

        .dark-mode::before {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(17, 17, 17, 0.7) 50%, rgba(26, 26, 26, 0.7) 100%) !important;
            opacity: 0.7 !important;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: var(--spacing-2xl);
            margin: var(--spacing-2xl);
            background: var(--gradient-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-2xl);
            box-shadow: 0 var(--spacing-md) var(--spacing-4xl) var(--shadow-secondary);
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
            z-index: 1;
        }

        .dark-mode .login-container {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2) !important;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        }

        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-3xl);
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
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

        .login-title {
            font-size: var(--font-size-3xl);
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: var(--spacing-sm);
        }

        .login-subtitle {
            font-size: var(--font-size-base);
            color: var(--text-muted);
            font-weight: 400;
        }

        .form-group {
            margin-bottom: var(--spacing-xl);
        }

        .form-label {
            display: block;
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--spacing-sm);
        }

        .form-input {
            width: 100%;
            padding: var(--spacing-md) var(--spacing-lg);
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            font-size: var(--font-size-base);
            color: var(--text-primary);
            transition: var(--transition-normal);
            backdrop-filter: blur(10px);
        }

        .dark-mode .form-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px var(--shadow-green);
            background: var(--bg-card-hover);
        }

        .dark-mode .form-input:focus {
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3) !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }

        .login-btn {
            width: 100%;
            padding: var(--spacing-lg);
            background: var(--gradient-primary);
            border: none;
            border-radius: var(--radius-lg);
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
            margin-bottom: var(--spacing-lg);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 var(--spacing-lg) var(--spacing-4xl) var(--shadow-green);
        }

        .login-btn:active {
            transform: translateY(0);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        /* Loading state for login button */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .login-btn.loading i {
            opacity: 0;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: var(--spacing-md);
            border-radius: var(--radius-lg);
            font-size: var(--font-size-sm);
            margin-bottom: var(--spacing-lg);
            text-align: center;
        }

        .dark-mode .error-message {
            background: rgba(239, 68, 68, 0.2) !important;
            border: 1px solid rgba(239, 68, 68, 0.4) !important;
            color: #fca5a5 !important;
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            padding: var(--spacing-md);
            border-radius: var(--radius-lg);
            font-size: var(--font-size-sm);
            margin-bottom: var(--spacing-lg);
            text-align: center;
        }

        .dark-mode .success-message {
            background: rgba(16, 185, 129, 0.2) !important;
            border: 1px solid rgba(16, 185, 129, 0.4) !important;
            color: #34d399 !important;
        }
    </style>
</head>
<body class="theme-cctv">
    <div class="login-container">
        <div class="login-header">
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('images/Bismillah-Bocimi.png') }}" alt="BOCIMI Logo">
                </div>
            </div>
            <h1 class="login-title">Login Dashboard</h1>
            <p class="login-subtitle">Akses CCTV Toll Road Monitoring System</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="Enter your email address"
                    value="{{ old('email') }}"
                    required 
                    autocomplete="email"
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Enter your password"
                    required 
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                Login to Dashboard
            </button>
        </form>
    </div>

    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const body = document.body;
            const themeToggle = document.getElementById('theme-toggle');
            const isDark = body.classList.contains('dark-mode');
            
            if (isDark) {
                body.classList.remove('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const body = document.body;
            const themeToggle = document.getElementById('theme-toggle');
            
            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                body.classList.remove('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });

        // Add loading animation to login button when clicked
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[method="POST"]');
            const loginBtn = document.querySelector('.login-btn');
            
            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function() {
                    // Add loading class to button
                    loginBtn.classList.add('loading');
                    
                    // Optional: Add a small delay to ensure the animation is visible
                    setTimeout(function() {
                        // The form will submit naturally
                    }, 100);
                });
            }
        });
    </script>

</body>
</html>
