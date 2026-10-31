import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { fetchStats, fetchTrips, type Stats, type Trip } from '../../lib/api';

export const DashboardOverviewPage: React.FC = () => {
  const [trips, setTrips] = useState<Trip[]>([]);
  const [filterRoute, setFilterRoute] = useState<string>('all');
  const [isLoading, setIsLoading] = useState(true);
  const [stats, setStats] = useState<Stats>({
    active_trips_count: 2,
    available_seats_count: 5,
    total_bookings_count: 3,
    pending_bookings_count: 1,
    platform_fee: 0,
  });

  const currentUser = JSON.parse(
    localStorage.getItem('auth_user') ||
      '{"name":"Quản trị viên Hệ thống","email":"admin@carpooling.vn","role":"admin"}'
  );

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    setIsLoading(true);
    try {
      const [t, s] = await Promise.all([fetchTrips(), fetchStats()]);
      setTrips(t);
      setStats(s);
    } catch {
      // Fallback
    } finally {
      setIsLoading(false);
    }
  };

  const filteredTrips = trips.filter(trip => {
    if (filterRoute === 'all') return true;
    if (filterRoute === 'binhduong') return trip.origin_name.includes('Bình Dương');
    if (filterRoute === 'thuduc') return trip.origin_name.includes('Thủ Đức');
    return true;
  });

  return (
    <div>
      {/* Top Bar */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginBottom: '2rem',
          flexWrap: 'wrap',
          gap: '1rem',
        }}
      >
        <div>
          <div style={{ fontSize: '0.85rem', color: '#52525B', fontWeight: 600, marginBottom: '0.25rem' }}>
            Hôm nay: Thứ Bảy, ngày 31 tháng 10 năm 2026
          </div>
          <h1 style={{ fontSize: '1.75rem', fontWeight: 900, letterSpacing: '-0.5px' }}>
            Xin chào, {currentUser.name}
          </h1>
        </div>

        <div style={{ display: 'flex', gap: '0.75rem' }}>
          <Link to="/dashboard/map-search" className="roomio-button-white">
            <span>Tìm xe trên bản đồ</span>
          </Link>
          <Link to="/dashboard/create-trip" className="roomio-button">
            <span>Đăng lịch trình xe</span>
          </Link>
        </div>
      </div>

      {/* Stats Ribbon (Continuous strip, exact Roomio style) */}
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
          <div className="stat-sub" style={{ color: '#2563eb' }}>
            100% Phi thương mại
          </div>
        </div>
      </div>

      {/* Trips Feed Window */}
      <div className="roomio-window">
        <div className="roomio-window-bar">
          <div className="roomio-window-dots">
            <span className="roomio-window-dot dot-red"></span>
            <span className="roomio-window-dot dot-yellow"></span>
            <span className="roomio-window-dot dot-green"></span>
          </div>
          <div className="window-title">tong-quan-chuyen-xe-di-lam.live</div>
          <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#16A34A' }}>● Trực tiếp</div>
        </div>

        <div style={{ padding: '1.5rem' }}>
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              marginBottom: '1.25rem',
              flexWrap: 'wrap',
              gap: '0.75rem',
            }}
          >
            <div>
              <h2 style={{ fontSize: '1.1rem', fontWeight: 900 }}>Chuyến xe dọc tuyến đi làm sáng nay</h2>
              <p style={{ fontSize: '0.8rem', color: '#52525B', fontWeight: 500, marginTop: '0.2rem' }}>
                Đồng nghiệp mở xe sáng T2–T6. Tiền xăng cộ tùy lòng cảm ơn chủ xe khi gặp nhau.
              </p>
            </div>

            {/* Quick route filters */}
            <div style={{ display: 'flex', gap: '0.5rem' }}>
              <button
                onClick={() => setFilterRoute('all')}
                style={{
                  padding: '0.35rem 0.65rem',
                  fontSize: '0.75rem',
                  fontWeight: 800,
                  borderRadius: '6px',
                  border: '1.5px solid #000000',
                  backgroundColor: filterRoute === 'all' ? '#BFDBFE' : '#FFFFFF',
                  cursor: 'pointer',
                }}
              >
                Tất cả trục đường
              </button>
              <button
                onClick={() => setFilterRoute('binhduong')}
                style={{
                  padding: '0.35rem 0.65rem',
                  fontSize: '0.75rem',
                  fontWeight: 800,
                  borderRadius: '6px',
                  border: '1.5px solid #000000',
                  backgroundColor: filterRoute === 'binhduong' ? '#BFDBFE' : '#FFFFFF',
                  cursor: 'pointer',
                }}
              >
                Trục Bình Dương
              </button>
              <button
                onClick={() => setFilterRoute('thuduc')}
                style={{
                  padding: '0.35rem 0.65rem',
                  fontSize: '0.75rem',
                  fontWeight: 800,
                  borderRadius: '6px',
                  border: '1.5px solid #000000',
                  backgroundColor: filterRoute === 'thuduc' ? '#BFDBFE' : '#FFFFFF',
                  cursor: 'pointer',
                }}
              >
                Trục Thủ Đức
              </button>
            </div>
          </div>

          {isLoading ? (
            <div style={{ textAlign: 'center', padding: '2.5rem', color: '#71717A', fontWeight: 700 }}>
              Đang tải danh sách chuyến xe...
            </div>
          ) : filteredTrips.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '3rem 1rem', border: '2px dashed #000000', borderRadius: '8px' }}>
              <p style={{ fontSize: '0.95rem', fontWeight: 700, color: '#3F3F46', marginBottom: '1rem' }}>
                Chưa có chuyến xe nào trên trục đường đã chọn.
              </p>
              <Link to="/dashboard/create-trip" className="roomio-button">
                <span>Mở chuyến xe đầu tiên</span>
              </Link>
            </div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '1.25rem' }}>
              {filteredTrips.map(trip => (
                <div key={trip.id} className="trip-item">
                  <div
                    style={{
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'flex-start',
                      marginBottom: '0.85rem',
                    }}
                  >
                    <div>
                      <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#52525B', textTransform: 'uppercase' }}>
                        Khởi hành {trip.departure_time?.substring(0, 5)}
                      </div>
                      <div style={{ fontSize: '1rem', fontWeight: 900, color: '#09090B' }}>
                        {trip.driver_profile?.user?.name || 'Tài xế văn phòng'}
                      </div>
                    </div>
                    <span className="badge-tag badge-green">{trip.available_seats} ghế trống</span>
                  </div>

                  <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', marginBottom: '1rem' }}>
                    <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
                      <span
                        style={{
                          width: '18px',
                          height: '18px',
                          borderRadius: '3px',
                          border: '1.5px solid #000000',
                          background: '#E0E7FF',
                          display: 'inline-flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          fontSize: '0.65rem',
                          fontWeight: 900,
                          flexShrink: 0,
                        }}
                      >
                        A
                      </span>
                      <span style={{ fontSize: '0.85rem', fontWeight: 700, color: '#18181B' }}>
                        {trip.origin_name}
                      </span>
                    </div>
                    <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
                      <span
                        style={{
                          width: '18px',
                          height: '18px',
                          borderRadius: '3px',
                          border: '1.5px solid #000000',
                          background: '#DCFCE7',
                          display: 'inline-flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          fontSize: '0.65rem',
                          fontWeight: 900,
                          flexShrink: 0,
                        }}
                      >
                        B
                      </span>
                      <span style={{ fontSize: '0.85rem', fontWeight: 700, color: '#18181B' }}>
                        {trip.destination_name}
                      </span>
                    </div>
                  </div>

                  <div
                    style={{
                      borderTop: '1px dashed #D4D4D8',
                      paddingTop: '0.75rem',
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                    }}
                  >
                    <div style={{ fontSize: '0.75rem', fontWeight: 700, color: '#71717A' }}>
                      {trip.vehicle?.make} {trip.vehicle?.model} · {trip.vehicle?.plate_number}
                    </div>
                    <div style={{ fontSize: '0.8rem', fontWeight: 800, color: '#16A34A' }}>
                      Tự tính xăng cộ
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
