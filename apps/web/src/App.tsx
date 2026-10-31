import React, { useEffect } from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import { DashboardPage } from './pages/DashboardPage';
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
        <Route path="/dashboard" element={<DashboardPage />} />
      </Routes>
    </BrowserRouter>
  );
};

export default App;
