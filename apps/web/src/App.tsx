import React, { useEffect } from 'react';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { DashboardLayout } from './layouts/DashboardLayout';
import { BookingsPage } from './pages/dashboard/BookingsPage';
import { CreateTripPage } from './pages/dashboard/CreateTripPage';
import { DashboardOverviewPage } from './pages/dashboard/DashboardOverviewPage';
import { MapSearchPage } from './pages/dashboard/MapSearchPage';
import { HomePage } from './pages/HomePage';
import { LoginPage } from './pages/LoginPage';

export const App: React.FC = () => {
  useEffect(() => {
    // Roomio exact radial circle-wipe hover effect
    const handlePointerOver = (event: PointerEvent) => {
      const target = event.target as HTMLElement | null;
      const action = target?.closest('.roomio-button, .roomio-button-white') as HTMLElement | null;
      if (!action) return;
      const bounds = action.getBoundingClientRect();
      action.style.setProperty('--hover-x', `${event.clientX - bounds.left}px`);
      action.style.setProperty('--hover-y', `${event.clientY - bounds.top}px`);
    };

    document.addEventListener('pointerover', handlePointerOver);
    return () => document.removeEventListener('pointerover', handlePointerOver);
  }, []);

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<LoginPage />} />

        {/* Dedicated Pages for each sidebar section */}
        <Route path="/dashboard" element={<DashboardLayout />}>
          <Route index element={<DashboardOverviewPage />} />
          <Route path="create-trip" element={<CreateTripPage />} />
          <Route path="bookings" element={<BookingsPage />} />
          <Route path="map-search" element={<MapSearchPage />} />
        </Route>

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
};

export default App;
