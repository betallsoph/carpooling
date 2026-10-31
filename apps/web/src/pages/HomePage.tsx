import React, { useState } from 'react';
import { Link } from 'react-router-dom';

export const HomePage: React.FC = () => {
  const [isSearching, setIsSearching] = useState(false);
  const [hasSearched, setHasSearched] = useState(false);

  const handleRunSearch = () => {
    setIsSearching(true);
    setTimeout(() => {
      setIsSearching(false);
      setHasSearched(true);
    }, 300);
  };

  return (
    <>
      <div className="roomio-grid-bg"></div>
      <div className="bg-overlay"></div>

      <div className="container">
        {/* Header */}
        <header>
          <Link to="/" className="brand-link">
            <div className="brand-title">carpooling<span>.vn</span></div>
          </Link>

          <div className="header-actions">
            <Link to="/login" className="roomio-button-white">
              <span>Đăng nhập</span>
            </Link>
            <Link to="/dashboard" className="roomio-button">
              <span>Bảng điều khiển</span>
            </Link>
          </div>
        </header>

        {/* Hero Section (Left-aligned Roomio style) */}
        <section className="hero">
          <h1>
            Tiện đường thì đi chung,<br />tiền xăng tùy lòng.
          </h1>

          <p className="hero-desc">
            Cùng đi làm sáng chiều trên một trục đường thì rủ nhau đi chung xe cho đỡ buồn ngủ và đỡ kẹt xe. 
            Nền tảng <strong>không thu bất kỳ đồng phí nào</strong> và <strong>không giao dịch tiền trên app</strong>. 
            Tiền xăng cộ hay cà phê ăn sáng hai bên tự tính với nhau, tùy lòng cảm ơn chủ xe.
          </p>

          <div className="tech-chips">
            <span className="chip chip-green">100% Phi thương mại</span>
            <span className="chip">Laravel 13 API + React Vite</span>
            <span className="chip">Cộng đồng dân văn phòng</span>
          </div>
        </section>

        {/* Main Grid */}
        <div className="grid-2">
          {/* Left: Corridor Matching Window */}
          <div className="roomio-window">
            <div className="roomio-window-bar">
              <div className="roomio-window-dots">
                <span className="roomio-window-dot dot-red"></span>
                <span className="roomio-window-dot dot-yellow"></span>
                <span className="roomio-window-dot dot-green"></span>
              </div>
              <div className="window-title">thuat-toan-ghep-hanh-lang.postgis</div>
              <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#16A34A' }}>● Sẵn sàng</div>
            </div>

            <div style={{ padding: '1.5rem' }}>
              <p style={{ fontSize: '0.875rem', color: '#3F3F46', lineHeight: 1.5, marginBottom: '1.25rem' }}>
                Chủ xe đăng lịch trình đi làm cố định sáng T2–T6. Khi bạn tìm xe, hệ thống dùng <strong>PostGIS</strong> 
                quét dọc trục đường xe chạy (độ lệch ≤ 2km) và kiểm tra đúng chiều xe đi để kết nối hai người.
              </p>

              {/* Stop A */}
              <div style={{ display: 'flex', gap: '0.85rem', alignItems: 'flex-start', padding: '0.75rem 1rem', background: '#F4F4F5', border: '2px solid #000000', borderRadius: '6px', marginBottom: '0.75rem' }}>
                <div style={{ width: '28px', height: '28px', borderRadius: '4px', border: '1.5px solid #000000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900, fontSize: '0.85rem', flexShrink: 0, backgroundColor: '#E0E7FF', color: '#4338CA' }}>A</div>
                <div>
                  <div style={{ fontSize: '0.75rem', fontWeight: 800, textTransform: 'uppercase', color: '#71717A' }}>Điểm đón của bạn (Pickup)</div>
                  <div style={{ fontSize: '0.875rem', fontWeight: 700, color: '#09090B' }}>Lotte Mart Bình Dương (Đại lộ Bình Dương, Thuận An)</div>
                </div>
              </div>

              {/* Stop B */}
              <div style={{ display: 'flex', gap: '0.85rem', alignItems: 'flex-start', padding: '0.75rem 1rem', background: '#F4F4F5', border: '2px solid #000000', borderRadius: '6px', marginBottom: '1rem' }}>
                <div style={{ width: '28px', height: '28px', borderRadius: '4px', border: '1.5px solid #000000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900, fontSize: '0.85rem', flexShrink: 0, backgroundColor: '#DCFCE7', color: '#15803D' }}>B</div>
                <div>
                  <div style={{ fontSize: '0.75rem', fontWeight: 800, textTransform: 'uppercase', color: '#71717A' }}>Điểm đến làm việc (Dropoff)</div>
                  <div style={{ fontSize: '0.875rem', fontWeight: 700, color: '#09090B' }}>Ngã tư Hàng Xanh / Tòa nhà Bitexco Quận 1</div>
                </div>
              </div>

              <button 
                id="btnRunSearch"
                className="roomio-button" 
                style={{ width: '100%', justifyContent: 'center', padding: '0.65rem 1rem', backgroundColor: hasSearched ? '#DCFCE7' : undefined }}
                onClick={handleRunSearch}
                disabled={isSearching}
              >
                <span>
                  {isSearching ? 'Đang dò tìm tuyến xe cùng đường...' : hasSearched ? 'Đã tìm thấy chuyến xe phù hợp!' : 'Thử tìm xe đi làm qua đoạn này'}
                </span>
              </button>

              <div style={{ marginTop: '1rem', background: '#18181B', color: '#A1A1AA', padding: '0.85rem', borderRadius: '6px', fontSize: '0.75rem', fontFamily: 'monospace', border: '2px solid #000000', overflowX: 'auto' }}>
                <span style={{ color: '#60A5FA' }}>SELECT</span> * <span style={{ color: '#60A5FA' }}>FROM</span> trip_instances<br />
                <span style={{ color: '#60A5FA' }}>WHERE</span> <span style={{ color: '#34D399' }}>ST_DWithin</span>(route_line::geography, pickup_point, 2000)<br />
                &nbsp;&nbsp;<span style={{ color: '#60A5FA' }}>AND</span> <span style={{ color: '#34D399' }}>ST_LineLocatePoint</span>(route_line, pickup) &lt; <span style={{ color: '#34D399' }}>ST_LineLocatePoint</span>(route_line, dropoff);
              </div>
            </div>

            {/* Dynamic Results */}
            {hasSearched && (
              <div style={{ borderTop: '2px solid #000000', padding: '1.25rem', backgroundColor: '#FAFAFA' }}>
                <div style={{ fontSize: '0.8rem', fontWeight: 800, marginBottom: '0.75rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <span>KẾT QUẢ TÌM THẤY:</span>
                  <span className="badge-tag badge-green">Cùng trục đường</span>
                </div>

                <div style={{ border: '2px solid #000000', borderRadius: '6px', background: '#FFFFFF', padding: '1rem', boxShadow: 'var(--shadow-primary)' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '0.5rem' }}>
                    <div>
                      <h4 style={{ fontSize: '0.95rem', fontWeight: 800 }}>Nguyễn Minh Tuấn</h4>
                      <p style={{ fontSize: '0.75rem', color: '#52525B' }}>Toyota Vios G (Trắng) · Biển số: 61A-889.92</p>
                    </div>
                    <div style={{ textAlign: 'right' }}>
                      <span className="badge-tag badge-green" style={{ fontSize: '0.75rem' }}>Tiền xăng tùy tâm</span>
                      <div style={{ fontSize: '0.7rem', color: '#71717A', fontWeight: 600, marginTop: '2px' }}>tự tính lúc đi chung</div>
                    </div>
                  </div>

                  <div style={{ fontSize: '0.8rem', color: '#27272A', lineHeight: 1.4, paddingTop: '0.5rem', borderTop: '1px dashed #E4E4E7' }}>
                    <div><strong>Xuất phát:</strong> 06:45 sáng (T2–T6) · Còn trống: <strong>2 ghế</strong></div>
                    <div><strong>Lộ trình:</strong> TT Hành chính Bình Dương ➔ QL13 ➔ Hàng Xanh ➔ Quận 1</div>
                    <div style={{ marginTop: '0.65rem' }}>
                      <button className="roomio-button" style={{ padding: '0.35rem 0.85rem', fontSize: '0.8rem' }} onClick={() => alert('Đã gửi lời chào hẹn đi cùng đến tài xế Tuấn!')}>
                        <span>Nhắn hẹn đi cùng</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>

          {/* Right: Community Etiquette & Legal Reassurance */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1.75rem' }}>
            <div className="roomio-window">
              <div className="roomio-window-bar">
                <div className="roomio-window-dots">
                  <span className="roomio-window-dot dot-red"></span>
                  <span className="roomio-window-dot dot-yellow"></span>
                  <span className="roomio-window-dot dot-green"></span>
                </div>
                <div className="window-title">van-hoa-di-chung-xe.community</div>
              </div>

              <div style={{ padding: '1.5rem' }}>
                <h3 style={{ fontSize: '1.05rem', fontWeight: 900, marginBottom: '0.5rem' }}>Văn hóa đi nhờ xe văn minh</h3>
                <p style={{ fontSize: '0.825rem', color: '#52525B', lineHeight: 1.5, marginBottom: '1rem' }}>
                  Chủ xe là dân văn phòng tiện đường đón bạn cùng đi cho vui, không phải tài xế công nghệ kiếm sống. 
                  Hãy giữ thái độ lịch thiệp và tôn trọng nhau:
                </p>

                <div style={{ display: 'flex', gap: '0.85rem', marginBottom: '0.85rem' }}>
                  <div style={{ width: '24px', height: '24px', borderRadius: '50%', background: 'var(--color-primary-blue)', border: '1.5px solid #000000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.75rem', fontWeight: 900, flexShrink: 0 }}>1</div>
                  <div>
                    <div style={{ fontSize: '0.825rem', fontWeight: 800 }}>Tiền xăng là tùy lòng hảo tâm</div>
                    <div style={{ fontSize: '0.8rem', color: '#52525B', lineHeight: 1.45 }}>
                      Lúc lên xe hoặc tới nơi, bạn có thể gửi lại chút tiền xăng tượng trưng hoặc mời chủ xe ly cà phê sáng.
                    </div>
                  </div>
                </div>

                <div style={{ display: 'flex', gap: '0.85rem', marginBottom: '0.85rem' }}>
                  <div style={{ width: '24px', height: '24px', borderRadius: '50%', background: 'var(--color-primary-blue)', border: '1.5px solid #000000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.75rem', fontWeight: 900, flexShrink: 0 }}>2</div>
                  <div>
                    <div style={{ fontSize: '0.825rem', fontWeight: 800 }}>Có mặt đúng giờ tại điểm hẹn</div>
                    <div style={{ fontSize: '0.8rem', color: '#52525B', lineHeight: 1.45 }}>
                      Đến trước 3–5 phút ở điểm đón ven đường, không để chủ xe dừng lâu gây cản trở giao thông.
                    </div>
                  </div>
                </div>

                <div style={{ display: 'flex', gap: '0.85rem' }}>
                  <div style={{ width: '24px', height: '24px', borderRadius: '50%', background: 'var(--color-primary-blue)', border: '1.5px solid #000000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.75rem', fontWeight: 900, flexShrink: 0 }}>3</div>
                  <div>
                    <div style={{ fontSize: '0.825rem', fontWeight: 800 }}>Giữ gìn không gian xe sạch sẽ</div>
                    <div style={{ fontSize: '0.8rem', color: '#52525B', lineHeight: 1.45 }}>
                      Không mang đồ ăn có mùi nồng, không hút thuốc và đóng mở cửa xe nhẹ nhàng.
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Blue Card */}
            <div className="roomio-blue-card">
              <h4 style={{ fontSize: '1rem', fontWeight: 900, color: '#1E3A8A', marginBottom: '0.5rem' }}>An tâm pháp lý tuyệt đối</h4>
              <p style={{ fontSize: '0.825rem', color: '#1E3A8A', lineHeight: 1.55, fontWeight: 500 }}>
                Vì app <strong>không bán vé, không mở ví giữ tiền, không ấn định giá và không thu phí trung gian</strong>, 
                đây hoàn toàn là việc đi nhờ xe tự nguyện giữa các cá nhân cùng công sở. 
                Không vi phạm quy định kinh doanh vận tải hành khách trái phép theo pháp luật Việt Nam.
              </p>
            </div>
          </div>
        </div>

        {/* Footer / Account Callout */}
        <div className="roomio-card" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem' }}>
          <div>
            <h4 style={{ fontSize: '0.95rem', fontWeight: 800 }}>Tài khoản thử nghiệm cộng đồng & Quản trị</h4>
            <p style={{ fontSize: '0.8rem', color: '#52525B' }}>Email: <strong>admin@carpooling.vn</strong> · Mật khẩu: <strong>password</strong></p>
          </div>
          <div style={{ display: 'flex', gap: '0.75rem' }}>
            <Link to="/login" className="roomio-button-white">
              <span>Đăng nhập</span>
            </Link>
            <Link to="/dashboard" className="roomio-button">
              <span>Mở Bảng điều khiển (Dashboard)</span>
            </Link>
          </div>
        </div>
      </div>
    </>
  );
};
