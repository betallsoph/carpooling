# carpooling.vn

> **Nền tảng kết nối đi chung xe văn phòng 100% phi thương mại tại Việt Nam.**  
> *"Tiện đường thì đi chung, tiền xăng tùy lòng."*

---

## 1. Giới thiệu dự án

`carpooling.vn` là giải pháp công nghệ kết nối các nhân viên văn phòng, người đi làm có cùng cung đường di chuyển hàng ngày (commuter carpooling). Hệ thống giúp tận dụng các ghế trống trên ô tô cá nhân vào giờ cao điểm sáng và chiều, giảm thiểu ùn tắc giao thông, tiết kiệm chi phí nhiên liệu và bảo vệ môi trường.

### Tinh thần & Pháp lý cốt lõi:
- **100% Phi thương mại**: Nền tảng **không thu phí trung gian, không ấn định giá vé, không mở ví điện tử giữ tiền**.
- **Tiền xăng tùy lòng**: Người đi nhờ tự nguyện chia sẻ một phần chi phí nhiên liệu, phí cầu đường (hoặc mời cà phê) trực tiếp với chủ xe lúc đi chung.
- **An tâm pháp lý**: Hoàn toàn là hành vi đi nhờ xe tự nguyện giữa các cá nhân công sở, không phát sinh giao dịch kinh doanh vận tải hành khách trái phép theo quy định pháp luật Việt Nam.
- **Văn hóa văn minh**: Tôn trọng không gian riêng của chủ xe, có mặt đúng giờ hẹn và giữ gìn xe sạch sẽ.

---

## 2. Công nghệ sử dụng (Tech Stack)

- **Ngôn ngữ & Runtime**: PHP 8.5+
- **Backend Framework**: Laravel 13.x
- **Bảng điều khiển quản trị (Admin Panel)**: [Filament v5](https://filamentphp.com) (quản lý tài xế, phương tiện, chuyến đi, báo cáo)
- **Cơ sở dữ liệu & Không gian địa lý (GIS)**: 
  - **PostgreSQL 16 + PostGIS**: Xử lý dữ liệu không gian, tuyến đường xe chạy dạng `Geometry(LineString, 4326)`.
  - Hỗ trợ fallback **SQLite** cho môi trường phát triển giao diện nhanh (Local UI Dev).
- **Hàng đợi & Giám sát**: Laravel Horizon + Redis.
- **Xác thực API**: Laravel Sanctum (Token-based authentication).
- **Giao diện người dùng (UI)**: Neo-brutalist Design System kế thừa phong cách `roomio` (viền đen sắc nét, bóng đổ cứng `2px 2px 0px -1px #000`, hiệu ứng tương tác mượt mà, hoàn toàn không dùng emoji).

---

## 3. Thuật toán ghép hành lang (Corridor Matching)

Khác với các ứng dụng taxi/xe ôm công nghệ tìm tài xế theo bán kính tròn xung quanh điểm đón, `carpooling.vn` sử dụng thuật toán **ghép tuyến hành lang (Corridor Search)** với PostGIS:

1. **Quét độ lệch hành lang (`ST_DWithin`)**: Tìm các chuyến xe có lộ trình đi qua điểm đón (pickup) và điểm trả (dropoff) của hành khách trong phạm vi cho phép (mặc định ≤ 2km):
   ```sql
   ST_DWithin(route_line::geography, ST_SetSRID(ST_Point(lon, lat), 4326)::geography, 2000)
   ```
2. **Kiểm tra đúng chiều di chuyển (`ST_LineLocatePoint`)**: Xác định vị trí tương đối trên tuyến đường để đảm bảo điểm đón phải diễn ra **trước** điểm trả trên cùng chiều xe chạy:
   ```sql
   ST_LineLocatePoint(route_line, ST_SetSRID(ST_Point(p_lon, p_lat), 4326)) 
   < 
   ST_LineLocatePoint(route_line, ST_SetSRID(ST_Point(d_lon, d_lat), 4326))
   ```

---

## 4. Cài đặt và Chạy thử (Local Development)

### Yêu cầu tiên quyết:
- PHP >= 8.5
- Composer >= 2.x
- Docker & Docker Compose (tùy chọn khi cần chạy PostGIS + Redis)

### Các bước cài đặt:

1. **Clone repository:**
   ```bash
   git clone https://github.com/betallsoph/carpooling.git
   cd carpooling
   ```

2. **Cài đặt các gói phụ thuộc (Dependencies):**
   ```bash
   composer install
   ```

3. **Thiết lập biến môi trường:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Chạy Migration và Seed dữ liệu mẫu:**
   - *Chế độ SQLite (nhanh, kiểm thử giao diện & API cơ bản):*
     ```bash
     touch database/database.sqlite
     php artisan migrate --seed
     ```
   - *Chế độ Docker PostGIS & Redis:*
     ```bash
     docker compose up -d
     php artisan migrate --seed
     ```

5. **Khởi chạy ứng dụng:**
   ```bash
   php artisan serve
   ```
   Ứng dụng sẽ chạy tại: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 5. Tài khoản & Địa chỉ truy cập

- **Trang chủ & Mô phỏng ghép xe**: `http://127.0.0.1:8000`
- **Bảng điều khiển quản trị (Filament Admin)**: `http://127.0.0.1:8000/admin`
  - **Email**: `admin@carpooling.vn`
  - **Mật khẩu**: `password`
- **Giám sát hàng đợi (Laravel Horizon)**: `http://127.0.0.1:8000/horizon`

---

## 6. Cấu trúc thư mục trọng tâm

```text
├── app/
│   ├── Filament/Resources/       # Quản trị tài xế, chuyến đi, xe, báo cáo vi phạm
│   ├── Http/Controllers/Api/     # REST API (tìm chuyến xe, gửi lời chào hẹn đi cùng)
│   └── Models/
│       ├── DriverProfile.php     # Hồ sơ bằng lái, giấy tờ xe
│       ├── TripTemplate.php      # Lịch trình mẫu cố định sáng/chiều T2–T6
│       ├── TripInstance.php      # Chuyến xe cụ thể & logic PostGIS ST_DWithin
│       └── Booking.php           # Trạng thái ghép chuyến (requested, accepted, completed)
├── database/
│   ├── migrations/               # Schema PostGIS geometry, quan hệ dữ liệu
│   └── seeders/                  # Dữ liệu tài xế, chuyến xe và tài khoản Admin mẫu
├── resources/views/
│   └── welcome.blade.php         # Landing page phong cách roomio neo-brutalist
└── routes/
    ├── api.php                   # Endpoints tra cứu hành lang và kết nối
    └── web.php                   # Routes điều hướng giao diện
```

---

## 7. Giấy phép (License)

Dự án được phát triển dưới giấy phép mã nguồn mở [MIT License](LICENSE).
