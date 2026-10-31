import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { fetchBookings, type Booking } from '../../lib/api';

export const BookingsPage: React.FC = () => {
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [activeFilter, setActiveFilter] = useState<'all' | 'requested' | 'accepted'>('all');
  const [isLoading, setIsLoading] = useState(true);
  const [actionFeedback, setActionFeedback] = useState<string | null>(null);

  useEffect(() => {
    loadBookings();
  }, []);

  const loadBookings = async () => {
    setIsLoading(true);
    try {
      const data = await fetchBookings();
      setBookings(data);
    } catch {
      // Fallback
    } finally {
      setIsLoading(false);
    }
  };

  const handleAccept = (bookingId: number, passengerName: string) => {
    setBookings(prev =>
      prev.map(b => (b.id === bookingId ? { ...b, status: 'accepted' } : b))
    );
    setActionFeedback(`Đã chấp nhận yêu cầu của đồng nghiệp ${passengerName}! Hãy liên lạc trao đổi điểm đón trước giờ đi.`);
    setTimeout(() => setActionFeedback(null), 5000);
  };

  const handleDecline = (bookingId: number, passengerName: string) => {
    setBookings(prev =>
      prev.map(b => (b.id === bookingId ? { ...b, status: 'cancelled' } : b))
    );
    setActionFeedback(`Đã từ chối yêu cầu của ${passengerName}. Ghế vẫn giữ trống cho người khác.`);
    setTimeout(() => setActionFeedback(null), 5000);
  };

  const filteredBookings = bookings.filter(b => {
    if (activeFilter === 'all') return true;
    return b.status === activeFilter;
  });

  const pendingCount = bookings.filter(b => b.status === 'requested').length;
  const acceptedCount = bookings.filter(b => b.status === 'accepted').length;

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
            Hộp thư đi chung
          </div>
          <h1 style={{ fontSize: '1.75rem', fontWeight: 900, letterSpacing: '-0.5px' }}>
            Yêu cầu ghép xe của đồng nghiệp
          </h1>
        </div>

        <div style={{ display: 'flex', gap: '0.75rem' }}>
          <Link to="/dashboard/create-trip" className="roomio-button">
            <span>Đăng thêm chuyến xe</span>
          </Link>
        </div>
      </div>

      {actionFeedback && (
        <div
          style={{
            border: '2px solid #000000',
            backgroundColor: '#DCFCE7',
            color: '#166534',
            padding: '0.85rem 1rem',
            borderRadius: '6px',
            fontSize: '0.85rem',
            fontWeight: 700,
            marginBottom: '1.5rem',
            lineHeight: 1.45,
          }}
        >
          {actionFeedback}
        </div>
      )}

      {/* Filter Tabs */}
      <div className="tab-bar">
        <button
          className={`tab-btn ${activeFilter === 'all' ? 'active' : ''}`}
          onClick={() => setActiveFilter('all')}
        >
          Tất cả yêu cầu ({bookings.length})
        </button>
        <button
          className={`tab-btn ${activeFilter === 'requested' ? 'active' : ''}`}
          onClick={() => setActiveFilter('requested')}
        >
          Chờ tài xế duyệt ({pendingCount})
        </button>
        <button
          className={`tab-btn ${activeFilter === 'accepted' ? 'active' : ''}`}
          onClick={() => setActiveFilter('accepted')}
        >
          Đã nhận đi chung ({acceptedCount})
        </button>
      </div>

      {/* Bookings Window Table */}
      <div className="roomio-window">
        <div className="roomio-window-bar">
          <div className="roomio-window-dots">
            <span className="roomio-window-dot dot-red"></span>
            <span className="roomio-window-dot dot-yellow"></span>
            <span className="roomio-window-dot dot-green"></span>
          </div>
          <div className="window-title">danh-sach-yeu-cau-ghep-xe.queue</div>
          <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#16A34A' }}>● Đang xử lý</div>
        </div>

        <div style={{ padding: '1.5rem' }}>
          {isLoading ? (
            <div style={{ textAlign: 'center', padding: '2.5rem', color: '#71717A', fontWeight: 700 }}>
              Đang tải danh sách yêu cầu ghép xe...
            </div>
          ) : filteredBookings.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '3rem 1rem', border: '2px dashed #000000', borderRadius: '8px' }}>
              <p style={{ fontSize: '0.95rem', fontWeight: 700, color: '#3F3F46', marginBottom: '0.5rem' }}>
                Không có yêu cầu ghép xe nào ở mục này.
              </p>
              <p style={{ fontSize: '0.8rem', color: '#71717A', fontWeight: 500 }}>
                Khi có đồng nghiệp tìm xe trùng trục đường với bạn, yêu cầu sẽ hiển thị tại đây.
              </p>
            </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
              {filteredBookings.map(booking => {
                const isPending = booking.status === 'requested';
                const isAccepted = booking.status === 'accepted';
                const passengerName = booking.passenger?.name || 'Đồng nghiệp xin ghép xe';

                return (
                  <div key={booking.id} className="trip-item">
                    <div
                      style={{
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'flex-start',
                        flexWrap: 'wrap',
                        gap: '0.75rem',
                        marginBottom: '0.85rem',
                      }}
                    >
                      <div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '0.25rem' }}>
                          <span style={{ fontSize: '1.05rem', fontWeight: 900, color: '#09090B' }}>
                            {passengerName}
                          </span>
                          <span style={{ fontSize: '0.75rem', color: '#71717A', fontWeight: 600 }}>
                            · {booking.passenger?.phone || '0908 123 456'}
                          </span>
                        </div>
                        <div style={{ fontSize: '0.75rem', color: '#52525B', fontWeight: 600 }}>
                          Xin ghép {booking.seats_requested} ghế · Khởi hành cùng tuyến đi làm sáng
                        </div>
                      </div>

                      {/* Status Badge */}
                      {isPending ? (
                        <span
                          style={{
                            padding: '0.25rem 0.65rem',
                            fontSize: '0.75rem',
                            fontWeight: 800,
                            borderRadius: '4px',
                            border: '1.5px solid #000000',
                            backgroundColor: '#FEF08A',
                            color: '#854D0E',
                          }}
                        >
                          Chờ tài xế duyệt
                        </span>
                      ) : isAccepted ? (
                        <span
                          style={{
                            padding: '0.25rem 0.65rem',
                            fontSize: '0.75rem',
                            fontWeight: 800,
                            borderRadius: '4px',
                            border: '1.5px solid #000000',
                            backgroundColor: '#DCFCE7',
                            color: '#15803D',
                          }}
                        >
                          Đã chấp nhận
                        </span>
                      ) : (
                        <span
                          style={{
                            padding: '0.25rem 0.65rem',
                            fontSize: '0.75rem',
                            fontWeight: 800,
                            borderRadius: '4px',
                            border: '1.5px solid #000000',
                            backgroundColor: '#F4F4F5',
                            color: '#71717A',
                          }}
                        >
                          Đã hủy
                        </span>
                      )}
                    </div>

                    {/* Route Details */}
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem', marginBottom: '1rem', backgroundColor: '#FAFAFA', padding: '0.75rem', borderRadius: '6px', border: '1.5px solid #E4E4E7' }}>
                      <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
                        <span style={{ fontSize: '0.75rem', fontWeight: 800, color: '#4338CA' }}>Đón:</span>
                        <span style={{ fontSize: '0.85rem', fontWeight: 700, color: '#18181B' }}>
                          {booking.pickup_name || 'Điểm hẹn đón trên trục đường'}
                        </span>
                      </div>
                      <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
                        <span style={{ fontSize: '0.75rem', fontWeight: 800, color: '#15803D' }}>Trả:</span>
                        <span style={{ fontSize: '0.85rem', fontWeight: 700, color: '#18181B' }}>
                          {booking.dropoff_name || 'Tòa nhà văn phòng / Công ty'}
                        </span>
                      </div>
                    </div>

                    {/* Actions and Policy */}
                    <div
                      style={{
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        flexWrap: 'wrap',
                        gap: '0.75rem',
                        borderTop: '1px dashed #D4D4D8',
                        paddingTop: '0.75rem',
                      }}
                    >
                      <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#71717A' }}>
                        Chi phí: Tùy tâm gửi tiền xăng khi lên xe (Không qua app)
                      </div>

                      {isPending && (
                        <div style={{ display: 'flex', gap: '0.5rem' }}>
                          <button
                            onClick={() => handleDecline(booking.id, passengerName)}
                            className="roomio-button-white"
                            style={{ padding: '0.4rem 0.75rem', fontSize: '0.8rem' }}
                          >
                            <span>Từ chối</span>
                          </button>
                          <button
                            onClick={() => handleAccept(booking.id, passengerName)}
                            className="roomio-button"
                            style={{ padding: '0.4rem 0.85rem', fontSize: '0.8rem', backgroundColor: '#DCFCE7' }}
                          >
                            <span>Đồng ý cho đi nhờ</span>
                          </button>
                        </div>
                      )}
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
