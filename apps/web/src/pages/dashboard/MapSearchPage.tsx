import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { searchTrips, type SearchResultTrip } from '../../lib/api';

export const MapSearchPage: React.FC = () => {
  const [pickupName, setPickupName] = useState('Lotte Mart Bình Dương (Thuận An)');
  const [pickupLng, setPickupLng] = useState(106.7025);
  const [pickupLat, setPickupLat] = useState(10.9021);

  const [dropoffName, setDropoffName] = useState('Ngã tư Hàng Xanh / Bitexco Quận 1');
  const [dropoffLng, setDropoffLng] = useState(106.7118);
  const [dropoffLat, setDropoffLat] = useState(10.8012);

  const [bufferMeters, setBufferMeters] = useState(2000);
  const [isSearching, setIsSearching] = useState(false);
  const [searchResults, setSearchResults] = useState<SearchResultTrip[]>([]);
  const [hasSearched, setHasSearched] = useState(false);
  const [directionReversed, setDirectionReversed] = useState(false);

  const handleReverseDirection = () => {
    const tempName = pickupName;
    const tempLng = pickupLng;
    const tempLat = pickupLat;

    setPickupName(dropoffName);
    setPickupLng(dropoffLng);
    setPickupLat(dropoffLat);

    setDropoffName(tempName);
    setDropoffLng(tempLng);
    setDropoffLat(tempLat);

    setDirectionReversed(!directionReversed);
    setSearchResults([]);
    setHasSearched(false);
  };

  const handleSearch = async () => {
    setIsSearching(true);
    setHasSearched(true);

    try {
      const res = await searchTrips({
        pickup_lng: pickupLng,
        pickup_lat: pickupLat,
        dropoff_lng: dropoffLng,
        dropoff_lat: dropoffLat,
        buffer_meters: bufferMeters,
      });
      setSearchResults(res.data);
    } catch {
      // Local demo fallback if network fails
      if (!directionReversed) {
        setSearchResults([
          {
            id: 1,
            date: '2026-11-02',
            departure_time: '06:45:00',
            origin: { name: 'TT Hành chính Bình Dương', address: 'Thủ Dầu Một' },
            destination: { name: 'Tòa nhà Bitexco Quận 1', address: 'Hải Triều, Bến Nghé' },
            total_distance_km: 30.0,
            estimated_duration_minutes: 50,
            available_seats: 3,
            driver: {
              name: 'Nguyễn Minh Tuấn',
              rating_avg: 4.95,
              rating_count: 18,
              rides_completed: 42,
            },
            vehicle: {
              make: 'Toyota',
              model: 'Vios G',
              color: 'Trắng',
              plate_number: '61A-889.92',
            },
          },
        ]);
      } else {
        setSearchResults([]);
      }
    } finally {
      setIsSearching(false);
    }
  };

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
            Không gian địa lý PostGIS
          </div>
          <h1 style={{ fontSize: '1.75rem', fontWeight: 900, letterSpacing: '-0.5px' }}>
            Tìm xe trên bản đồ hành lang
          </h1>
        </div>

        <div style={{ display: 'flex', gap: '0.75rem' }}>
          <Link to="/dashboard" className="roomio-button-white">
            <span>Về tổng quan</span>
          </Link>
          <Link to="/dashboard/create-trip" className="roomio-button">
            <span>Đăng lịch trình xe</span>
          </Link>
        </div>
      </div>

      {/* Main Grid: Left Controls, Right Map */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1.35fr', gap: '1.75rem', marginBottom: '2rem' }}>
        {/* Left: Search Parameters Form */}
        <div className="roomio-card">
          <h2 style={{ fontSize: '1.15rem', fontWeight: 900, marginBottom: '1.25rem', borderBottom: '2px solid #000000', paddingBottom: '0.75rem' }}>
            Tham số tìm kiếm hành lang
          </h2>

          <div className="roomio-form-group">
            <label className="roomio-form-label">Điểm đón của bạn (Pickup Point A)</label>
            <input
              type="text"
              className="roomio-form-input"
              value={pickupName}
              onChange={e => setPickupName(e.target.value)}
            />
            <div style={{ fontSize: '0.75rem', color: '#71717A', fontWeight: 600, marginTop: '0.35rem' }}>
              Tọa độ: [{pickupLng.toFixed(4)}, {pickupLat.toFixed(4)}]
            </div>
          </div>

          <div style={{ textAlign: 'center', margin: '0.75rem 0' }}>
            <button
              type="button"
              onClick={handleReverseDirection}
              style={{
                padding: '0.4rem 0.85rem',
                fontSize: '0.75rem',
                fontWeight: 800,
                borderRadius: '6px',
                border: '1.5px solid #000000',
                backgroundColor: directionReversed ? '#FEE2E2' : '#F4F4F5',
                color: directionReversed ? '#991B1B' : '#000000',
                cursor: 'pointer',
              }}
            >
              {directionReversed ? '⇄ Đang thử chiều ngược (Ngược xe)' : '⇄ Đổi chiều điểm đón / trả'}
            </button>
          </div>

          <div className="roomio-form-group">
            <label className="roomio-form-label">Điểm trả / Nơi làm việc (Dropoff Point B)</label>
            <input
              type="text"
              className="roomio-form-input"
              value={dropoffName}
              onChange={e => setDropoffName(e.target.value)}
            />
            <div style={{ fontSize: '0.75rem', color: '#71717A', fontWeight: 600, marginTop: '0.35rem' }}>
              Tọa độ: [{dropoffLng.toFixed(4)}, {dropoffLat.toFixed(4)}]
            </div>
          </div>

          <div className="roomio-form-group">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.35rem' }}>
              <label className="roomio-form-label" style={{ marginBottom: 0 }}>
                Bán kính lệch hành lang: {bufferMeters}m
              </label>
              <span style={{ fontSize: '0.75rem', fontWeight: 700, color: '#52525B' }}>
                {(bufferMeters / 1000).toFixed(1)} km
              </span>
            </div>
            <input
              type="range"
              min="500"
              max="4000"
              step="250"
              value={bufferMeters}
              onChange={e => setBufferMeters(Number(e.target.value))}
              style={{ width: '100%', accentColor: '#2563eb', cursor: 'pointer' }}
            />
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: '#71717A', fontWeight: 600, marginTop: '0.2rem' }}>
              <span>500m (Gần sát trục)</span>
              <span>2000m (Tiêu chuẩn)</span>
              <span>4000m (Rộng)</span>
            </div>
          </div>

          <button
            type="button"
            onClick={handleSearch}
            disabled={isSearching}
            className="roomio-button"
            style={{ width: '100%', justifyContent: 'center', padding: '0.75rem' }}
          >
            <span>{isSearching ? 'Đang quét PostGIS...' : 'Quét tìm chuyến xe phù hợp'}</span>
          </button>
        </div>

        {/* Right: Interactive SVG Map Corridor Visualizer */}
        <div className="roomio-window">
          <div className="roomio-window-bar">
            <div className="roomio-window-dots">
              <span className="roomio-window-dot dot-red"></span>
              <span className="roomio-window-dot dot-yellow"></span>
              <span className="roomio-window-dot dot-green"></span>
            </div>
            <div className="window-title">ban-do-hanh-lang.postgis-vector</div>
            <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#2563eb' }}>Buffer {bufferMeters}m</div>
          </div>

          <div style={{ padding: '1.25rem', backgroundColor: '#FAFAFA' }}>
            {/* SVG Visual Canvas */}
            <div
              style={{
                width: '100%',
                height: '320px',
                border: '2px solid #000000',
                borderRadius: '6px',
                backgroundColor: '#FFFFFF',
                position: 'relative',
                overflow: 'hidden',
              }}
            >
              <svg width="100%" height="100%" viewBox="0 0 500 320" preserveAspectRatio="none">
                {/* Coordinate Grid */}
                <defs>
                  <pattern id="gridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#E4E4E7" strokeWidth="1" />
                  </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#gridPattern)" />

                {/* Corridor Buffer Zone (ST_Buffer) */}
                <path
                  d="M 60 40 L 440 280"
                  stroke="#BFDBFE"
                  strokeWidth={bufferMeters / 30}
                  strokeLinecap="round"
                  opacity="0.6"
                />

                {/* Main Commute Road Axis (LineString) */}
                <path
                  d="M 60 40 L 440 280"
                  stroke="#000000"
                  strokeWidth="5"
                  strokeLinecap="round"
                />

                {/* Direction Arrows */}
                <circle cx="250" cy="160" r="14" fill="#2563eb" stroke="#000000" strokeWidth="2" />
                <path d="M 244 154 L 256 160 L 244 166" fill="none" stroke="#FFFFFF" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" />

                {/* Road Labels */}
                <text x="80" y="35" fontSize="11" fontWeight="800" fill="#09090B">Bình Dương (QL13)</text>
                <text x="330" y="295" fontSize="11" fontWeight="800" fill="#09090B">TP.HCM (Q1 / Bitexco)</text>

                {/* Point A Marker */}
                <circle cx={directionReversed ? 380 : 130} cy={directionReversed ? 240 : 90} r="10" fill="#4338CA" stroke="#000000" strokeWidth="2" />
                <text x={directionReversed ? 375 : 125} y={directionReversed ? 244 : 94} fontSize="11" fontWeight="900" fill="#FFFFFF">A</text>

                {/* Point B Marker */}
                <circle cx={directionReversed ? 130 : 380} cy={directionReversed ? 90 : 240} r="10" fill="#15803D" stroke="#000000" strokeWidth="2" />
                <text x={directionReversed ? 125 : 375} y={directionReversed ? 94 : 244} fontSize="11" fontWeight="900" fill="#FFFFFF">B</text>
              </svg>

              {/* Status Overlay */}
              <div
                style={{
                  position: 'absolute',
                  bottom: '12px',
                  left: '12px',
                  right: '12px',
                  padding: '0.65rem 0.85rem',
                  borderRadius: '6px',
                  border: '1.5px solid #000000',
                  backgroundColor: directionReversed ? '#FEE2E2' : '#DCFCE7',
                  color: directionReversed ? '#991B1B' : '#166534',
                  fontSize: '0.75rem',
                  fontWeight: 800,
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center',
                }}
              >
                <span>
                  {directionReversed
                    ? '⚠️ Ngược chiều xe chạy! (ST_LineLocatePoint A > B)'
                    : '✓ Hợp lệ: Nằm trong hành lang 2000m & cùng chiều di chuyển'}
                </span>
                <span style={{ fontSize: '0.7rem' }}>
                  {directionReversed ? 'PostGIS từ chối ghép' : 'Khớp 1 chuyến'}
                </span>
              </div>
            </div>

            <div style={{ marginTop: '0.85rem', fontSize: '0.75rem', color: '#52525B', lineHeight: 1.5, fontWeight: 500 }}>
              Thuật toán <strong>ST_DWithin</strong> kiểm tra khoảng cách vuông góc từ điểm hẹn A & B đến trục đường xe chạy. Sau đó <strong>ST_LineLocatePoint</strong> xác minh điểm đón xuất hiện trước điểm trả theo hướng đi.
            </div>
          </div>
        </div>
      </div>

      {/* Results Section */}
      {hasSearched && (
        <div className="roomio-window">
          <div className="roomio-window-bar">
            <div className="roomio-window-dots">
              <span className="roomio-window-dot dot-red"></span>
              <span className="roomio-window-dot dot-yellow"></span>
              <span className="roomio-window-dot dot-green"></span>
            </div>
            <div className="window-title">ket-qua-ghep-hanh-lang.result</div>
            <div style={{ fontSize: '0.75rem', fontWeight: 800, color: searchResults.length > 0 ? '#16A34A' : '#EF4444' }}>
              {searchResults.length} chuyến xe phù hợp
            </div>
          </div>

          <div style={{ padding: '1.5rem' }}>
            {searchResults.length === 0 ? (
              <div style={{ textAlign: 'center', padding: '2rem 1rem', border: '2px dashed #000000', borderRadius: '8px' }}>
                <p style={{ fontSize: '1rem', fontWeight: 800, color: '#991B1B', marginBottom: '0.5rem' }}>
                  Không tìm thấy chuyến xe nào phù hợp với yêu cầu này.
                </p>
                <p style={{ fontSize: '0.8rem', color: '#52525B', fontWeight: 500 }}>
                  {directionReversed
                    ? 'Bạn đang tìm xe đi ngược hướng di chuyển của tuyến buổi sáng. Hãy đổi lại chiều đón/trả.'
                    : 'Hãy thử tăng bán kính lệch hành lang lên 2500m–3000m hoặc chọn một điểm đón gần trục QL13 hơn.'}
                </p>
              </div>
            ) : (
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '1.25rem' }}>
                {searchResults.map(trip => (
                  <div key={trip.id} className="trip-item">
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '0.75rem' }}>
                      <div>
                        <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#52525B' }}>
                          Khởi hành {trip.departure_time?.substring(0, 5)}
                        </div>
                        <div style={{ fontSize: '1.05rem', fontWeight: 900, color: '#09090B' }}>
                          {trip.driver.name}
                        </div>
                      </div>
                      <span className="badge-tag badge-green">{trip.available_seats} ghế trống</span>
                    </div>

                    <div style={{ fontSize: '0.8rem', color: '#52525B', marginBottom: '0.85rem', lineHeight: 1.45 }}>
                      Tuyến chính: <strong>{trip.origin.name}</strong> → <strong>{trip.destination.name}</strong>
                    </div>

                    <div style={{ borderTop: '1px dashed #D4D4D8', paddingTop: '0.75rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                      <div style={{ fontSize: '0.75rem', fontWeight: 700, color: '#71717A' }}>
                        {trip.vehicle.make} {trip.vehicle.model} · {trip.vehicle.plate_number}
                      </div>
                      <button
                        className="roomio-button"
                        style={{ padding: '0.35rem 0.75rem', fontSize: '0.8rem' }}
                        onClick={() => alert(`Đã gửi yêu cầu ghép xe đến tài xế ${trip.driver.name}! Hai bạn có thể tự hẹn nhau tại điểm đón.`)}
                      >
                        <span>Xin ghép xe</span>
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};
