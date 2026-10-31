<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpooling VN — Rủ nhau đi làm chung, tiện đường thì ghép xe</title>
    <meta name="description" content="Nền tảng kết nối dân văn phòng đi làm cùng đường. Không giao dịch tiền nong trên app, tiền xăng tùy lòng với chủ xe.">

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

        .chip-green {
            background-color: #DCFCE7;
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

        /* Stop Card */
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

        /* Etiquette checklist box */
        .etiquette-card {
            padding: 1.5rem;
        }

        .rule-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            padding: 0.85rem 0;
            border-bottom: 1px dashed #D4D4D8;
        }

        .rule-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .rule-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1.5px solid #000000;
            background: var(--color-primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 900;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .rule-title {
            font-size: 0.875rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .rule-desc {
            font-size: 0.8rem;
            color: #52525B;
            line-height: 1.45;
            font-weight: 500;
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

        .badge-green {
            background-color: #DCFCE7;
            color: #15803D;
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
                <div class="brand-title">carpooling<span>.vn</span></div>
            </a>

            <div class="header-actions">
                <a href="/horizon" target="_blank" class="roomio-button-white">
                    <span>Hàng đợi Horizon</span>
                </a>
                <a href="/admin" target="_blank" class="roomio-button">
                    <span>Trang quản trị (Admin)</span>
                </a>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <h1>Tiện đường thì đi chung,<br>tiền xăng tùy lòng.</h1>

            <p class="hero-desc">
                Cùng đi làm sáng chiều trên một trục đường thì rủ nhau đi chung xe cho đỡ buồn ngủ và đỡ kẹt xe. 
                Nền tảng <strong>không thu bất kỳ đồng phí nào</strong> và <strong>không giao dịch tiền trên app</strong>. 
                Tiền xăng cộ hay cà phê ăn sáng hai bên tự tính với nhau, tùy lòng cảm ơn chủ xe.
            </p>

            <div class="tech-chips">
                <div class="chip chip-green">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                    <span>100% Phi thương mại</span>
                </div>
                <div class="chip chip-blue">
                    <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                    <span>Ghép hành lang PostGIS</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    <span>Laravel 13</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18M9 21V9"></path></svg>
                    <span>Filament 5</span>
                </div>
                <div class="chip">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Cộng đồng đi làm văn phòng</span>
                </div>
            </div>
        </section>

        <!-- Main Grid -->
        <div class="grid-2">
            <!-- Left Card: PostGIS Corridor Matching Window -->
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
                        Chủ xe đăng lịch trình đi làm cố định sáng T2–T6. Khi bạn tìm xe, hệ thống dùng <strong>PostGIS</strong> 
                        quét dọc trục đường xe chạy (độ lệch ≤ 2km) và kiểm tra đúng chiều xe đi để kết nối hai người.
                    </p>

                    <div class="route-stop">
                        <div class="stop-badge" style="background-color: #E0E7FF; color: #4338CA;">A</div>
                        <div>
                            <div class="stop-title">Điểm đón của bạn (Pickup)</div>
                            <div class="stop-addr">Lotte Mart Bình Dương (Đại lộ Bình Dương, Thuận An)</div>
                        </div>
                    </div>

                    <div class="route-stop">
                        <div class="stop-badge" style="background-color: #DCFCE7; color: #15803D;">B</div>
                        <div>
                            <div class="stop-title">Điểm đến làm việc (Dropoff)</div>
                            <div class="stop-addr">Ngã tư Hàng Xanh / Tòa nhà Bitexco Quận 1</div>
                        </div>
                    </div>

                    <button id="btnRunSearch" class="roomio-button" style="width: 100%; margin-top: 0.5rem; justify-content: center;">
                        <span>Thử tìm xe đi làm qua đoạn này</span>
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
                        <span>KẾT QUẢ TÌM THẤY:</span>
                        <span class="badge-tag badge-green">Cùng trục đường</span>
                    </div>

                    <div class="trip-item">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 800;">Nguyễn Minh Tuấn</h4>
                                <p style="font-size: 0.75rem; color: #52525B;">Toyota Vios G (Trắng) · Biển số: 61A-889.92</p>
                            </div>
                            <div style="text-align: right;">
                                <span class="badge-tag badge-green" style="font-size: 0.75rem;">Tiền xăng tùy tâm</span>
                                <div style="font-size: 0.7rem; color: #71717A; font-weight: 600; margin-top: 2px;">tự tính lúc đi chung</div>
                            </div>
                        </div>

                        <div style="font-size: 0.8rem; color: #27272A; line-height: 1.4; padding-top: 0.5rem; border-top: 1px dashed #E4E4E7;">
                            <div><strong>Xuất phát:</strong> 06:45 sáng (T2–T6) · Còn trống: <strong>2 ghế</strong></div>
                            <div><strong>Lộ trình:</strong> TT Hành chính Bình Dương ➔ QL13 ➔ Hàng Xanh ➔ Quận 1</div>
                            <div style="margin-top: 0.5rem;">
                                <button class="roomio-button" style="padding: 0.35rem 0.85rem; font-size: 0.8rem;" onclick="alert('Đã gửi lời chào hẹn đi chung đến tài xế Tuấn!')">
                                    <span>Nhắn hẹn đi cùng</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Community Etiquette & Legal Safety -->
            <div style="display: flex; flex-direction: column; gap: 1.75rem;">
                <div class="roomio-window">
                    <div class="roomio-window-bar">
                        <div class="roomio-window-dots">
                            <span class="roomio-window-dot dot-red"></span>
                            <span class="roomio-window-dot dot-yellow"></span>
                            <span class="roomio-window-dot dot-green"></span>
                        </div>
                        <div class="window-title">van-hoa-di-chung-xe.community</div>
                    </div>

                    <div class="etiquette-card">
                        <h3 style="font-size: 1.05rem; font-weight: 900; margin-bottom: 0.5rem;">Văn hóa đi nhờ xe văn minh</h3>
                        <p style="font-size: 0.825rem; color: #52525B; line-height: 1.5; margin-bottom: 1rem;">
                            Chủ xe là dân văn phòng tiện đường đón bạn cùng đi cho vui, không phải tài xế công nghệ kiếm sống. 
                            Hãy giữ thái độ lịch thiệp và tôn trọng nhau:
                        </p>

                        <div class="rule-item">
                            <span class="rule-num">1</span>
                            <div>
                                <div class="rule-title">Tiền xăng là tùy lòng hảo tâm</div>
                                <div class="rule-desc">
                                    Lúc lên xe hoặc tới nơi, bạn có thể gửi lại chút tiền xăng tượng trưng (ví dụ 20k–30k) 
                                    hoặc mời chủ xe ly cà phê sáng. Tuyệt đối không có chuyện app đòi tiền hay ép giá.
                                </div>
                            </div>
                        </div>

                        <div class="rule-item">
                            <span class="rule-num">2</span>
                            <div>
                                <div class="rule-title">Có mặt đúng giờ tại điểm hẹn</div>
                                <div class="rule-desc">
                                    Đến trước 3–5 phút ở điểm đón ven đường. Đừng để chủ xe phải dừng lâu gây kẹt xe 
                                    hoặc trễ giờ chấm công ở công ty.
                                </div>
                            </div>
                        </div>

                        <div class="rule-item">
                            <span class="rule-num">3</span>
                            <div>
                                <div class="rule-title">Giữ gìn không gian xe sạch sẽ</div>
                                <div class="rule-desc">
                                    Không mang đồ ăn có mùi nồng, không hút thuốc và đóng mở cửa nhẹ nhàng.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal peace of mind (Neo-Brutalist blue card) -->
                <div class="roomio-blue-card">
                    <div style="display: flex; gap: 0.65rem; align-items: center; margin-bottom: 0.5rem;">
                        <svg class="icon" viewBox="0 0 24 24" style="stroke: #1E40AF; width: 22px; height: 22px;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                        <h4 style="font-size: 1rem; font-weight: 900; color: #1E3A8A;">An tâm pháp lý tuyệt đối</h4>
                    </div>
                    <p style="font-size: 0.825rem; color: #1E3A8A; line-height: 1.55; font-weight: 500;">
                        Vì app <strong>không bán vé, không mở ví giữ tiền, không ấn định giá và không thu phí trung gian</strong>, 
                        đây hoàn toàn là việc đi nhờ xe tự nguyện giữa các cá nhân cùng công sở. 
                        Không một cơ quan nào có thể quy chụp là xe dù, bến cóc hay kinh doanh vận tải trái phép.
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
            btnRunSearch.innerHTML = '<span>Đang dò tìm tuyến xe cùng đường...</span>';

            setTimeout(function() {
                searchResultArea.style.display = 'block';
                btnRunSearch.innerHTML = '<span style="color: #15803D;">Đã tìm thấy chuyến xe phù hợp!</span>';
                btnRunSearch.style.backgroundColor = '#DCFCE7';
                btnRunSearch.disabled = false;
            }, 250);
        });
    </script>
</body>
</html>
