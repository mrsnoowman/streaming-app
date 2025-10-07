<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CCTV Toll Road Dashboard</title>
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
            background: var(--gradient-bg);
            background-image: url('https://10.61.0.15:8181/images/1636870790794.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
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
        }

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
        }

        /* Dark Mode Specific Styles */
        .dark-mode {
            background: linear-gradient(135deg, #000000 0%, #111111 50%, #1a1a1a 100%) !important;
            background-image: url('https://10.61.0.15:8181/images/1636870790794.jpg') !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            color: #e2e8f0 !important;
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

        .form-input::placeholder {
            color: var(--text-muted);
            transition: opacity 0.3s ease;
        }

        .dark-mode .form-input::placeholder {
            color: #94a3b8 !important;
        }

        /* Input Focus Animations */
        .form-group.focused .form-label {
            color: var(--primary-green);
            transform: translateY(-2px);
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .form-group.has-value .form-label {
            color: var(--primary-green);
        }

        .form-group {
            transition: transform 0.3s ease;
        }

        .form-group.focused {
            transform: translateY(-1px);
        }

        /* Field Error Styling */
        .field-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            animation: fadeIn 0.3s ease;
        }

        .dark-mode .field-error {
            color: #fca5a5 !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Error Input Styling */
        .form-input.error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3) !important;
        }

        .dark-mode .form-input.error {
            border-color: #fca5a5 !important;
            box-shadow: 0 0 0 3px rgba(252, 165, 165, 0.3) !important;
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
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Login Button Loading Animation */
        .login-btn.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
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

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Form Loading State */
        .form-loading .form-input {
            opacity: 0.7;
            pointer-events: none;
        }

        .form-loading .login-btn {
            pointer-events: none;
        }

        /* Success Animation */
        .login-success {
            animation: successPulse 0.6s ease-in-out;
        }

        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Error Animation */
        .login-error {
            animation: errorShake 0.5s ease-in-out;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .theme-toggle {
            position: fixed;
            top: var(--spacing-2xl);
            right: var(--spacing-2xl);
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
            backdrop-filter: blur(20px);
            box-shadow: 0 var(--spacing-sm) var(--spacing-2xl) var(--shadow-primary);
            z-index: 1000;
        }

        .dark-mode .theme-toggle {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 var(--spacing-md) var(--spacing-3xl) var(--shadow-primary);
        }

        .theme-toggle i {
            font-size: var(--font-size-lg);
            color: var(--text-primary);
            transition: var(--transition-normal);
        }

        .theme-toggle.dark-mode i {
            transform: rotate(180deg);
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                margin: var(--spacing-lg);
                padding: var(--spacing-xl);
                max-width: none;
            }

            .login-title {
                font-size: var(--font-size-2xl);
            }

            .theme-toggle {
                top: var(--spacing-lg);
                right: var(--spacing-lg);
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: var(--spacing-md);
                padding: var(--spacing-lg);
            }

            .logo {
                width: 50px;
                height: 50px;
            }

            .login-title {
                font-size: var(--font-size-xl);
            }
        }
    </style>
</head>
<body class="theme-cctv">
    <!-- Theme Toggle Button -->
    <div class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
        <i class="fas fa-sun"></i>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo-section">
                <div class="logo">
                    <img src="https://10.61.0.15:5005/images/Bismillah-Bocimi.png" alt="BOCIMI Logo">
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

            <button type="submit" class="login-btn" id="loginButton">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Dashboard</span>
            </button>
        </form>
    </div>

    <script>
        // Theme toggle functionality
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
                if (theme === 'dark') {
                    body.classList.add('dark-mode');
                    icon.className = 'fas fa-moon';
                    themeToggle.title = 'Switch to Light Mode';
                } else {
                    body.classList.remove('dark-mode');
                    icon.className = 'fas fa-sun';
                    themeToggle.title = 'Switch to Dark Mode';
                }
                
                // Force reflow to ensure CSS variables are updated
                body.offsetHeight;
            }
        }

        // Simple form validation function (non-blocking)
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            console.log('=== FORM VALIDATION DEBUG ===');
            console.log('Email element:', email);
            console.log('Email value:', email ? email.value : 'EMAIL ELEMENT NOT FOUND');
            console.log('Password element:', password);
            console.log('Password value:', password ? password.value : 'PASSWORD ELEMENT NOT FOUND');
            console.log('=== END VALIDATION DEBUG ===');
            
            // Always return true to allow form submission
            // Server-side validation will handle the actual validation
            return true;
        }

        // Show field error
        function showFieldError(field, message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '12px';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;
            
            field.parentNode.appendChild(errorDiv);
            field.style.borderColor = '#ef4444';
            field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
        }

        // Clear field error
        function clearFieldError(field) {
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
            field.style.borderColor = '';
            field.style.boxShadow = '';
        }

        // Email validation
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Login form animation handling
        function initLoginAnimations() {
            const loginForm = document.querySelector('form[method="POST"]');
            const loginBtn = document.getElementById('loginButton');
            const formContainer = document.querySelector('.login-container');
            const inputs = document.querySelectorAll('.form-input');

            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function(e) {
                    console.log('=== FORM SUBMIT DEBUG ===');
                    console.log('Form element:', loginForm);
                    
                    // Get form data
                    const formData = new FormData(loginForm);
                    console.log('Form data:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }
                    
                    // Just add loading animation - don't prevent form submission
                    loginBtn.classList.add('loading');
                    loginBtn.disabled = true;
                    formContainer.classList.add('form-loading');
                    
                    // Disable all inputs
                    inputs.forEach(input => {
                        input.disabled = true;
                    });

                    console.log('Form submitting naturally to server...');
                    console.log('=== END SUBMIT DEBUG ===');
                    // Let form submit naturally - no preventDefault
                });

                // Handle form validation errors
                const errorMessages = document.querySelectorAll('.error-message');
                if (errorMessages.length > 0) {
                    // Add error animation to form
                    formContainer.classList.add('login-error');
                    setTimeout(() => {
                        formContainer.classList.remove('login-error');
                    }, 500);
                }

                // Handle success messages
                const successMessages = document.querySelectorAll('.success-message');
                if (successMessages.length > 0) {
                    // Add success animation to form
                    formContainer.classList.add('login-success');
                    setTimeout(() => {
                        formContainer.classList.remove('login-success');
                    }, 600);
                }
            }
        }

        // Input focus animations
        function initInputAnimations() {
            const inputs = document.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                    clearFieldError(this);
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.value) {
                        this.parentElement.classList.add('has-value');
                    } else {
                        this.parentElement.classList.remove('has-value');
                    }
                });

                input.addEventListener('input', function() {
                    clearFieldError(this);
                    if (this.value) {
                        this.parentElement.classList.add('has-value');
                    } else {
                        this.parentElement.classList.remove('has-value');
                    }
                });

                // Check initial value
                if (input.value) {
                    input.parentElement.classList.add('has-value');
                }
            });
        }

        // Debug server-side errors
        function debugServerErrors() {
            console.log('=== SERVER-SIDE ERROR DEBUG ===');
            const serverErrors = document.querySelectorAll('.error-message');
            console.log('Found', serverErrors.length, 'server error messages');
            
            serverErrors.forEach((error, index) => {
                console.log(`Server Error ${index + 1}:`, error.textContent.trim());
            });
            
            // Check if there are any Laravel validation errors
            const validationErrors = document.querySelectorAll('.error-message');
            if (validationErrors.length > 0) {
                console.log('Laravel validation errors found:');
                validationErrors.forEach((error, index) => {
                    console.log(`Validation Error ${index + 1}:`, error.textContent.trim());
                });
            }
            console.log('=== END SERVER ERROR DEBUG ===');
        }

        // Initialize theme toggle when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initThemeToggle();
            initLoginAnimations();
            initInputAnimations();
            debugServerErrors();
        });
    </script>
</body>
</html>
