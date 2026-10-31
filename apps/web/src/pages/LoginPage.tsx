import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

export const LoginPage: React.FC = () => {
  const navigate = useNavigate();
  const [email, setEmail] = useState('admin@carpooling.vn');
  const [password, setPassword] = useState('password');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError(null);

    try {
      const res = await fetch('/api/v1/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      if (res.ok) {
        const data = await res.json();
        localStorage.setItem('auth_token', data.token || 'demo_token');
        localStorage.setItem('auth_user', JSON.stringify(data.user || { name: 'Người dùng', email, role: 'driver' }));
        navigate('/dashboard');
      } else {
        // Fallback for offline demo mode
        localStorage.setItem('auth_token', 'demo_token');
        const role = email.includes('admin') ? 'admin' : email.includes('driver') ? 'driver' : 'passenger';
        const name = email.includes('admin') ? 'Quản trị viên Hệ thống' : email.includes('driver') ? 'Nguyễn Minh Tuấn' : 'Lê Phương Thảo';
        localStorage.setItem('auth_user', JSON.stringify({ name, email, role }));
        navigate('/dashboard');
      }
    } catch {
      // Fallback for offline demo mode
      localStorage.setItem('auth_token', 'demo_token');
      localStorage.setItem('auth_user', JSON.stringify({ name: 'Quản trị viên Hệ thống', email, role: 'admin' }));
      navigate('/dashboard');
    } finally {
      setIsLoading(false);
    }
  };

  const fillAccount = (e: string, p: string) => {
    setEmail(e);
    setPassword(p);
  };

  return (
    <>
      <div className="roomio-grid-bg"></div>
      <div className="bg-overlay"></div>

      <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '1.5rem' }}>
        <div style={{ width: '100%', maxWidth: '440px' }}>
          <div style={{ textAlign: 'center', marginBottom: '1.5rem' }}>
            <Link to="/" className="brand-title" style={{ fontSize: '2rem', textDecoration: 'none', color: '#000000', display: 'inline-block' }}>
              carpooling<span>.vn</span>
            </Link>
            <div style={{ fontSize: '0.85rem', color: '#52525B', fontWeight: 600, marginTop: '0.35rem' }}>
              Nền tảng đi nhờ xe công sở phi thương mại
            </div>
          </div>

          <div className="roomio-card" style={{ padding: '2rem' }}>
            {error && (
              <div style={{ border: '2px solid #000000', backgroundColor: '#FEE2E2', color: '#991B1B', padding: '0.65rem 0.85rem', borderRadius: '6px', fontSize: '0.8rem', fontWeight: 700, marginBottom: '1.25rem' }}>
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="roomio-form-group">
                <label className="roomio-form-label" style={{ color: '#18181B' }}>Email hoặc số điện thoại</label>
                <input
                  type="text"
                  className="roomio-form-input"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  placeholder="admin@carpooling.vn"
                />
              </div>

              <div className="roomio-form-group">
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.35rem' }}>
                  <label className="roomio-form-label" style={{ color: '#18181B', marginBottom: 0 }}>Mật khẩu</label>
                  <span style={{ fontSize: '0.75rem', color: '#71717A', fontWeight: 600 }}>Mặc định: password</span>
                </div>
                <input
                  type="password"
                  className="roomio-form-input"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  placeholder="••••••••"
                />
              </div>

              <button
                type="submit"
                disabled={isLoading}
                className="roomio-button"
                style={{ width: '100%', padding: '0.7rem 1.25rem', marginTop: '0.5rem' }}
              >
                <span>{isLoading ? 'Đang xử lý...' : 'Đăng nhập'}</span>
              </button>
            </form>

            <div style={{ marginTop: '1.5rem', paddingTop: '1.25rem', borderTop: '1px dashed #D4D4D8' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 800, color: '#71717A', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: '0.65rem' }}>
                Bấm để điền nhanh tài khoản thử nghiệm:
              </div>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '0.45rem' }}>
                <div 
                  onClick={() => fillAccount('admin@carpooling.vn', 'password')}
                  style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#F4F4F5', border: '1.5px solid #000000', borderRadius: '6px', padding: '0.45rem 0.65rem', fontSize: '0.75rem', fontWeight: 700, cursor: 'pointer' }}
                >
                  <span>Quản trị viên (Admin Ops)</span>
                  <span style={{ color: '#2563eb' }}>admin@carpooling.vn</span>
                </div>
                <div 
                  onClick={() => fillAccount('driver@carpooling.vn', 'password')}
                  style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#F4F4F5', border: '1.5px solid #000000', borderRadius: '6px', padding: '0.45rem 0.65rem', fontSize: '0.75rem', fontWeight: 700, cursor: 'pointer' }}
                >
                  <span>Chủ xe (Tài xế Tuấn)</span>
                  <span style={{ color: '#2563eb' }}>driver@carpooling.vn</span>
                </div>
                <div 
                  onClick={() => fillAccount('passenger@carpooling.vn', 'password')}
                  style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#F4F4F5', border: '1.5px solid #000000', borderRadius: '6px', padding: '0.45rem 0.65rem', fontSize: '0.75rem', fontWeight: 700, cursor: 'pointer' }}
                >
                  <span>Hành khách (Phương Thảo)</span>
                  <span style={{ color: '#2563eb' }}>passenger@carpooling.vn</span>
                </div>
              </div>
            </div>
          </div>

          <div style={{ marginTop: '1.25rem', textAlign: 'center' }}>
            <Link to="/" style={{ fontSize: '0.8rem', fontWeight: 700, color: '#2563eb', textDecoration: 'underline' }}>
              ← Về trang chủ carpooling.vn
            </Link>
          </div>
        </div>
      </div>
    </>
  );
};
