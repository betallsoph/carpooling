import React from 'react';
import { Link, NavLink, Outlet, useNavigate } from 'react-router-dom';

export const DashboardLayout: React.FC = () => {
  const navigate = useNavigate();
  const currentUser = JSON.parse(
    localStorage.getItem('auth_user') ||
      '{"name":"Quản trị viên Hệ thống","email":"admin@carpooling.vn","role":"admin"}'
  );

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
        <aside
          style={{
            width: '260px',
            background: 'rgba(255, 255, 255, 0.95)',
            backdropFilter: 'blur(8px)',
            borderRight: '2px solid #000000',
            padding: '1.5rem 1.25rem',
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            position: 'sticky',
            top: 0,
            height: '100vh',
            flexShrink: 0,
          }}
        >
          <div>
            <Link
              to="/"
              style={{
                textDecoration: 'none',
                color: '#000000',
                display: 'block',
                marginBottom: '2rem',
              }}
            >
              <div className="brand-title">
                carpooling<span>.vn</span>
              </div>
              <div className="brand-badge">Commuter Carpooling</div>
            </Link>

            <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
              <NavLink
                to="/dashboard"
                end
                className={({ isActive }) => `sidebar-nav-item ${isActive ? 'active' : ''}`}
              >
                Tổng quan chuyến xe
              </NavLink>

              <NavLink
                to="/dashboard/create-trip"
                className={({ isActive }) => `sidebar-nav-item ${isActive ? 'active' : ''}`}
              >
                Đăng lịch trình xe
              </NavLink>

              <NavLink
                to="/dashboard/bookings"
                className={({ isActive }) => `sidebar-nav-item ${isActive ? 'active' : ''}`}
              >
                Yêu cầu ghép xe
              </NavLink>

              <NavLink
                to="/dashboard/map-search"
                className={({ isActive }) => `sidebar-nav-item ${isActive ? 'active' : ''}`}
              >
                Tìm xe trên bản đồ
              </NavLink>
            </nav>
          </div>

          <div
            style={{
              border: '2px solid #000000',
              borderRadius: '8px',
              background: '#FFFFFF',
              padding: '1rem',
              boxShadow: 'var(--shadow-primary)',
            }}
          >
            <div
              style={{
                fontSize: '0.875rem',
                fontWeight: 800,
                color: '#09090B',
                marginBottom: '0.2rem',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'nowrap',
              }}
            >
              {currentUser.name}
            </div>
            <div
              style={{
                fontSize: '0.7rem',
                fontWeight: 700,
                color: '#52525B',
                marginBottom: '0.75rem',
              }}
            >
              {currentUser.role === 'admin'
                ? 'Quản trị viên hệ thống'
                : currentUser.role === 'driver'
                ? 'Chủ xe văn phòng'
                : 'Hành khách'}{' '}
              · {currentUser.email}
            </div>
            <button
              onClick={handleLogout}
              className="roomio-button-white"
              style={{ width: '100%', padding: '0.4rem 0.75rem', fontSize: '0.8rem' }}
            >
              <span>Đăng xuất</span>
            </button>
          </div>
        </aside>

        {/* Main Routed Content Area */}
        <main style={{ flex: 1, padding: '2rem', maxWidth: '1200px', overflowY: 'auto' }}>
          <Outlet />
        </main>
      </div>
    </>
  );
};
