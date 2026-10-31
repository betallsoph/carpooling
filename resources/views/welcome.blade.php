<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpooling Việt Nam — Nền tảng chia sẻ chi phí đi làm văn phòng</title>
    <meta name="description" content="Nền tảng Carpooling đi làm văn phòng lặp theo hành lang (Commute) tại Việt Nam. Tiết kiệm, minh bạch, đúng luật NĐ 218/2026.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0B0F19;
            --card-bg: rgba(17, 24, 39, 0.75);
            --card-border: rgba(255, 255, 255, 0.08);
            --emerald-glow: #10B981;
            --accent-cyan: #06B6D4;
            --text-main: #F3F4F6;
            --text-muted: #9CA3AF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(16, 185, 129, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(6, 182, 212, 0.1) 0%, transparent 45%);
            background-attachment: fixed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #10B981 0%, #06B6D4 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #042f2e;
            font-size: 22px;
            font-weight: 800;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.35);
        }

        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #F3F4F6, #9CA3AF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            transform: translateY(-1px);
        }

        .hero {
            padding: 4rem 0 3rem;
            text-align: center;
            max-width: 820px;
            margin: 0 auto;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #34D399;
            font-size: 0.775rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            background-color: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10B981;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            letter-spacing: -1px;
        }

        h1 span.highlight {
            background: linear-gradient(135deg, #34D399 0%, #38BDF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.125rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .stack-chips {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 3.5rem;
        }

        .chip {
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.8rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--card-border);
            color: #D1D5DB;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .chip b {
            color: #34D399;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 900px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            h1 {
                font-size: 2.2rem;
            }
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 2rem;
            backdrop-filter: blur(16px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .status-pill {
            font-size: 0.75rem;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .demo-box {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1rem;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: rgba(255, 255, 255, 0.1);
        }

        .timeline-item {
            position: relative;
        }

        .timeline-dot {
            position: absolute;
            left: -1.5rem;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #10B981;
            background: #0B0F19;
        }

        .timeline-dot.dest {
            border-color: #06B6D4;
        }

        .timeline-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .timeline-val {
            font-size: 0.95rem;
            font-weight: 600;
            color: #F3F4F6;
        }

        .qr-preview {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid var(--card-border);
            margin-top: 1.25rem;
        }

        .qr-preview img {
            width: 90px;
            height: 90px;
            border-radius: 8px;
            background: white;
            padding: 4px;
        }

        .qr-info h4 {
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .qr-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .price-tag {
            color: #34D399;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .code-snippet {
            background: #060911;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 1rem;
            font-family: ui-monospace, monospace;
            font-size: 0.8rem;
            color: #94A3B8;
            overflow-x: auto;
            margin-top: 1rem;
        }

        .code-snippet span.kw { color: #F43F5E; }
        .code-snippet span.fn { color: #38BDF8; }
        .code-snippet span.str { color: #34D399; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo-wrap">
                <div class="logo-icon">🚗</div>
                <div>
                    <div class="logo-text">Carpooling VN</div>
                    <small style="color: #6B7280; font-size: 0.72rem;">Laravel 13 · PostGIS · Commute Platform</small>
                </div>
            </div>

            <div class="nav-links">
                <a href="/horizon" target="_blank" class="btn btn-outline">
                    <span style="color:#A78BFA;">⚡</span> Horizon Queue
                </a>
                <a href="/admin" target="_blank" class="btn btn-primary">
                    <span>🛡️</span> Admin Filament
                </a>
            </div>
        </header>

        <section class="hero">
            <div class="badge-pill">
                <span class="badge-dot"></span>
                Đúng Luật NĐ 218/2026 · Chia sẻ chi phí thuần túy · Không bán vé gom khách
            </div>

            <h1>Đi chung xe đi làm <br><span class="highlight">Tiết kiệm, Văn minh, Hợp pháp</span></h1>

            <p class="hero-desc">
                Hệ thống ghép chuyến đi làm lặp theo hành lang (Commute) cố định. Ứng dụng thuật toán không gian 
                <strong>PostgreSQL PostGIS</strong> tìm kiếm dọc corridor, tuyệt đối không spam API Matrix tốn kém.
                Đóng góp chi phí xăng cộ trực tiếp qua <strong>VietQR NAPAS 247</strong>.
            </p>

            <div class="stack-chips">
                <div class="chip">🚀 Laravel <b>13.39</b></div>
                <div class="chip">🐘 PHP <b>8.5.12</b></div>
                <div class="chip">🗺️ PostgreSQL <b>16 + PostGIS 3.4</b></div>
                <div class="chip">⚡ Redis <b>Horizon</b></div>
                <div class="chip">🎛️ Filament <b>5.11</b></div>
                <div class="chip">📱 Sanctum <b>REST API</b></div>
            </div>
        </section>

        <div class="grid-2">
            <!-- Thẻ 1: Demo Ghép Chuyến PostGIS Thực Tế -->
            <div class="card">
                <div class="card-title">
                    <span>📍 Mô phỏng Ghép chuyến Hành lang (Corridor)</span>
                    <span class="status-pill">Đã kiểm nghiệm</span>
                </div>
                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">
                    Chủ xe chạy tuyến Bình Dương ➔ Quận 1 sáng T2-T6. Hành khách tìm kiếm tại Lotte Mart Lái Thiêu ➔ Hàng Xanh. PostGIS tự động nhận diện điểm đón nằm dọc tuyến và cùng hướng đi.
                </p>

                <div class="demo-box">
                    <div class="timeline">
                        <div class="timeline-item">
                            <span class="timeline-dot"></span>
                            <div class="timeline-label">Tuyến chính của Chủ xe</div>
                            <div class="timeline-val">Thủ Dầu Một ➔ Nhà Hát Thành Phố (34.5 km)</div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot dest"></span>
                            <div class="timeline-label">Điểm đón của Khách (Trong Buffer 2km)</div>
                            <div class="timeline-val">Lotte Mart Bình Dương ➔ Ngã 4 Hàng Xanh</div>
                        </div>
                    </div>
                </div>

                <div class="code-snippet">
<span class="kw">SELECT</span> * <span class="kw">FROM</span> trip_instances
<span class="kw">WHERE</span> <span class="fn">ST_DWithin</span>(route_line::geography, pickup_point, 2000)
  <span class="kw">AND</span> <span class="fn">ST_LineLocatePoint</span>(route_line, pickup) &lt; <span class="fn">ST_LineLocatePoint</span>(route_line, dropoff);
                </div>
            </div>

            <!-- Thẻ 2: VietQR & Minh Bạch Chi Phí -->
            <div class="card">
                <div class="card-title">
                    <span>💳 Minh bạch Chi phí & VietQR</span>
                    <span class="status-pill">Không Giữ Tiền Hộ</span>
                </div>
                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">
                    Công thức: <code style="color:#34D399;">(km × đơn giá NL + toll) / (1 chủ xe + n khách)</code>. Tiền xăng cộ được chuyển khoản trực tiếp qua tài khoản ngân hàng của chủ xe.
                </p>

                <div class="qr-preview">
                    <img src="https://img.vietqr.io/image/VCB-1023456789-compact2.png?amount=38000&addInfo=CP000001&accountName=NGUYEN%20MINH%20TUAN" alt="VietQR Demo">
                    <div class="qr-info">
                        <h4>Nguyễn Minh Tuấn (VCB)</h4>
                        <p>Chuyến: Bình Dương ➔ Q1 · Xe: Toyota Vios</p>
                        <p>Số tiền đóng góp: <span class="price-tag">38,000 đ</span></p>
                        <p style="font-size: 0.75rem; color: #10B981; margin-top: 4px;">Nội dung CK: <strong>CP000001</strong></p>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                    <a href="/admin/login" class="btn btn-outline" style="flex: 1; justify-content: center; font-size: 0.8rem;">
                        Tài khoản Admin: <b>admin@carpooling.vn</b> / <b>password</b>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
