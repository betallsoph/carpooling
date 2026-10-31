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
                    <a href="/dashboard" class="nav-item active">Tổng quan</a>
                    <a href="#trips" class="nav-item">Chuyến xe đi làm</a>
                    <a href="#bookings" class="nav-item">Yêu cầu ghép xe</a>
                    <a href="/" class="nav-item">Tìm chuyến trên bản đồ</a>
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
                    @if ($user->role === 'admin')
                        <a href="/admin" target="_blank" class="roomio-button">
                            <span>Mở Filament Admin</span>
                        </a>
                    @else
                        <button class="roomio-button" onclick="alert('Tính năng đăng lịch trình xe sáng chiều đang mở trong đợt thử nghiệm!')">
                            <span>Đăng lịch trình xe</span>
                        </button>
                    @endif
                </div>
            </div>

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

            <!-- Window 1: Live Trip Instances -->
            <div id="trips" class="roomio-window">
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
                        <span class="badge-tag">{{ $trips->count() }} chuyến hoạt động</span>
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
                                Hiện chưa có chuyến xe nào được đăng cho ngày hôm nay.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Window 2: Bookings & Matching Requests -->
            <div id="bookings" class="roomio-window">
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

    <script>
        // Roomio ripple hover effect
        document.addEventListener('pointerover', function(event) {
            const action = event.target.closest('.roomio-button, .roomio-button-white');
            if (!action) return;
            const bounds = action.getBoundingClientRect();
            action.style.setProperty('--hover-x', `${event.clientX - bounds.left}px`);
            action.style.setProperty('--hover-y', `${event.clientY - bounds.top}px`);
        });
    </script>
</body>
</html>
