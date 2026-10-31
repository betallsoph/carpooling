import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { createTrip } from '../../lib/api';

export const CreateTripPage: React.FC = () => {
  const navigate = useNavigate();

  const [originName, setOriginName] = useState('TT Hành chính Bình Dương (Thủ Dầu Một)');
  const [destinationName, setDestinationName] = useState('Tòa nhà Bitexco / Quận 1 TP.HCM');
  const [departureTime, setDepartureTime] = useState('06:45');
  const [availableSeats, setAvailableSeats] = useState(3);
  const [vehicleModel, setVehicleModel] = useState('Toyota Vios G 2023');
  const [vehiclePlate, setVehiclePlate] = useState('61A-889.92');
  const [selectedDays, setSelectedDays] = useState<string[]>(['T2', 'T3', 'T4', 'T5', 'T6']);
  const [notes, setNotes] = useState('Đi làm đúng giờ, trên xe không hút thuốc, nghe nhạc nhẹ.');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  const toggleDay = (day: string) => {
    setSelectedDays(prev =>
      prev.includes(day) ? prev.filter(d => d !== day) : [...prev, day]
    );
  };

  const handleSelectOrigin = (val: string) => setOriginName(val);
  const handleSelectDestination = (val: string) => setDestinationName(val);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    setErrorMessage(null);

    try {
      await createTrip({
        origin_name: originName,
        destination_name: destinationName,
        departure_time: departureTime,
        available_seats: availableSeats,
        vehicle_model: vehicleModel,
        vehicle_plate: vehiclePlate,
      });

      // Navigate back to overview upon success
      navigate('/dashboard', {
        state: { success: 'Đã mở chuyến xe đi làm thành công! Tuyến đường đã sẵn sàng để đồng nghiệp ghép xe.' },
      });
    } catch {
      setErrorMessage('Không thể lưu chuyến xe lúc này. Vui lòng kiểm tra lại kết nối API.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div>
      {/* Top Bar / Header */}
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
            <Link to="/dashboard" style={{ color: '#2563eb', textDecoration: 'none', fontWeight: 700 }}>
              ← Quay lại Tổng quan
            </Link>
          </div>
          <h1 style={{ fontSize: '1.75rem', fontWeight: 900, letterSpacing: '-0.5px' }}>
            Đăng lịch trình xe đi làm
          </h1>
        </div>

        <div style={{ display: 'flex', gap: '0.75rem' }}>
          <Link to="/dashboard" className="roomio-button-white">
            <span>Hủy bỏ</span>
          </Link>
          <button
            type="button"
            onClick={handleSubmit}
            disabled={isSubmitting}
            className="roomio-button"
          >
            <span>{isSubmitting ? 'Đang lưu...' : 'Mở chuyến xe đi làm'}</span>
          </button>
        </div>
      </div>

      {errorMessage && (
        <div
          style={{
            border: '2px solid #000000',
            backgroundColor: '#FEE2E2',
            color: '#991B1B',
            padding: '0.85rem 1rem',
            borderRadius: '6px',
            fontSize: '0.85rem',
            fontWeight: 700,
            marginBottom: '1.5rem',
          }}
        >
          {errorMessage}
        </div>
      )}

      {/* Main Form Layout (2 Columns) */}
      <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 0.8fr', gap: '1.75rem' }}>
        {/* Left Column: Core Route & Schedule */}
        <div className="roomio-card">
          <h2 style={{ fontSize: '1.15rem', fontWeight: 900, marginBottom: '1.25rem', borderBottom: '2px solid #000000', paddingBottom: '0.75rem' }}>
            Thông tin lộ trình & Giờ giấc
          </h2>

          <form onSubmit={handleSubmit}>
            {/* Origin */}
            <div className="roomio-form-group">
              <label className="roomio-form-label">Điểm đón xuất phát (Pickup)</label>
              <input
                type="text"
                className="roomio-form-input"
                value={originName}
                onChange={e => setOriginName(e.target.value)}
                required
                placeholder="VD: TT Hành chính Bình Dương, Lotte Mart Thuận An..."
              />
              {/* Quick Suggestion Chips */}
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.4rem', marginTop: '0.5rem' }}>
                <span
                  onClick={() => handleSelectOrigin('TT Hành chính Bình Dương')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + TT Hành chính BD
                </span>
                <span
                  onClick={() => handleSelectOrigin('Lotte Mart Thuận An (QL13)')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + Lotte Mart Thuận An
                </span>
                <span
                  onClick={() => handleSelectOrigin('Khu đô thị Vạn Phúc (Thủ Đức)')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + KĐT Vạn Phúc
                </span>
              </div>
            </div>

            {/* Destination */}
            <div className="roomio-form-group">
              <label className="roomio-form-label">Điểm đến làm việc (Destination)</label>
              <input
                type="text"
                className="roomio-form-input"
                value={destinationName}
                onChange={e => setDestinationName(e.target.value)}
                required
                placeholder="VD: Tòa nhà Bitexco Quận 1, Landmark 81..."
              />
              {/* Quick Suggestion Chips */}
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.4rem', marginTop: '0.5rem' }}>
                <span
                  onClick={() => handleSelectDestination('Tòa nhà Bitexco / Quận 1')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + Bitexco Quận 1
                </span>
                <span
                  onClick={() => handleSelectDestination('Landmark 81 Bình Thạnh')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + Landmark 81
                </span>
                <span
                  onClick={() => handleSelectDestination('Khu Công nghệ cao Quận 9')}
                  style={{ fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', border: '1px solid #000000', borderRadius: '4px', background: '#F4F4F5', cursor: 'pointer' }}
                >
                  + Khu CNC Q9
                </span>
              </div>
            </div>

            {/* Time & Seats Grid */}
            <div className="form-grid-2">
              <div className="roomio-form-group">
                <label className="roomio-form-label">Giờ xe khởi hành</label>
                <input
                  type="time"
                  className="roomio-form-input"
                  value={departureTime}
                  onChange={e => setDepartureTime(e.target.value)}
                  required
                />
              </div>

              <div className="roomio-form-group">
                <label className="roomio-form-label">Số ghế trống sẵn sàng</label>
                <div style={{ display: 'flex', gap: '0.5rem' }}>
                  {[1, 2, 3, 4].map(num => (
                    <button
                      key={num}
                      type="button"
                      onClick={() => setAvailableSeats(num)}
                      style={{
                        flex: 1,
                        padding: '0.65rem 0',
                        fontSize: '0.85rem',
                        fontWeight: 800,
                        borderRadius: '6px',
                        border: '2px solid #000000',
                        backgroundColor: availableSeats === num ? '#BFDBFE' : '#FFFFFF',
                        boxShadow: availableSeats === num ? '2px 2px 0px -1px #000' : 'none',
                        cursor: 'pointer',
                      }}
                    >
                      {num} ghế
                    </button>
                  ))}
                </div>
              </div>
            </div>

            {/* Recurring Days Selector */}
            <div className="roomio-form-group">
              <label className="roomio-form-label">Lịch trình lặp lại trong tuần</label>
              <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                {['T2', 'T3', 'T4', 'T5', 'T6', 'T7'].map(day => {
                  const isChecked = selectedDays.includes(day);
                  return (
                    <button
                      key={day}
                      type="button"
                      onClick={() => toggleDay(day)}
                      style={{
                        padding: '0.5rem 0.85rem',
                        fontSize: '0.8rem',
                        fontWeight: 800,
                        borderRadius: '6px',
                        border: '2px solid #000000',
                        backgroundColor: isChecked ? '#BFDBFE' : '#FFFFFF',
                        boxShadow: isChecked ? '2px 2px 0px -1px #000' : 'none',
                        cursor: 'pointer',
                      }}
                    >
                      {day}
                    </button>
                  );
                })}
              </div>
            </div>

            {/* Notes */}
            <div className="roomio-form-group">
              <label className="roomio-form-label">Ghi chú cho bạn đi chung</label>
              <textarea
                className="roomio-form-input"
                rows={3}
                value={notes}
                onChange={e => setNotes(e.target.value)}
                placeholder="VD: Xe không hút thuốc, dừng đúng điểm hẹn, tiền xăng tùy lòng..."
              />
            </div>
          </form>
        </div>

        {/* Right Column: Vehicle & Non-Commercial Policy */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
          {/* Vehicle Box */}
          <div className="roomio-card">
            <h2 style={{ fontSize: '1.15rem', fontWeight: 900, marginBottom: '1.25rem', borderBottom: '2px solid #000000', paddingBottom: '0.75rem' }}>
              Thông tin xe của bạn
            </h2>

            <div className="roomio-form-group">
              <label className="roomio-form-label">Dòng xe / Đời xe</label>
              <input
                type="text"
                className="roomio-form-input"
                value={vehicleModel}
                onChange={e => setVehicleModel(e.target.value)}
                required
                placeholder="VD: Toyota Vios G 2023, Mazda 3..."
              />
            </div>

            <div className="roomio-form-group" style={{ marginBottom: 0 }}>
              <label className="roomio-form-label">Biển số xe (để nhận diện)</label>
              <input
                type="text"
                className="roomio-form-input"
                value={vehiclePlate}
                onChange={e => setVehiclePlate(e.target.value)}
                required
                placeholder="VD: 61A-889.92"
              />
            </div>
          </div>

          {/* Non-Commercial Policy Notice */}
          <div className="roomio-blue-card">
            <h3 style={{ fontSize: '1rem', fontWeight: 900, marginBottom: '0.65rem' }}>
              Văn hóa đi nhờ xe công sở
            </h3>
            <p style={{ fontSize: '0.85rem', color: '#1E3A8A', lineHeight: 1.55, fontWeight: 500, marginBottom: '0.85rem' }}>
              Nền tảng carpooling.vn hoạt động <strong>100% phi lợi nhuận</strong>. Chúng tôi không thu bất kỳ khoản phí nào và <strong>không tích hợp cổng thanh toán</strong>.
            </p>
            <ul style={{ fontSize: '0.8rem', color: '#1E3A8A', paddingLeft: '1.2rem', lineHeight: 1.6, fontWeight: 600 }}>
              <li>Tiền xăng cộ hay bữa sáng do hai bên tự nguyện gửi chủ xe khi đi cùng.</li>
              <li>Chủ xe đi làm hàng ngày, đón đồng nghiệp thuận đường.</li>
              <li>Tôn trọng thời gian, đúng giờ hẹn đã hẹn trước.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};
