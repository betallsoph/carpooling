<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpooling VN — Đi làm chung xe, chia đôi tiền xăng</title>
    <meta name="description" content="Nền tảng chia sẻ chuyến đi làm tuyến cố định cho dân văn phòng. Tiết kiệm tiền xăng, văn minh, đúng quy định pháp luật.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;

            /* roomio anttxqt exact colors */
            --color-blue-150: rgb(205, 227, 254);
            --color-primary-blue: rgb(191, 219, 254);
            --color-primary-dark: rgb(96, 165, 250);
            --color-primary-light: rgb(219, 234, 254);
            --color-primary-lighter: rgb(239, 246, 255);
            --color-primary-medium: rgb(191, 219, 254);

            /* roomio exact offset shadows */
            --shadow-primary: 2px 2px 0px -1px #000000;
            --shadow-secondary: 2px 2px 0px -1px #000000;
            --shadow-secondary-opposite: -2px 2px 0px -1px #000000;
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-primary);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: var(--font-sans);
            background-color: #FFFFFF;
            color: #000000;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* roomio-grid-bg exact replica */
        .roomio-grid-bg {
            background-image:
                linear-gradient(to right, rgba(156, 163, 175, 0.22) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(156, 163, 175, 0.22) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .bg-overlay {
            pointer-events: none;
            position: fixed;
            inset: 0;
            z-index: -10;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.82));
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 2rem 1.25rem 4rem;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #000000;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #000000;
        }

        .brand-logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: var(--color-primary-blue);
            box-shadow: var(--shadow-primary);
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -0.6px;
            line-height: 1;
        }

        .brand-title span {
            color: #2563eb;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* roomio exact Buttons */
        .roomio-button {
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-primary);
            display: inline-flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: var(--color-primary-blue);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 800;
            color: #000000;
            box-shadow: var(--shadow-primary);
            text-decoration: none;
            transition-property: background-color, border-color, color, opacity, transform, box-shadow;
            transition-duration: 150ms, 150ms, 150ms, 150ms, 200ms, 200ms;
            transition-timing-function: ease, ease, ease, ease, cubic-bezier(0.34, 1.56, 0.64, 1), cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 0;
            isolation: isolate;
            overflow: hidden;
        }

        .roomio-button-white {
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-primary);
            display: inline-flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 800;
            color: #000000;
            box-shadow: var(--shadow-primary);
            text-decoration: none;
            transition-property: background-color, border-color, color, opacity, transform, box-shadow;
            transition-duration: 150ms, 150ms, 150ms, 150ms, 200ms, 200ms;
            transition-timing-function: ease, ease, ease, ease, cubic-bezier(0.34, 1.56, 0.64, 1), cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* roomio exact hover ripple wipe effect */
        @media (hover: hover) and (pointer: fine) {
            .roomio-button::before {
                position: absolute;
                z-index: -1;
                inset: 0;
                background: #FFFFFF;
                clip-path: circle(0 at var(--hover-x, 50%) var(--hover-y, 50%));
                content: '';
                transition: clip-path 320ms cubic-bezier(0.22, 1, 0.36, 1);
            }

            .roomio-button:hover::before {
                clip-path: circle(160% at var(--hover-x, 50%) var(--hover-y, 50%));
            }

            .roomio-button:not(:disabled):hover {
                color: #2563eb !important;
            }

            .roomio-button:not(:disabled):hover svg {
                stroke: #2563eb !important;
            }

            .roomio-button-white:hover {
                background-color: #F4F4F5;
            }
        }

        /* roomio exact active tap press effect */
        .roomio-button:active,
        .roomio-button-white:active,
        .roomio-card:active {
            transform: translate(var(--tap-depth, 1px), var(--tap-depth, 1px)) !important;
            box-shadow: none !important;
            transition: transform 80ms ease, box-shadow 80ms ease !important;
        }

        /* roomio Cards */
        .roomio-card {
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-secondary);
            border-radius: 8px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            box-shadow: var(--shadow-secondary);
            padding: 1.5rem;
        }

        .roomio-blue-card {
            --tap-depth: 1px;
            --tap-shadow: var(--shadow-secondary);
            border-radius: 8px;
            border: 2px solid #000000;
            background-color: #DBEAFE;
            box-shadow: var(--shadow-secondary);
            padding: 1.5rem;
        }

        /* roomio Window */
        .roomio-window {
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            box-shadow: var(--shadow-secondary);
        }

        .roomio-window-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000000;
            background-color: #F4F4F5;
            padding: 0.65rem 1rem;
        }

        .roomio-window-dots {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .roomio-window-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            border: 1px solid #000000;
        }

        .dot-red { background-color: #EF4444; }
        .dot-yellow { background-color: #F59E0B; }
        .dot-green { background-color: #10B981; }

        .window-title {
            font-size: 0.8rem;
            font-weight: 700;
            font-family: var(--font-mono);
            color: #52525B;
        }

        /* Hero */
        .hero {
            padding: 1.5rem 0 3rem;
            max-width: 860px;
        }

        .corridor-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            border: 2px solid #000000;
            background-color: var(--color-primary-lighter);
            font-size: 0.8rem;
            font-weight: 800;
            box-shadow: var(--shadow-primary);
            margin-bottom: 1.5rem;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        h1 {
            font-size: 3.25rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -1.2px;
            margin-bottom: 1.25rem;
        }

        .hero-desc {
            font-size: 1.15rem;
            color: #3F3F46;
            line-height: 1.6;
            font-weight: 500;
            max-width: 720px;
            margin-bottom: 2rem;
        }

        .tech-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            font-size: 0.8rem;
            font-weight: 800;
            box-shadow: var(--shadow-primary);
        }

        .chip-blue {
            background-color: var(--color-primary-lighter);
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 1.75rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 960px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            h1 {
                font-size: 2.35rem;
            }
        }

        /* Form elements */
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 1rem;
        }

        label {
            font-size: 0.8rem;
            font-weight: 800;
            color: #18181B;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        input[type="text"] {
            width: 100%;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            padding: 0.65rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            outline: none;
        }

        input[type="text"]:focus {
            box-shadow: 0 0 0 2px var(--color-primary-blue);
        }

        .route-stop {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FAFAFA;
            margin-bottom: 0.75rem;
        }

        .stop-badge {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1.5px solid #000000;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        .stop-title {
            font-size: 0.85rem;
            font-weight: 800;
        }

        .stop-addr {
            font-size: 0.75rem;
            color: #52525B;
            font-weight: 500;
        }

        .sql-snippet {
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #18181B;
            color: #E4E4E7;
            padding: 0.85rem;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            line-height: 1.55;
            overflow-x: auto;
            margin-top: 1rem;
        }

        .sql-kw { color: #F43F5E; font-weight: 700; }
        .sql-fn { color: #38BDF8; font-weight: 600; }
        .sql-str { color: #34D399; }

        /* VietQR box */
        .qr-card-content {
            padding: 1.5rem;
        }

        .vietqr-box {
            display: flex;
            gap: 1.25rem;
            align-items: center;
            padding: 1rem;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            box-shadow: var(--shadow-primary);
            margin-bottom: 1.25rem;
        }

        .vietqr-img {
            width: 95px;
            height: 95px;
            border: 2px solid #000000;
            border-radius: 6px;
            padding: 4px;
            background: #FFFFFF;
        }

        .qr-meta-title {
            font-size: 0.95rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .qr-meta-sub {
            font-size: 0.75rem;
            color: #52525B;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .amount-highlight {
            font-size: 1.25rem;
            font-weight: 900;
            color: #059669;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            font-size: 0.8rem;
            font-weight: 600;
            border-bottom: 1px dashed #D4D4D8;
        }

        .cost-row:last-child {
            border-bottom: none;
            padding-top: 0.75rem;
            font-size: 0.9rem;
            font-weight: 800;
        }

        /* SVG Icons */
        .icon {
            display: inline-block;
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
            stroke: currentColor;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            vertical-align: middle;
            flex-shrink: 0;
        }

        .search-results-box {
            border-top: 2px solid #000000;
            padding: 1.25rem;
            background-color: #FAFAFA;
        }

        .trip-item {
            border: 2px solid #000000;
            border-radius: 6px;
            background: #FFFFFF;
            padding: 1rem;
            box-shadow: var(--shadow-primary);
        }

        .badge-tag {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            border-radius: 4px;
            border: 1.5px solid #000000;
            background: var(--color-primary-blue);
        }
    </style>
</head>
<body>
    <div class="roomio-grid-bg fixed inset-0 -z-10 opacity-60"></div>
    <div class="bg-overlay"></div>

    <div class="container">
        <!-- Header -->
        <header>
            <a href="/" class="brand-link">
                <div class="brand-logo-box">
                    <svg class="icon" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 10.7 2 10.9 2 11v5c0 .6.4 1 1 1h2"></path>
                        <circle cx="7" cy="17" r="2"></circle>
                        <path d="M9 17h6"></path>
                        <circle cx="17" cy="17" r="2"></circle>
                    </svg>
                </div>
                <div>
                    <div class="brand-title">carpooling<span>.vn</span></div>
                </div>
            </a>

            <div class="header-actions">
                <a href="/horizon" target="_blank" class="roomio-button-white">
                    <svg class="icon" viewBox="0 0 24 24">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    <span>Hàng đợi Horizon</span>
                </a>
                <a href="/admin" target="_blank" class="roomio-button">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Trang quản trị (Admin)</span>
                </a>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="corridor-pill">
                <span class="pulse-dot"></span>
                <span>Tuyến thí điểm số 1: Bình Dương (Thủ Dầu Một) ➔ Quận 1 (TP.HCM)</span>
            </div>

            <h1>Đi làm chung xe,<br>chia đôi tiền xăng.</h1>

            <p class="hero-desc">
                Sáng đi làm, chiều đi về cùng một trục đường thì rủ nhau đi chung một xe. Vừa tiết kiệm tiền hơn đi taxi,
                vừa nhàn đầu hơn phải tự lái xe máy qua mấy ngã tư kẹt xe mỗi ngày. Tiền xăng cộ và vé cầu đường chia đều, 
                chuyển khoản trực tiếp cho tài xế qua VietQR.
            </p>

            <div class="tech-chips">
                <div class="chip chip-blue">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    <span>Laravel 13.39</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                    <span>PostgreSQL PostGIS</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18M9 21V9"></path></svg>
                    <span>Filament 5.11</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"></rect><line x1="2" x2="22" y1="10" y2="10"></line></svg>
                    <span>VietQR NAPAS 247</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Nghị định 218/2026</span>
                </div>
            </div>
        </section>

        <!-- Main Interactive Grid -->
        <div class="grid-2">
            <!-- Left Card: PostGIS Corridor Matching Interactive Window -->
            <div class="roomio-window">
                <div class="roomio-window-bar">
                    <div class="roomio-window-dots">
                        <span class="roomio-window-dot dot-red"></span>
                        <span class="roomio-window-dot dot-yellow"></span>
                        <span class="roomio-window-dot dot-green"></span>
                    </div>
                    <div class="window-title">thuật-toán-ghép-hành-lang.postgis</div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #16A34A;">● Sẵn sàng</div>
                </div>

                <div style="padding: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #3F3F46; line-height: 1.5; margin-bottom: 1.25rem;">
                        Chủ xe đã đăng sẵn lịch trình đi làm sáng T2–T6. Khi khách tìm xe, hệ thống dùng <strong>PostGIS</strong> 
                        quét dọc trục đường xe chạy (bán kính lệch ≤ 2km) và tự động nhận diện chiều xe đi.
                    </p>

                    <div class="route-stop">
                        <div class="stop-badge" style="background-color: #E0E7FF; color: #4338CA;">A</div>
                        <div>
                            <div class="stop-title">Điểm đón khách (Pickup)</div>
                            <div class="stop-addr">Lotte Mart Bình Dương (Đại lộ Bình Dương, Thuận An)</div>
                        </div>
                    </div>

                    <div class="route-stop">
                        <div class="stop-badge" style="background-color: #DCFCE7; color: #15803D;">B</div>
                        <div>
                            <div class="stop-title">Điểm trả khách (Dropoff)</div>
                            <div class="stop-addr">Ngã tư Hàng Xanh (Điện Biên Phủ, Bình Thạnh, TP.HCM)</div>
                        </div>
                    </div>

                    <button id="btnRunSearch" class="roomio-button" style="width: 100%; margin-top: 0.5rem; justify-content: center;">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" x2="16.65" y1="21" y2="16.65"></line></svg>
                        <span>Thử chạy tìm xe theo lộ trình này</span>
                    </button>

                    <div class="sql-snippet">
<span class="sql-kw">SELECT</span> * <span class="sql-kw">FROM</span> trip_instances
<span class="sql-kw">WHERE</span> <span class="sql-fn">ST_DWithin</span>(route_line::geography, pickup_point, 2000)
  <span class="sql-kw">AND</span> <span class="sql-fn">ST_LineLocatePoint</span>(route_line, pickup) &lt; <span class="sql-fn">ST_LineLocatePoint</span>(route_line, dropoff);
                    </div>
                </div>

                <!-- Dynamic Search Result Area -->
                <div id="searchResultArea" class="search-results-box" style="display: none;">
                    <div style="font-size: 0.8rem; font-weight: 800; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>KẾT QUẢ TÌM THẤY TRỰC TIẾP:</span>
                        <span class="badge-tag">Khớp lộ trình 100%</span>
                    </div>

                    <div class="trip-item">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 800;">Nguyễn Minh Tuấn</h4>
                                <p style="font-size: 0.75rem; color: #52525B;">Toyota Vios G (Trắng) · Biển số: 61A-889.92</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 1.15rem; font-weight: 900; color: #059669;">38.000đ</div>
                                <div style="font-size: 0.7rem; color: #71717A; font-weight: 600;">tiền xăng chia đều</div>
                            </div>
                        </div>

                        <div style="font-size: 0.8rem; color: #27272A; line-height: 1.4; padding-top: 0.5rem; border-top: 1px dashed #E4E4E7;">
                            <div><strong>Xuất phát:</strong> 06:45 sáng (T2–T6) · Còn trống: <strong>2 ghế</strong></div>
                            <div><strong>Tuyến gốc:</strong> TT Hành chính Bình Dương ➔ Nhà Hát Thành Phố (Q.1)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Card: VietQR & Legal Safety -->
            <div style="display: flex; flex-direction: column; gap: 1.75rem;">
                <div class="roomio-window">
                    <div class="roomio-window-bar">
                        <div class="roomio-window-dots">
                            <span class="roomio-window-dot dot-red"></span>
                            <span class="roomio-window-dot dot-yellow"></span>
                            <span class="roomio-window-dot dot-green"></span>
                        </div>
                        <div class="window-title">chia-se-chi-phi-vietqr.pay</div>
                    </div>

                    <div class="qr-card-content">
                        <p style="font-size: 0.85rem; color: #3F3F46; line-height: 1.5; margin-bottom: 1.25rem;">
                            Tiền xăng cộ và vé cầu đường chia đều cho số người trên xe (1 chủ xe + số khách).
                            Hành khách quét mã <strong>VietQR</strong> chuyển khoản thẳng vào tài khoản ngân hàng của chủ xe.
                        </p>

                        <div class="vietqr-box">
                            <img src="https://img.vietqr.io/image/VCB-1023456789-compact2.png?amount=38000&addInfo=CP000001&accountName=NGUYEN%20MINH%20TUAN" 
                                 alt="Mã VietQR đóng góp tiền xăng" 
                                 class="vietqr-img">
                            <div>
                                <div class="qr-meta-title">Nguyễn Minh Tuấn (VCB)</div>
                                <div class="qr-meta-sub">TK: 1023456789 · Nội dung: <b>CP000001</b></div>
                                <div>
                                    <span class="amount-highlight">38.000đ</span>
                                    <span style="font-size: 0.75rem; color: #52525B; font-weight: 600;">/ lượt đi</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cost transparency breakdown -->
                        <div style="border: 2px solid #000000; border-radius: 6px; padding: 0.85rem; background: #FAFAFA;">
                            <div class="cost-row">
                                <span style="color: #52525B;">Tiền xăng (34.5 km × 1.800đ/km)</span>
                                <span>62.100đ</span>
                            </div>
                            <div class="cost-row">
                                <span style="color: #52525B;">Vé trạm thu phí Lái Thiêu</span>
                                <span>15.000đ</span>
                            </div>
                            <div class="cost-row">
                                <span>Mỗi người đóng góp (chia đôi)</span>
                                <span style="color: #059669;">38.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal notice box (Neo-Brutalist blue card) -->
                <div class="roomio-blue-card">
                    <div style="display: flex; gap: 0.65rem; align-items: center; margin-bottom: 0.5rem;">
                        <svg class="icon" viewBox="0 0 24 24" style="stroke: #1E40AF; width: 22px; height: 22px;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                        <h4 style="font-size: 1rem; font-weight: 900; color: #1E3A8A;">Đúng luật, không phải xe dù gom khách</h4>
                    </div>
                    <p style="font-size: 0.825rem; color: #1E3A8A; line-height: 1.55; font-weight: 500;">
                        Theo đúng tinh thần <strong>Nghị định 218/2026</strong>: Ứng dụng không bán vé, không mở ví giữ tiền hộ, 
                        và không ấn định chuyến để kiếm lời. Đây thuần túy là xe gia đình cá nhân đi làm, chia sẻ tiền xăng cộ với đồng nghiệp cùng đường.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer / Admin access callout -->
        <div class="roomio-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 800;">Tài khoản đăng nhập quản trị (Admin Ops)</h4>
                <p style="font-size: 0.8rem; color: #52525B;">Email: <strong>admin@carpooling.vn</strong> · Mật khẩu: <strong>password</strong></p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="/admin/login" target="_blank" class="roomio-button">
                    <span>Mở màn hình đăng nhập Admin</span>
                    <svg class="icon" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Roomio exact hover ripple script and interactive simulation -->
    <script>
        document.addEventListener('pointerover', function(event) {
            const action = event.target.closest('.roomio-button, .roomio-button-white');
            if (!action) return;
            const bounds = action.getBoundingClientRect();
            action.style.setProperty('--hover-x', `${event.clientX - bounds.left}px`);
            action.style.setProperty('--hover-y', `${event.clientY - bounds.top}px`);
        });

        // Interactive Search Simulation
        const btnRunSearch = document.getElementById('btnRunSearch');
        const searchResultArea = document.getElementById('searchResultArea');

        btnRunSearch.addEventListener('click', function() {
            btnRunSearch.disabled = true;
            btnRunSearch.innerHTML = '<span>Đang tính toán tọa độ...</span>';

            setTimeout(function() {
                searchResultArea.style.display = 'block';
                btnRunSearch.innerHTML = '<svg class="icon" viewBox="0 0 24 24" style="stroke: #15803D;"><polyline points="20 6 9 17 4 12"></polyline></svg><span style="color: #15803D;">Đã tìm thấy chuyến xe phù hợp!</span>';
                btnRunSearch.style.backgroundColor = '#DCFCE7';
                btnRunSearch.disabled = false;
            }, 300);
        });
    </script>
</body>
</html>
