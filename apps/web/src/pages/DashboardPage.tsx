import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { createTrip, fetchBookings, fetchStats, fetchTrips, type Booking, type Stats, type Trip } from '../lib/api';

export const DashboardPage: React.FC = () => {
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState<'trips' | 'form' | 'bookings'>('trips');
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const [trips, setTrips] = useState<Trip[]>([]);
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [stats, setStats] = useState<Stats>({
    active_trips_count: 2,
    available_seats_count: 5,
    total_bookings_count: 3,
    pending_bookings_count: 1,
    platform_fee: 0,
  });
  const [selectedDays, setSelectedDays] = useState<string[]>(['T2', 'T3', 'T4', 'T5', 'T6']);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  // Form states
  const [originName, setOriginName] = useState('TT Hành chính Bình Dương (Thủ Dầu Một)');
  const [destinationName, setDestinationName] = useState('Tòa nhà Bitexco / Quận 1 TP.HCM');
  const [departureTime, setDepartureTime] = useState('06:45');
  const [availableSeats, setAvailableSeats] = useState(3);
  const [vehicleModel, setVehicleModel] = useState('Toyota Vios G 2023');
  const [vehiclePlate, setVehiclePlate] = useState('61A-889.92');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const currentUser = JSON.parse(localStorage.getItem('auth_user') || '{"name":"Quản trị viên Hệ thống","email":"admin@carpooling.vn","role":"admin"}');

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    const [t, s, b] = await Promise.all([fetchTrips(), fetchStats(), fetchBookings()]);
    setTrips(t);
    setStats(s);
    setBookings(b);
  };

  const toggleDay = (day: string) => {
    setSelectedDays(prev => 
      prev.includes(day) ? prev.filter(d => d !== day) : [...prev, day]
    );
  };

  const handleCreateTrip = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    setSuccessMessage(null);

    try {
      const newTrip = await createTrip({
        origin_name: originName,
        destination_name: destinationName,
        departure_time: departureTime,
        available_seats: availableSeats,
        vehicle_model: vehicleModel,
        vehicle_plate: vehiclePlate,
      });

      setTrips(prev => [newTrip, ...prev]);
      setStats(prev => ({
        ...prev,
        active_trips_count: prev.active_trips_count + 1,
        available_seats_count: prev.available_seats_count + availableSeats,
      }));

      setIsDrawerOpen(false);
      setActiveTab('trips');
      setSuccessMessage('Đã đăng lịch trình xe đi làm thành công! Tuyến đường đã sẵn sàng để đồng nghiệp cùng trục hẹn đi nhờ.');
    } catch {
      // Local fallback for offline mode
      const localTrip: Trip = {
        id: Date.now(),
        driver_profile_id: 1,
        vehicle_id: 1,
        date: '2026-11-02',
        departure_time: `${departureTime}:00`,
        status: 'scheduled',
        origin_name: originName,
        origin_address: originName,
        destination_name: destinationName,
        destination_address: destinationName,
        total_distance_km: 30.0,
        estimated_duration_minutes: 50,
        total_seats: availableSeats,
        available_seats: availableSeats,
        driver_profile: {
          id: 1,
          license_number: '790123456789',
          rating_avg: 5.0,
          rating_count: 1,
          user: currentUser,
        },
        vehicle: {
          id: 1,
          make: 'Toyota',
          model: vehicleModel,
          plate_number: vehiclePlate,
        }
      };

      setTrips(prev => [localTrip, ...prev]);
      setIsDrawerOpen(false);
      setActiveTab('trips');
      setSuccessMessage('Đã đăng lịch trình xe đi làm thành công! (Chế độ xem trước Demo)');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    navigate('/login');
  };

  return (
    <>
      <div className="roomio-grid-bg"></div>
      <div className="bg-overlay"></div>

      <div style={{ display: 'flex', minHeight: '100vh' }}>
        {/* Sidebar (Roomio style) */}
        <aside style={{ width: '260px', background: 'rgba(255, 255, 255, 0.95)', backdropFilter: 'blur(8px)', borderRight: '2px solid #000000', padding: '1.5rem 1.25rem', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', position: 'sticky', top: 0, height: '100vh', flexShrink: 0 }}>
          <div>
            <Link to="/" style={{ textDecoration: 'none', color: '#000000', display: 'block', marginBottom: '2rem' }}>
              <div className="brand-title">carpooling<span>.vn</span></div>
              <div className="brand-badge">Commuter Carpooling</div>
            </Link>

            <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
              <div 
                onClick={() => setActiveTab('trips')}
                style={{ display: 'flex', alignItems: 'center', padding: '0.6rem 0.85rem', fontSize: '0.85rem', fontWeight: 700, color: '#27272A', borderRadius: '6px', border: '2px solid', borderColor: activeTab === 'trips' ? '#000000' : 'transparent', backgroundColor: activeTab === 'trips' ? 'var(--color-primary-blue)' : 'transparent', boxShadow: activeTab === 'trips' ? 'var(--shadow-primary)' : 'none', cursor: 'pointer' }}
              >
                Tổng quan chuyến xe
              </div>
              <div 
                onClick={() => setIsDrawerOpen(true)}
                style={{ display: 'flex', alignItems: 'center', padding: '0.6rem 0.85rem', fontSize: '0.85rem', fontWeight: 700, color: '#27272A', borderRadius: '6px', border: '2px solid transparent', cursor: 'pointer' }}
              >
                Đăng lịch trình xe
              </div>
              <div 
                onClick={() => setActiveTab('bookings')}
                style={{ display: 'flex', alignItems: 'center', padding: '0.6rem 0.85rem', fontSize: '0.85rem', fontWeight: 700, color: '#27272A', borderRadius: '6px', border: '2px solid', borderColor: activeTab === 'bookings' ? '#000000' : 'transparent', backgroundColor: activeTab === 'bookings' ? 'var(--color-primary-blue)' : 'transparent', boxShadow: activeTab === 'bookings' ? 'var(--shadow-primary)' : 'none', cursor: 'pointer' }}
              >
                Yêu cầu ghép xe
              </div>
              <Link to="/" style={{ display: 'flex', alignItems: 'center', padding: '0.6rem 0.85rem', fontSize: '0.85rem', fontWeight: 700, color: '#27272A', textDecoration: 'none', borderRadius: '6px' }}>
                Tìm xe trên bản đồ
              </Link>
            </nav>
          </div>

          <div style={{ border: '2px solid #000000', borderRadius: '8px', background: '#FFFFFF', padding: '1rem', boxShadow: 'var(--shadow-primary)' }}>
            <div style={{ fontSize: '0.875rem', fontWeight: 800, color: '#09090B', marginBottom: '0.2rem', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
              {currentUser.name}
            </div>
            <div style={{ fontSize: '0.7rem', fontWeight: 700, color: '#52525B', marginBottom: '0.75rem' }}>
              {currentUser.role === 'admin' ? 'Quản trị viên hệ thống' : currentUser.role === 'driver' ? 'Chủ xe văn phòng' : 'Hành khách'} · {currentUser.email}
            </div>
            <button onClick={handleLogout} className="roomio-button-white" style={{ width: '100%', padding: '0.4rem 0.75rem', fontSize: '0.8rem' }}>
              <span>Đăng xuất</span>
            </button>
          </div>
        </aside>

        {/* Main Content */}
        <main style={{ flex: 1, padding: '2rem', maxWidth: '1200px', overflowY: 'auto' }}>
          {/* Top Bar */}
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem', flexWrap: 'wrap', gap: '1rem' }}>
            <div>
              <div style={{ fontSize: '0.85rem', color: '#52525B', fontWeight: 600, marginBottom: '0.25rem' }}>
                Hôm nay: Thứ Bảy, ngày 31 tháng 10 năm 2026
              </div>
              <h1 style={{ fontSize: '1.75rem', fontWeight: 900, letterSpacing: '-0.5px' }}>
                Xin chào, {currentUser.name}
              </h1>
            </div>

            <div style={{ display: 'flex', gap: '0.75rem' }}>
              <Link to="/" className="roomio-button-white">
                <span>Về trang chủ</span>
              </Link>
              <button className="roomio-button" onClick={() => setIsDrawerOpen(true)}>
                <span>Đăng lịch trình xe</span>
              </button>
            </div>
          </div>

          {successMessage && (
            <div style={{ border: '2px solid #000000', backgroundColor: '#DCFCE7', color: '#166534', padding: '0.85rem 1rem', borderRadius: '6px', fontSize: '0.85rem', fontWeight: 700, marginBottom: '1.5rem', lineHeight: 1.45 }}>
              {successMessage}
            </div>
          )}

          {/* Stats Ribbon (Exact Roomio continuous bar) */}
          <div className="stats-ribbon">
            <div className="stat-block">
              <div className="stat-label">Chuyến xe mở hôm nay</div>
              <div className="stat-value">{stats.active_trips_count}</div>
              <div className="stat-sub">Đang nhận người đi chung</div>
            </div>
            <div className="stat-block">
              <div className="stat-label">Ghế trống sẵn sàng</div>
              <div className="stat-value">{stats.available_seats_count}</div>
              <div className="stat-sub">Dọc hành lang công sở</div>
            </div>
            <div className="stat-block">
              <div className="stat-label">Yêu cầu ghép xe</div>
              <div className="stat-value">{stats.total_bookings_count}</div>
              <div className="stat-sub">{stats.pending_bookings_count} yêu cầu mới</div>
            </div>
            <div className="stat-block">
              <div className="stat-label">Tiền nền tảng thu</div>
              <div className="stat-value">0đ</div>
              <div className="stat-sub" style={{ color: '#2563eb' }}>100% Phi thương mại</div>
            </div>
          </div>

          {/* Underline Tabs */}
          <div className="tab-bar">
            <button className={`tab-btn ${activeTab === 'trips' ? 'active' : ''}`} onClick={() => setActiveTab('trips')}>
              Chuyến xe đi làm ({trips.length})
            </button>
            <button className={`tab-btn ${activeTab === 'form' ? 'active' : ''}`} onClick={() => setActiveTab('form')}>
              Đăng lịch trình xe mới (Form Roomio)
            </button>
            <button className={`tab-btn ${activeTab === 'bookings' ? 'active' : ''}`} onClick={() => setActiveTab('bookings')}>
              Yêu cầu ghép xe ({bookings.length})
            </button>
          </div>

          {/* TAB 1: Trips Feed */}
          {activeTab === 'trips' && (
            <div className="roomio-window">
              <div className="roomio-window-bar">
                <div className="roomio-window-dots">
                  <span className="roomio-window-dot dot-red"></span>
                  <span className="roomio-window-dot dot-yellow"></span>
                  <span className="roomio-window-dot dot-green"></span>
                </div>
                <div className="window-title">chuyen-xe-di-lam-hom-nay.live</div>
                <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#16A34A' }}>● Trực tiếp</div>
              </div>

              <div style={{ padding: '1.5rem' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
                  <h3 style={{ fontSize: '1.05rem', fontWeight: 900 }}>Chuyến xe dọc tuyến đi làm sáng nay</h3>
                  <button className="roomio-button" style={{ padding: '0.35rem 0.75rem', fontSize: '0.8rem' }} onClick={() => setIsDrawerOpen(true)}>
                    <span>Thêm chuyến xe</span>
                  </button>
                </div>

                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '1.25rem' }}>
                  {trips.map(trip => (
                    <div key={trip.id} style={{ border: '2px solid #000000', borderRadius: '6px', background: '#FFFFFF', padding: '1.25rem', boxShadow: 'var(--shadow-primary)', display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                      <div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '0.75rem' }}>
                          <div>
                            <div style={{ fontSize: '0.95rem', fontWeight: 800 }}>{trip.driver_profile?.user?.name || 'Tài xế Tuấn'}</div>
                            <div style={{ fontSize: '0.75rem', color: '#52525B', fontWeight: 600 }}>
                              {trip.vehicle?.make || 'Toyota'} {trip.vehicle?.model || 'Vios G'} · Biển số: <strong>{trip.vehicle?.plate_number || '61A-889.92'}</strong>
                            </div>
                          </div>
                          <span className="badge-tag badge-green">Tiền xăng tùy tâm</span>
                        </div>

                        <div style={{ fontSize: '0.8rem', color: '#27272A', lineHeight: 1.45, padding: '0.75rem 0', borderTop: '1px dashed #E4E4E7', borderBottom: '1px dashed #E4E4E7', margin: '0.5rem 0 1rem 0' }}>
                          <div><strong>Xuất phát:</strong> {trip.departure_time?.slice(0, 5) || '06:45'} sáng</div>
                          <div><strong>Từ:</strong> {trip.origin_name}</div>
                          <div><strong>Đến:</strong> {trip.destination_name}</div>
                          <div style={{ marginTop: '0.35rem', fontWeight: 700, color: '#15803D' }}>
                            Còn trống {trip.available_seats} ghế trên xe
                          </div>
                        </div>
                      </div>

                      <div style={{ display: 'flex', gap: '0.5rem' }}>
                        <button className="roomio-button" style={{ flex: 1, padding: '0.45rem 0.85rem', fontSize: '0.8rem' }} onClick={() => alert('Đã gửi lời chào hẹn đi cùng đến tài xế!')}>
                          <span>Nhắn hẹn đi cùng</span>
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* TAB 2: Inline Form (Form Roomio Chuẩn) */}
          {activeTab === 'form' && (
            <div className="roomio-card" style={{ marginBottom: '2rem' }}>
              <div style={{ marginBottom: '1.5rem', paddingBottom: '1rem', borderBottom: '2px solid #000000', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <div>
                  <h3 style={{ fontSize: '1.2rem', fontWeight: 900 }}>Đăng lịch trình xe đi làm (T2–T6)</h3>
                  <p style={{ fontSize: '0.8rem', color: '#52525B', fontWeight: 600, marginTop: '0.25rem' }}>
                    Chủ xe mở ghế trống cho đồng nghiệp đi nhờ dọc đường. 100% phi thương mại, không thu tiền trên app.
                  </p>
                </div>
                <span className="badge-tag badge-green">Form chuẩn Roomio</span>
              </div>

              <form onSubmit={handleCreateTrip}>
                <div className="form-grid-2">
                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Điểm đón xuất phát (Origin)</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={originName}
                      onChange={(e) => setOriginName(e.target.value)}
                      required
                      placeholder="Ví dụ: TT Hành chính Bình Dương, Thủ Dầu Một"
                    />
                  </div>

                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Điểm đến làm việc (Destination)</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={destinationName}
                      onChange={(e) => setDestinationName(e.target.value)}
                      required
                      placeholder="Ví dụ: Tòa nhà Bitexco / Quận 1"
                    />
                  </div>
                </div>

                <div className="form-grid-2">
                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Giờ xuất phát sáng</label>
                    <input
                      type="time"
                      className="roomio-form-input"
                      value={departureTime}
                      onChange={(e) => setDepartureTime(e.target.value)}
                      required
                    />
                  </div>

                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Số ghế mở đi chung</label>
                    <select
                      className="roomio-form-input"
                      value={availableSeats}
                      onChange={(e) => setAvailableSeats(Number(e.target.value))}
                    >
                      <option value={1}>1 ghế</option>
                      <option value={2}>2 ghế</option>
                      <option value={3}>3 ghế</option>
                      <option value={4}>4 ghế</option>
                    </select>
                  </div>
                </div>

                <div className="roomio-form-group">
                  <label className="roomio-form-label">Các ngày đi làm cố định</label>
                  <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                    {['T2', 'T3', 'T4', 'T5', 'T6'].map(day => (
                      <div
                        key={day}
                        onClick={() => toggleDay(day)}
                        style={{ padding: '0.45rem 0.85rem', border: '2px solid #000000', borderRadius: '6px', fontSize: '0.8rem', fontWeight: 800, backgroundColor: selectedDays.includes(day) ? 'var(--color-primary-blue)' : '#FFFFFF', color: selectedDays.includes(day) ? '#000000' : '#52525B', boxShadow: selectedDays.includes(day) ? 'var(--shadow-primary)' : 'none', cursor: 'pointer', userSelect: 'none' }}
                      >
                        {day}
                      </div>
                    ))}
                  </div>
                </div>

                <div className="form-grid-2">
                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Dòng xe</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={vehicleModel}
                      onChange={(e) => setVehicleModel(e.target.value)}
                    />
                  </div>

                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Biển số</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={vehiclePlate}
                      onChange={(e) => setVehiclePlate(e.target.value)}
                    />
                  </div>
                </div>

                <div style={{ backgroundColor: '#F4F4F5', border: '2px solid #000000', borderRadius: '6px', padding: '1rem', marginBottom: '1.5rem', fontSize: '0.8rem', color: '#52525B' }}>
                  <strong>Quy tắc phi thương mại:</strong> Nền tảng không thu phí. Tiền xăng cộ là tự nguyện giữa chủ xe và người đi nhờ khi lên xe.
                </div>

                <div style={{ display: 'flex', gap: '0.75rem', justifyContent: 'flex-end' }}>
                  <button type="button" className="roomio-button-white" onClick={() => setActiveTab('trips')}>
                    <span>Hủy bỏ</span>
                  </button>
                  <button type="submit" disabled={isSubmitting} className="roomio-button">
                    <span>{isSubmitting ? 'Đang lưu...' : 'Lưu và mở chuyến xe'}</span>
                  </button>
                </div>
              </form>
            </div>
          )}

          {/* TAB 3: Bookings Queue */}
          {activeTab === 'bookings' && (
            <div className="roomio-window">
              <div className="roomio-window-bar">
                <div className="roomio-window-dots">
                  <span className="roomio-window-dot dot-red"></span>
                  <span className="roomio-window-dot dot-yellow"></span>
                  <span className="roomio-window-dot dot-green"></span>
                </div>
                <div className="window-title">yeu-cau-ghep-chuyen.queue</div>
              </div>

              <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '0.85rem' }}>
                  <thead>
                    <tr style={{ backgroundColor: '#F4F4F5' }}>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Hành khách</th>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Điểm đón (Pickup)</th>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Điểm đến (Dropoff)</th>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Số ghế</th>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Trạng thái</th>
                      <th style={{ padding: '0.75rem 1rem', borderBottom: '2px solid #000000', fontWeight: 800, fontSize: '0.75rem', textTransform: 'uppercase' }}>Lời nhắn</th>
                    </tr>
                  </thead>
                  <tbody>
                    {bookings.map(b => (
                      <tr key={b.id} style={{ borderBottom: '1px solid #E4E4E7' }}>
                        <td style={{ padding: '0.85rem 1rem' }}>
                          <div style={{ fontWeight: 800 }}>{b.passenger?.name || 'Lê Phương Thảo'}</div>
                          <div style={{ fontSize: '0.75rem', color: '#71717A' }}>{b.passenger?.phone || '0938112233'}</div>
                        </td>
                        <td style={{ padding: '0.85rem 1rem' }}>{b.pickup_name}</td>
                        <td style={{ padding: '0.85rem 1rem' }}>{b.dropoff_name}</td>
                        <td style={{ padding: '0.85rem 1rem' }}><strong>{b.seats_requested} ghế</strong></td>
                        <td style={{ padding: '0.85rem 1rem' }}>
                          <span className={`badge-tag ${b.status === 'accepted' ? 'badge-green' : ''}`}>
                            {b.status === 'accepted' ? 'Đã duyệt' : 'Chờ xác nhận'}
                          </span>
                        </td>
                        <td style={{ padding: '0.85rem 1rem', color: '#52525B', fontStyle: 'italic' }}>
                          "{b.note || 'Không có ghi chú'}"
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* Blue Card */}
          <div className="roomio-blue-card">
            <h4 style={{ fontSize: '1rem', fontWeight: 900, color: '#1E3A8A', marginBottom: '0.5rem' }}>
              Văn hóa đi nhờ xe văn minh & An tâm pháp lý
            </h4>
            <p style={{ fontSize: '0.825rem', color: '#1E3A8A', lineHeight: 1.55, fontWeight: 500 }}>
              Tất cả các chuyến đi kết nối trên <strong>carpooling.vn</strong> là sự sẻ chia tự nguyện giữa những người cùng công sở, cùng trục đường. 
              Nền tảng <strong>tuyệt đối không thu phí trung gian, không ấn định giá vé và không trung chuyển tiền</strong>. 
              Tiền hỗ trợ xăng cộ hay bữa sáng do hai bên tự tính với nhau trực tiếp khi gặp mặt.
            </p>
          </div>
        </main>
      </div>

      {/* Slide-over Form Drawer (Roomio Standard) */}
      {isDrawerOpen && (
        <div className="roomio-form-drawer-overlay" onClick={() => setIsDrawerOpen(false)}>
          <div className="roomio-form-drawer-panel" onClick={(e) => e.stopPropagation()}>
            <div className="roomio-form-drawer-head">
              <span style={{ fontSize: '1.05rem', fontWeight: 900, color: '#000000' }}>
                Đăng lịch trình xe đi làm (T2–T6)
              </span>
              <button 
                onClick={() => setIsDrawerOpen(false)}
                style={{ background: '#FFFFFF', border: '2px solid #000000', borderRadius: '6px', width: '32px', height: '32px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900, cursor: 'pointer' }}
                aria-label="Đóng"
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleCreateTrip} style={{ display: 'flex', flexDirection: 'column', height: '100%', minHeight: 0 }}>
              <div className="roomio-form-drawer-body">
                <div className="roomio-form-group">
                  <label className="roomio-form-label">Điểm xuất phát đón khách (Origin)</label>
                  <input
                    type="text"
                    className="roomio-form-input"
                    value={originName}
                    onChange={(e) => setOriginName(e.target.value)}
                    required
                    placeholder="Ví dụ: TT Hành chính Bình Dương"
                  />
                </div>

                <div className="roomio-form-group">
                  <label className="roomio-form-label">Điểm đến làm việc (Destination)</label>
                  <input
                    type="text"
                    className="roomio-form-input"
                    value={destinationName}
                    onChange={(e) => setDestinationName(e.target.value)}
                    required
                    placeholder="Ví dụ: Tòa nhà Bitexco / Quận 1"
                  />
                </div>

                <div className="form-grid-2">
                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Giờ xuất phát sáng</label>
                    <input
                      type="time"
                      className="roomio-form-input"
                      value={departureTime}
                      onChange={(e) => setDepartureTime(e.target.value)}
                      required
                    />
                  </div>

                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Số ghế mở đi chung</label>
                    <select
                      className="roomio-form-input"
                      value={availableSeats}
                      onChange={(e) => setAvailableSeats(Number(e.target.value))}
                    >
                      <option value={1}>1 ghế</option>
                      <option value={2}>2 ghế</option>
                      <option value={3}>3 ghế</option>
                      <option value={4}>4 ghế</option>
                    </select>
                  </div>
                </div>

                <div className="roomio-form-group">
                  <label className="roomio-form-label">Các ngày đi làm cố định</label>
                  <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                    {['T2', 'T3', 'T4', 'T5', 'T6'].map(day => (
                      <div
                        key={day}
                        onClick={() => toggleDay(day)}
                        style={{ padding: '0.45rem 0.85rem', border: '2px solid #000000', borderRadius: '6px', fontSize: '0.8rem', fontWeight: 800, backgroundColor: selectedDays.includes(day) ? 'var(--color-primary-blue)' : '#FFFFFF', color: selectedDays.includes(day) ? '#000000' : '#52525B', boxShadow: selectedDays.includes(day) ? 'var(--shadow-primary)' : 'none', cursor: 'pointer', userSelect: 'none' }}
                      >
                        {day}
                      </div>
                    ))}
                  </div>
                </div>

                <div className="form-grid-2">
                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Dòng xe</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={vehicleModel}
                      onChange={(e) => setVehicleModel(e.target.value)}
                    />
                  </div>

                  <div className="roomio-form-group">
                    <label className="roomio-form-label">Biển số</label>
                    <input
                      type="text"
                      className="roomio-form-input"
                      value={vehiclePlate}
                      onChange={(e) => setVehiclePlate(e.target.value)}
                    />
                  </div>
                </div>

                <div style={{ backgroundColor: '#F4F4F5', border: '2px solid #000000', borderRadius: '6px', padding: '0.85rem', fontSize: '0.8rem', color: '#52525B' }}>
                  <strong>Quy tắc phi thương mại:</strong> Nền tảng không thu phí. Tiền xăng cộ là tự nguyện giữa chủ xe và người đi nhờ khi lên xe.
                </div>
              </div>

              <div className="roomio-form-drawer-foot">
                <button type="button" className="roomio-button-white" style={{ flex: 1 }} onClick={() => setIsDrawerOpen(false)}>
                  <span>Hủy</span>
                </button>
                <button type="submit" disabled={isSubmitting} className="roomio-button" style={{ flex: 2 }}>
                  <span>{isSubmitting ? 'Đang lưu...' : 'Lưu và mở chuyến xe'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
};
