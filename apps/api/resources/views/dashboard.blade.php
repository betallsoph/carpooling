<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bảng điều khiển — carpooling.vn</title>
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

        /* App Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar (Roomio style) */
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-right: 2px solid #000000;
            padding: 1.5rem 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
        }

        @media (max-width: 900px) {
            .sidebar {
                display: none;
            }
        }

        .brand-link {
            text-decoration: none;
            color: #000000;
            display: block;
            margin-bottom: 2rem;
        }

        .brand-title {
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: -0.6px;
            line-height: 1;
        }

        .brand-title span {
            color: #2563eb;
        }

        .brand-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 800;
            background: var(--color-primary-blue);
            border: 1.5px solid #000000;
            border-radius: 4px;
            padding: 0.15rem 0.4rem;
            margin-top: 0.35rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #27272A;
            text-decoration: none;
            border-radius: 6px;
            border: 2px solid transparent;
            transition: all 120ms ease;
            cursor: pointer;
        }

        .nav-item:hover {
            background-color: #F4F4F5;
            border-color: #000000;
            color: #000000;
        }

        .nav-item.active {
            background-color: var(--color-primary-blue);
            border-color: #000000;
            color: #000000;
            box-shadow: var(--shadow-primary);
        }

        .user-card {
            border: 2px solid #000000;
            border-radius: 8px;
            background: #FFFFFF;
            padding: 1rem;
            box-shadow: var(--shadow-primary);
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 800;
            color: #09090B;
            margin-bottom: 0.2rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            font-size: 0.7rem;
            font-weight: 700;
            color: #52525B;
            margin-bottom: 0.75rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            overflow-y: auto;
        }

        @media (max-width: 640px) {
            .main-content {
                padding: 1rem;
            }
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .date-label {
            font-size: 0.85rem;
            color: #52525B;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .page-heading {
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Roomio exact Buttons */
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
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 800;
            color: #000000;
            box-shadow: var(--shadow-primary);
            text-decoration: none;
            transition: transform 80ms ease, box-shadow 80ms ease, background-color 150ms ease, color 150ms ease;
            position: relative;
            overflow: hidden;
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

        .roomio-button-white {
            display: inline-flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 800;
            color: #000000;
            box-shadow: var(--shadow-primary);
            text-decoration: none;
            transition: transform 80ms ease, box-shadow 80ms ease, background-color 150ms ease;
            user-select: none;
        }

        .roomio-button-white:hover {
            background-color: #F4F4F5;
        }

        .roomio-button-white:active {
            transform: translate(1px, 1px) !important;
            box-shadow: none !important;
        }

        /* Stats Ribbon (Single continuous strip, roomio style) */
        .stats-ribbon {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 2px solid #000000;
            border-radius: 8px;
            background: #FFFFFF;
            box-shadow: var(--shadow-secondary);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .stats-ribbon {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-block {
            padding: 1.25rem 1.5rem;
            border-right: 1px solid #E4E4E7;
        }

        .stat-block:last-child {
            border-right: none;
        }

        @media (max-width: 768px) {
            .stat-block:nth-child(2) {
                border-right: none;
            }
            .stat-block:nth-child(1), .stat-block:nth-child(2) {
                border-bottom: 1px solid #E4E4E7;
            }
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 800;
            color: #71717A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #09090B;
        }

        .stat-sub {
            font-size: 0.75rem;
            font-weight: 600;
            color: #15803D;
            margin-top: 0.25rem;
        }

        /* Underline Tabs (Roomio Style) */
        .tab-bar {
            display: flex;
            gap: 1.5rem;
            border-bottom: 2px solid #E4E4E7;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .tab-btn {
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            padding-bottom: 0.75rem;
            margin-bottom: -2px;
            font-size: 0.9rem;
            font-weight: 800;
            color: #71717A;
            cursor: pointer;
            transition: all 120ms ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: #000000;
        }

        .tab-btn.active {
            border-bottom-color: #000000;
            color: #2563eb;
        }

        /* Roomio Window Cards */
        .roomio-window {
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid #000000;
            background-color: #FFFFFF;
            box-shadow: var(--shadow-secondary);
            margin-bottom: 2rem;
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
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 1.5px solid #000000;
        }

        .dot-red { background-color: #EF4444; }
        .dot-yellow { background-color: #EAB308; }
        .dot-green { background-color: #22C55E; }

        .window-title {
            font-size: 0.8rem;
            font-weight: 800;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: #18181B;
        }

        .window-content {
            padding: 1.5rem;
        }

        /* Roomio Card */
        .roomio-card {
            border: 2px solid #000000;
            border-radius: 8px;
            background: #FFFFFF;
            box-shadow: var(--shadow-secondary);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Trip cards */
        .trip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.25rem;
        }

        .trip-card {
            border: 2px solid #000000;
            border-radius: 6px;
            background: #FFFFFF;
            padding: 1.25rem;
            box-shadow: var(--shadow-primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .trip-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .driver-name {
            font-size: 0.95rem;
            font-weight: 800;
        }

        .vehicle-info {
            font-size: 0.75rem;
            color: #52525B;
            font-weight: 600;
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

        .route-path {
            font-size: 0.8rem;
            color: #27272A;
            line-height: 1.45;
            padding: 0.75rem 0;
            border-top: 1px dashed #E4E4E7;
            border-bottom: 1px dashed #E4E4E7;
            margin: 0.5rem 0 1rem 0;
        }

        /* Booking Table */
        .table-wrapper {
            overflow-x: auto;
        }

        table.roomio-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.85rem;
        }

        table.roomio-table th {
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #000000;
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #F4F4F5;
        }

        table.roomio-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #E4E4E7;
            font-weight: 500;
        }

        table.roomio-table tr:hover td {
            background-color: #FAFAFA;
        }

        /* Roomio Form Fields (Exact Roomio Token Standard) */
        .roomio-form-group {
            margin-bottom: 1.25rem;
        }

        .roomio-form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 0.35rem;
        }

        .roomio-form-input {
            width: 100%;
            border: 2px solid #000000;
            border-radius: 6px;
            background-color: #FFFFFF;
            padding: 0.65rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #000000;
            font-family: inherit;
            outline: none;
            transition: all 120ms ease;
        }

        .roomio-form-input:focus {
            box-shadow: 0 0 0 3px #BFDBFE;
            border-color: #000000;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Day selector pills */
        .weekday-picker {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .day-chip {
            padding: 0.45rem 0.85rem;
            border: 2px solid #000000;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 800;
            background-color: #FFFFFF;
            color: #52525B;
            cursor: pointer;
            transition: all 100ms ease;
            user-select: none;
        }

        .day-chip.selected {
            background-color: var(--color-primary-blue);
            color: #000000;
            box-shadow: var(--shadow-primary);
        }

        /* Roomio Form Drawer (Slide-over) */
        .roomio-form-drawer-overlay {
            position: fixed;
            inset: 0;
            z-index: 999;
            display: none;
            justify-content: flex-end;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
        }

        .roomio-form-drawer-overlay.open {
            display: flex;
        }

        .roomio-form-drawer-panel {
            display: flex;
            height: 100%;
            width: 100%;
            max-width: 620px;
            flex-direction: column;
            overflow: hidden;
            border-left: 2px solid #000000;
            background: #FFFFFF;
            box-shadow: -4px 0px 0px 0px rgba(0,0,0,0.15);
            animation: drawerSlideIn 0.22s ease-out;
        }

        @keyframes drawerSlideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        .roomio-form-drawer-head {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid #000000;
            background-color: #F4F4F5;
            user-select: none;
        }

        .roomio-form-drawer-body {
            min-height: 0;
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .roomio-form-drawer-foot {
            display: flex;
            flex-shrink: 0;
            gap: 0.75rem;
            border-top: 2px solid #000000;
            padding: 1rem 1.5rem;
            background-color: #FAFAFA;
        }

        .close-btn {
            background: #FFFFFF;
            border: 2px solid #000000;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            cursor: pointer;
            transition: all 100ms;
        }

        .close-btn:hover {
            background: #FEE2E2;
            color: #991B1B;
        }

        .alert-success {
            border: 2px solid #000000;
            background-color: #DCFCE7;
            color: #166534;
            padding: 0.85rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.45;
        }

        /* Neo-brutalist Blue Notice */
        .roomio-blue-card {
            border-radius: 8px;
            border: 2px solid #000000;
            background-color: #DBEAFE;
            box-shadow: var(--shadow-secondary);
            padding: 1.5rem;
        }

        .blue-title {
            font-size: 1rem;
            font-weight: 900;
            color: #1E3A8A;
            margin-bottom: 0.5rem;
        }

        .blue-desc {
            font-size: 0.825rem;
            color: #1E3A8A;
            line-height: 1.55;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="roomio-grid-bg"></div>
    <div class="bg-overlay"></div>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div>
                <a href="/" class="brand-link">
                    <div class="brand-title">carpooling<span>.vn</span></div>
                    <div class="brand-badge">Commuter Carpooling</div>
                </a>

                <nav class="nav-menu">
                    <div class="nav-item active" onclick="switchTab('trips')">Tổng quan chuyến xe</div>
                    <div class="nav-item" onclick="openDrawer()">Đăng lịch trình xe</div>
                    <div class="nav-item" onclick="switchTab('bookings')">Yêu cầu ghép xe</div>
                    <a href="/" class="nav-item">Tìm xe trên bản đồ</a>
                    @if ($user->role === 'admin')
                        <a href="/admin" target="_blank" class="nav-item" style="color: #2563eb;">Quản trị Filament Ops</a>
                    @endif
                </nav>
            </div>

            <div class="user-card">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-role">
                    @if ($user->role === 'admin')
                        Quản trị viên hệ thống
                    @elseif ($user->role === 'driver')
                        Chủ xe văn phòng
                    @else
                        Hành khách đi nhờ xe
                    @endif
                    · {{ $user->email }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="roomio-button-white" style="width: 100%; padding: 0.4rem 0.75rem; font-size: 0.8rem;">
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div>
                    <div class="date-label">Hôm nay: {{ now()->locale('vi')->isoFormat('dddd, [ngày] D [tháng] M [năm] YYYY') }}</div>
                    <h1 class="page-heading">Xin chào, {{ $user->name }}</h1>
                </div>

                <div class="top-actions">
                    <a href="/" class="roomio-button-white">
                        <span>Về trang chủ</span>
                    </a>
                    <button class="roomio-button" onclick="openDrawer()">
                        <span>Đăng lịch trình xe</span>
                    </button>
                    @if ($user->role === 'admin')
                        <a href="/admin" target="_blank" class="roomio-button-white">
                            <span>Mở Filament Admin</span>
                        </a>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Ribbon (Continuous strip, roomio style) -->
            <div class="stats-ribbon">
                <div class="stat-block">
                    <div class="stat-label">Chuyến xe mở hôm nay</div>
                    <div class="stat-value">{{ $activeTripsCount }}</div>
                    <div class="stat-sub">Đang nhận người đi chung</div>
                </div>
                <div class="stat-block">
                    <div class="stat-label">Ghế trống sẵn sàng</div>
                    <div class="stat-value">{{ $availableSeatsCount }}</div>
                    <div class="stat-sub">Dọc hành lang công sở</div>
                </div>
                <div class="stat-block">
                    <div class="stat-label">Yêu cầu ghép xe</div>
                    <div class="stat-value">{{ $totalBookingsCount }}</div>
                    <div class="stat-sub">{{ $pendingBookingsCount }} yêu cầu mới</div>
                </div>
                <div class="stat-block">
                    <div class="stat-label">Tiền nền tảng thu</div>
                    <div class="stat-value">0đ</div>
                    <div class="stat-sub" style="color: #2563eb;">100% Phi thương mại</div>
                </div>
            </div>

            <!-- Underline Tabs (Roomio Style) -->
            <div class="tab-bar">
                <button id="tabBtnTrips" class="tab-btn active" onclick="switchTab('trips')">
                    Chuyến xe đi làm ({{ $trips->count() }})
                </button>
                <button id="tabBtnForm" class="tab-btn" onclick="switchTab('form')">
                    Đăng lịch trình xe mới (Form Roomio)
                </button>
                <button id="tabBtnBookings" class="tab-btn" onclick="switchTab('bookings')">
                    Yêu cầu ghép xe ({{ $bookings->count() }})
                </button>
            </div>

            <!-- TAB 1: Live Trip Instances -->
            <div id="sectionTrips" class="roomio-window">
                <div class="roomio-window-bar">
                    <div class="roomio-window-dots">
                        <span class="roomio-window-dot dot-red"></span>
                        <span class="roomio-window-dot dot-yellow"></span>
                        <span class="roomio-window-dot dot-green"></span>
                    </div>
                    <div class="window-title">chuyen-xe-di-lam-hom-nay.live</div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #16A34A;">● Trực tiếp</div>
                </div>

                <div class="window-content">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                        <h3 style="font-size: 1.05rem; font-weight: 900;">Chuyến xe dọc tuyến đi làm sáng nay</h3>
                        <button class="roomio-button" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;" onclick="openDrawer()">
                            <span>Thêm chuyến xe</span>
                        </button>
                    </div>

                    <div class="trip-grid">
                        @forelse ($trips as $trip)
                            <div class="trip-card">
                                <div>
                                    <div class="trip-header">
                                        <div>
                                            <div class="driver-name">{{ $trip->driverProfile->user->name ?? 'Tài xế cộng đồng' }}</div>
                                            <div class="vehicle-info">
                                                {{ $trip->vehicle->make ?? 'Ô tô' }} {{ $trip->vehicle->model ?? '' }} · Biển số: <strong>{{ $trip->vehicle->plate_number ?? '61A-889.92' }}</strong>
                                            </div>
                                        </div>
                                        <span class="badge-tag badge-green">Tiền xăng tùy tâm</span>
                                    </div>

                                    <div class="route-path">
                                        <div><strong>Xuất phát:</strong> {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }} sáng</div>
                                        <div><strong>Từ:</strong> {{ $trip->origin_name }}</div>
                                        <div><strong>Đến:</strong> {{ $trip->destination_name }}</div>
                                        <div style="margin-top: 0.35rem; font-weight: 700; color: #15803D;">
                                            Còn trống {{ $trip->available_seats }} ghế trên xe
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                                    <button class="roomio-button" style="flex: 1; padding: 0.45rem 0.85rem; font-size: 0.8rem;" onclick="alert('Đã gửi thông báo hẹn đi cùng đến chủ xe!')">
                                        <span>Nhắn hẹn đi cùng</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #71717A; font-weight: 600;">
                                Hiện chưa có chuyến xe nào được đăng. Hãy bấm "Đăng lịch trình xe" ở góc trên để tạo chuyến đầu tiên!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 2: Inline Form (Form Roomio Chuẩn) -->
            <div id="sectionForm" class="roomio-card" style="display: none;">
                <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #000000; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 1.2rem; font-weight: 900;">Đăng lịch trình xe đi làm (T2–T6)</h3>
                        <p style="font-size: 0.8rem; color: #52525B; font-weight: 600; margin-top: 0.25rem;">
                            Chủ xe mở ghế trống cho đồng nghiệp đi nhờ dọc đường. 100% phi thương mại, không thu tiền trên app.
                        </p>
                    </div>
                    <span class="badge-tag badge-green">Form chuẩn Roomio</span>
                </div>

                <form action="{{ route('dashboard.trips.store') }}" method="POST">
                    @csrf

                    <div class="form-grid-2">
                        <div class="roomio-form-group">
                            <label for="inline_origin_name" class="roomio-form-label">Điểm đón xuất phát (Origin)</label>
                            <input 
                                id="inline_origin_name" 
                                name="origin_name" 
                                type="text" 
                                class="roomio-form-input" 
                                required 
                                placeholder="Ví dụ: TT Hành chính Bình Dương, Thủ Dầu Một"
                                value="{{ old('origin_name', 'TT Hành chính Bình Dương (Thủ Dầu Một)') }}"
                            >
                        </div>

                        <div class="roomio-form-group">
                            <label for="inline_destination_name" class="roomio-form-label">Điểm đến làm việc (Destination)</label>
                            <input 
                                id="inline_destination_name" 
                                name="destination_name" 
                                type="text" 
                                class="roomio-form-input" 
                                required 
                                placeholder="Ví dụ: Ngã tư Hàng Xanh / Tòa nhà Bitexco Quận 1"
                                value="{{ old('destination_name', 'Tòa nhà Bitexco / Quận 1 TP.HCM') }}"
                            >
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="roomio-form-group">
                            <label for="inline_departure_time" class="roomio-form-label">Giờ xuất phát buổi sáng</label>
                            <input 
                                id="inline_departure_time" 
                                name="departure_time" 
                                type="time" 
                                class="roomio-form-input" 
                                required 
                                value="{{ old('departure_time', '06:45') }}"
                            >
                        </div>

                        <div class="roomio-form-group">
                            <label for="inline_available_seats" class="roomio-form-label">Số ghế trống mở cho đi nhờ</label>
                            <select id="inline_available_seats" name="available_seats" class="roomio-form-input">
                                <option value="1">1 ghế trống</option>
                                <option value="2">2 ghế trống</option>
                                <option value="3" selected>3 ghế trống</option>
                                <option value="4">4 ghế trống</option>
                            </select>
                        </div>
                    </div>

                    <div class="roomio-form-group">
                        <label class="roomio-form-label">Ngày đi cố định trong tuần</label>
                        <div class="weekday-picker">
                            <div class="day-chip selected">Thứ Hai</div>
                            <div class="day-chip selected">Thứ Ba</div>
                            <div class="day-chip selected">Thứ Tư</div>
                            <div class="day-chip selected">Thứ Năm</div>
                            <div class="day-chip selected">Thứ Sáu</div>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="roomio-form-group">
                            <label for="inline_vehicle_model" class="roomio-form-label">Dòng xe ô tô</label>
                            <input 
                                id="inline_vehicle_model" 
                                name="vehicle_model" 
                                type="text" 
                                class="roomio-form-input" 
                                placeholder="Ví dụ: Toyota Vios G 2023"
                                value="{{ old('vehicle_model', 'Toyota Vios G 2023') }}"
                            >
                        </div>

                        <div class="roomio-form-group">
                            <label for="inline_vehicle_plate" class="roomio-form-label">Biển số xe</label>
                            <input 
                                id="inline_vehicle_plate" 
                                name="vehicle_plate" 
                                type="text" 
                                class="roomio-form-input" 
                                placeholder="Ví dụ: 61A-889.92"
                                value="{{ old('vehicle_plate', '61A-889.92') }}"
                            >
                        </div>
                    </div>

                    <div style="background-color: #F4F4F5; border: 2px solid #000000; border-radius: 6px; padding: 1rem; margin-bottom: 1.5rem;">
                        <div style="font-size: 0.825rem; font-weight: 800; color: #18181B; margin-bottom: 0.25rem;">
                            Văn hóa chia sẻ chi phí xăng dầu:
                        </div>
                        <div style="font-size: 0.8rem; color: #52525B; line-height: 1.45; font-weight: 500;">
                            Hệ thống <strong>không can thiệp thu tiền hay mở ví điện tử</strong>. Người đi nhờ tự nguyện gửi chút tiền xăng (hoặc mời ly cà phê) trực tiếp cho chủ xe lúc đi chung.
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                        <button type="button" class="roomio-button-white" onclick="switchTab('trips')">
                            <span>Hủy bỏ</span>
                        </button>
                        <button type="submit" class="roomio-button">
                            <span>Lưu và mở chuyến xe</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: Bookings Queue -->
            <div id="sectionBookings" class="roomio-window" style="display: none;">
                <div class="roomio-window-bar">
                    <div class="roomio-window-dots">
                        <span class="roomio-window-dot dot-red"></span>
                        <span class="roomio-window-dot dot-yellow"></span>
                        <span class="roomio-window-dot dot-green"></span>
                    </div>
                    <div class="window-title">yeu-cau-ghep-chuyen.queue</div>
                </div>

                <div class="window-content" style="padding: 0;">
                    <div class="table-wrapper">
                        <table class="roomio-table">
                            <thead>
                                <tr>
                                    <th>Hành khách</th>
                                    <th>Điểm đón (Pickup)</th>
                                    <th>Điểm đến (Dropoff)</th>
                                    <th>Số ghế</th>
                                    <th>Trạng thái</th>
                                    <th>Lời nhắn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $b)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 800;">{{ $b->passenger->name ?? 'Người đi nhờ' }}</div>
                                            <div style="font-size: 0.75rem; color: #71717A;">{{ $b->passenger->phone ?? '0938***' }}</div>
                                        </td>
                                        <td>{{ $b->pickup_name }}</td>
                                        <td>{{ $b->dropoff_name }}</td>
                                        <td><strong>{{ $b->seats_requested }} ghế</strong></td>
                                        <td>
                                            @if ($b->status === 'accepted')
                                                <span class="badge-tag badge-green">Đã duyệt</span>
                                            @elseif ($b->status === 'requested')
                                                <span class="badge-tag">Chờ xác nhận</span>
                                            @else
                                                <span class="badge-tag" style="background: #F4F4F5;">{{ $b->status }}</span>
                                            @endif
                                        </td>
                                        <td style="color: #52525B; font-style: italic;">
                                            "{{ $b->note ?? 'Không có ghi chú' }}"
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 2rem; color: #71717A;">
                                            Chưa có yêu cầu ghép xe nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Blue Notice: Community principles -->
            <div class="roomio-blue-card">
                <h4 class="blue-title">Văn hóa đi nhờ xe văn minh & An tâm pháp lý</h4>
                <p class="blue-desc">
                    Tất cả các chuyến đi kết nối trên <strong>carpooling.vn</strong> là sự sẻ chia tự nguyện giữa những người cùng công sở, cùng trục đường. 
                    Nền tảng <strong>tuyệt đối không thu phí trung gian, không ấn định giá vé và không trung chuyển tiền</strong>. 
                    Tiền hỗ trợ xăng cộ hay bữa sáng do hai bên tự tính với nhau trực tiếp khi gặp mặt.
                </p>
            </div>
        </main>
    </div>

    <!-- Roomio Form Drawer (Slide-over panel, exact roomio standard) -->
    <div id="drawerOverlay" class="roomio-form-drawer-overlay" onclick="closeDrawer(event)">
        <div class="roomio-form-drawer-panel" onclick="event.stopPropagation()">
            <div class="roomio-form-drawer-head">
                <span style="font-size: 1.05rem; font-weight: 900; color: #000000;">Đăng lịch trình xe đi làm (T2–T6)</span>
                <button class="close-btn" onclick="closeDrawer(event)" aria-label="Đóng">✕</button>
            </div>

            <form action="{{ route('dashboard.trips.store') }}" method="POST" style="display: flex; flex-direction: column; height: 100%; min-height: 0;">
                @csrf

                <div class="roomio-form-drawer-body">
                    <div class="roomio-form-group">
                        <label for="drawer_origin_name" class="roomio-form-label">Điểm xuất phát đón khách (Origin)</label>
                        <input 
                            id="drawer_origin_name" 
                            name="origin_name" 
                            type="text" 
                            class="roomio-form-input" 
                            required 
                            placeholder="Ví dụ: TT Hành chính Bình Dương (Thủ Dầu Một)"
                            value="TT Hành chính Bình Dương"
                        >
                    </div>

                    <div class="roomio-form-group">
                        <label for="drawer_destination_name" class="roomio-form-label">Điểm đến làm việc (Destination)</label>
                        <input 
                            id="drawer_destination_name" 
                            name="destination_name" 
                            type="text" 
                            class="roomio-form-input" 
                            required 
                            placeholder="Ví dụ: Tòa nhà Bitexco / Quận 1"
                            value="Bitexco Financial Tower, Quận 1"
                        >
                    </div>

                    <div class="form-grid-2">
                        <div class="roomio-form-group">
                            <label for="drawer_departure_time" class="roomio-form-label">Giờ xuất phát sáng</label>
                            <input 
                                id="drawer_departure_time" 
                                name="departure_time" 
                                type="time" 
                                class="roomio-form-input" 
                                required 
                                value="06:45"
                            >
                        </div>

                        <div class="roomio-form-group">
                            <label for="drawer_available_seats" class="roomio-form-label">Số ghế mở đi chung</label>
                            <select id="drawer_available_seats" name="available_seats" class="roomio-form-input">
                                <option value="1">1 ghế</option>
                                <option value="2">2 ghế</option>
                                <option value="3" selected>3 ghế</option>
                                <option value="4">4 ghế</option>
                            </select>
                        </div>
                    </div>

                    <div class="roomio-form-group">
                        <label class="roomio-form-label">Các ngày đi làm cố định</label>
                        <div class="weekday-picker">
                            <div class="day-chip selected">T2</div>
                            <div class="day-chip selected">T3</div>
                            <div class="day-chip selected">T4</div>
                            <div class="day-chip selected">T5</div>
                            <div class="day-chip selected">T6</div>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="roomio-form-group">
                            <label for="drawer_vehicle_model" class="roomio-form-label">Dòng xe</label>
                            <input 
                                id="drawer_vehicle_model" 
                                name="vehicle_model" 
                                type="text" 
                                class="roomio-form-input" 
                                value="Toyota Vios G 2023"
                            >
                        </div>

                        <div class="roomio-form-group">
                            <label for="drawer_vehicle_plate" class="roomio-form-label">Biển số</label>
                            <input 
                                id="drawer_vehicle_plate" 
                                name="vehicle_plate" 
                                type="text" 
                                class="roomio-form-input" 
                                value="61A-889.92"
                            >
                        </div>
                    </div>

                    <div style="background-color: #F4F4F5; border: 2px solid #000000; border-radius: 6px; padding: 0.85rem; font-size: 0.8rem; color: #52525B;">
                        <strong>Quy tắc phi thương mại:</strong> Nền tảng không thu phí. Tiền xăng cộ là tự nguyện giữa chủ xe và người đi nhờ khi lên xe.
                    </div>
                </div>

                <div class="roomio-form-drawer-foot">
                    <button type="button" class="roomio-button-white" style="flex: 1;" onclick="closeDrawer(event)">
                        <span>Hủy</span>
                    </button>
                    <button type="submit" class="roomio-button" style="flex: 2;">
                        <span>Lưu và mở chuyến xe</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Roomio ripple hover effect
        document.addEventListener('pointerover', function(event) {
            const action = event.target.closest('.roomio-button, .roomio-button-white');
            if (!action) return;
            const bounds = action.getBoundingClientRect();
            action.style.setProperty('--hover-x', `${event.clientX - bounds.left}px`);
            action.style.setProperty('--hover-y', `${event.clientY - bounds.top}px`);
        });

        // Tab Switching
        function switchTab(tab) {
            document.getElementById('sectionTrips').style.display = tab === 'trips' ? 'block' : 'none';
            document.getElementById('sectionForm').style.display = tab === 'form' ? 'block' : 'none';
            document.getElementById('sectionBookings').style.display = tab === 'bookings' ? 'block' : 'none';

            document.getElementById('tabBtnTrips').classList.toggle('active', tab === 'trips');
            document.getElementById('tabBtnForm').classList.toggle('active', tab === 'form');
            document.getElementById('tabBtnBookings').classList.toggle('active', tab === 'bookings');
        }

        // Roomio Drawer Controls
        function openDrawer() {
            document.getElementById('drawerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer(event) {
            if (event) event.preventDefault();
            document.getElementById('drawerOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Weekday toggle chips
        document.querySelectorAll('.day-chip').forEach(function(chip) {
            chip.addEventListener('click', function() {
                chip.classList.toggle('selected');
            });
        });
    </script>
</body>
</html>
