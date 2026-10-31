<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập — carpooling.vn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --shadow-primary: 2px 2px 0px -1px #000000;
            --shadow-secondary: 2px 2px 0px -1px #000000;
            --color-primary-blue: #BFDBFE;
            --color-primary-blue-hover: #93C5FD;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #FFFFFF;
            color: #000000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        /* Roomio dot-grid background */
        .roomio-grid-bg {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(#000000 1.25px, transparent 1.25px);
            background-size: 24px 24px;
            opacity: 0.45;
            z-index: -10;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.85));
            pointer-events: none;
            z-index: -9;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.6px;
            text-decoration: none;
            color: #000000;
            display: inline-block;
        }

        .brand-title span {
            color: #2563eb;
        }

        .brand-sub {
            font-size: 0.85rem;
            color: #52525B;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Roomio Neo-Brutalist Card */
        .roomio-card {
            border: 2px solid #000000;
            border-radius: 8px;
            background-color: #FFFFFF;
            box-shadow: var(--shadow-secondary);
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
            color: #18181B;
        }

        .form-input {
            width: 100%;
            border: 2px solid #000000;
            border-radius: 6px;
            padding: 0.65rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            background-color: #FFFFFF;
            color: #000000;
            outline: none;
            transition: all 120ms ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 3px #BFDBFE;
        }

        /* Roomio exact button */
        .roomio-button {
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-primary);
            display: inline-flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: var(--color-primary-blue);
            padding: 0.7rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 800;
            color: #000000;
            box-shadow: var(--shadow-primary);
            text-decoration: none;
            transition: transform 80ms ease, box-shadow 80ms ease, background-color 150ms ease, color 150ms ease;
            position: relative;
            overflow: hidden;
            width: 100%;
            user-select: none;
        }

        .roomio-button::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: #FFFFFF;
            clip-path: circle(0% at var(--hover-x, 50%) var(--hover-y, 50%));
            transition: clip-path 260ms ease-out;
            pointer-events: none;
            z-index: 1;
        }

        .roomio-button:hover::before {
            clip-path: circle(160% at var(--hover-x, 50%) var(--hover-y, 50%));
        }

        .roomio-button:not(:disabled):hover {
            color: #2563eb !important;
        }

        .roomio-button:active {
            transform: translate(var(--tap-depth), var(--tap-depth)) !important;
            box-shadow: none !important;
        }

        .roomio-button span {
            position: relative;
            z-index: 2;
        }

        .roomio-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Demo helper chips */
        .demo-accounts {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px dashed #D4D4D8;
        }

        .demo-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: #71717A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.65rem;
        }

        .chip-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .demo-chip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F4F4F5;
            border: 1.5px solid #000000;
            border-radius: 6px;
            padding: 0.45rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 100ms ease;
        }

        .demo-chip:hover {
            background: #BFDBFE;
            transform: translate(-1px, -1px);
            box-shadow: 1px 1px 0px #000000;
        }

        .demo-chip:active {
            transform: translate(1px, 1px);
            box-shadow: none;
        }

        .alert-error {
            border: 2px solid #000000;
            background-color: #FEE2E2;
            color: #991B1B;
            padding: 0.65rem 0.85rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }

        .alert-success {
            border: 2px solid #000000;
            background-color: #DCFCE7;
            color: #166534;
            padding: 0.65rem 0.85rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }

        .bottom-links {
            margin-top: 1.25rem;
            text-align: center;
        }

        .bottom-links a {
            font-size: 0.8rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: underline;
            transition: color 120ms;
        }

        .bottom-links a:hover {
            color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="roomio-grid-bg"></div>
    <div class="bg-overlay"></div>

    <div class="login-wrapper">
        <div class="brand-header">
            <a href="/" class="brand-title">carpooling<span>.vn</span></a>
            <div class="brand-sub">Nền tảng đi nhờ xe công sở phi thương mại</div>
        </div>

        <div class="roomio-card">
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email hoặc số điện thoại</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="text" 
                        class="form-input" 
                        value="{{ old('email', 'admin@carpooling.vn') }}" 
                        required 
                        autofocus
                        autocomplete="username"
                        placeholder="admin@carpooling.vn"
                    >
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                        <label for="password" class="form-label" style="margin-bottom: 0;">Mật khẩu</label>
                        <span style="font-size: 0.75rem; color: #71717A; font-weight: 600;">Mặc định: password</span>
                    </div>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="form-input" 
                        value="password"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                    <input type="checkbox" id="remember" name="remember" style="accent-color: #2563eb; width: 16px; height: 16px;">
                    <label for="remember" style="font-size: 0.8rem; font-weight: 600; color: #3F3F46; cursor: pointer;">Ghi nhớ đăng nhập</label>
                </div>

                <button type="submit" class="roomio-button">
                    <span>Đăng nhập</span>
                </button>
            </form>

            <div class="demo-accounts">
                <div class="demo-title">Nhấp để điền nhanh tài khoản thử nghiệm:</div>
                <div class="chip-group">
                    <div class="demo-chip" onclick="fillAccount('admin@carpooling.vn', 'password')">
                        <span>Quản trị viên (Admin Ops)</span>
                        <span style="color: #2563eb; font-size: 0.7rem;">admin@carpooling.vn</span>
                    </div>
                    <div class="demo-chip" onclick="fillAccount('driver@carpooling.vn', 'password')">
                        <span>Chủ xe (Tài xế Tuấn)</span>
                        <span style="color: #2563eb; font-size: 0.7rem;">driver@carpooling.vn</span>
                    </div>
                    <div class="demo-chip" onclick="fillAccount('passenger@carpooling.vn', 'password')">
                        <span>Hành khách (Phương Thảo)</span>
                        <span style="color: #2563eb; font-size: 0.7rem;">passenger@carpooling.vn</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-links">
            <a href="/">← Về trang chủ carpooling.vn</a>
        </div>
    </div>

    <script>
        // Roomio ripple hover effect
        document.addEventListener('pointerover', function(event) {
            const action = event.target.closest('.roomio-button');
            if (!action) return;
            const bounds = action.getBoundingClientRect();
            action.style.setProperty('--hover-x', `${event.clientX - bounds.left}px`);
            action.style.setProperty('--hover-y', `${event.clientY - bounds.top}px`);
        });

        // Quick demo filler
        function fillAccount(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
