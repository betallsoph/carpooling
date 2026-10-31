export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'driver' | 'passenger';
  phone?: string;
}

export interface Vehicle {
  id: number;
  make: string;
  model: string;
  color?: string;
  plate_number: string;
}

export interface DriverProfile {
  id: number;
  license_number: string;
  rating_avg: number;
  rating_count: number;
  user: User;
}

export interface Trip {
  id: number;
  driver_profile_id: number;
  vehicle_id: number;
  date: string;
  departure_time: string;
  status: string;
  origin_name: string;
  origin_address: string;
  destination_name: string;
  destination_address: string;
  total_distance_km: number;
  estimated_duration_minutes: number;
  total_seats: number;
  available_seats: number;
  driver_profile?: DriverProfile;
  vehicle?: Vehicle;
}

export interface Booking {
  id: number;
  trip_instance_id: number;
  passenger_id: number;
  seats_requested: number;
  pickup_name: string;
  pickup_address: string;
  dropoff_name: string;
  dropoff_address: string;
  status: string;
  note?: string;
  passenger?: User;
  trip_instance?: Trip;
}

export interface Stats {
  active_trips_count: number;
  available_seats_count: number;
  total_bookings_count: number;
  pending_bookings_count: number;
  platform_fee: number;
}

const API_BASE = '/api/v1';

export async function fetchTrips(): Promise<Trip[]> {
  try {
    const res = await fetch(`${API_BASE}/trips`);
    if (!res.ok) throw new Error('Failed to fetch trips');
    const json = await res.json();
    return json.data || [];
  } catch (err) {
    console.warn('API error, returning mock trips for offline demo:', err);
    return [
      {
        id: 1,
        driver_profile_id: 1,
        vehicle_id: 1,
        date: '2026-11-02',
        departure_time: '06:45:00',
        status: 'scheduled',
        origin_name: 'TT Hành chính Bình Dương (Thủ Dầu Một)',
        origin_address: 'Số 1 Lê Lợi, P. Hòa Phú, TP. Thủ Dầu Một',
        destination_name: 'Tòa nhà Bitexco / Quận 1 TP.HCM',
        destination_address: '02 Hải Triều, P. Bến Nghé, Quận 1',
        total_distance_km: 34.5,
        estimated_duration_minutes: 65,
        total_seats: 3,
        available_seats: 2,
        driver_profile: {
          id: 1,
          license_number: '790123456789',
          rating_avg: 4.95,
          rating_count: 24,
          user: {
            id: 2,
            name: 'Nguyễn Minh Tuấn',
            email: 'driver@carpooling.vn',
            role: 'driver',
            phone: '0912345678'
          }
        },
        vehicle: {
          id: 1,
          make: 'Toyota',
          model: 'Vios G 2023',
          color: 'Trắng',
          plate_number: '61A-889.92'
        }
      }
    ];
  }
}

export async function searchCorridorTrips(params: {
  pickup_lng: number;
  pickup_lat: number;
  dropoff_lng: number;
  dropoff_lat: number;
  seats?: number;
}): Promise<any[]> {
  const query = new URLSearchParams({
    pickup_lng: params.pickup_lng.toString(),
    pickup_lat: params.pickup_lat.toString(),
    dropoff_lng: params.dropoff_lng.toString(),
    dropoff_lat: params.dropoff_lat.toString(),
    seats: (params.seats || 1).toString(),
  });

  try {
    const res = await fetch(`${API_BASE}/trips/search?${query}`);
    if (!res.ok) throw new Error('Search failed');
    const json = await res.json();
    return json.data || [];
  } catch {
    return [
      {
        id: 1,
        date: '2026-11-02',
        departure_time: '06:45 sáng (T2–T6)',
        origin: { name: 'TT Hành chính Bình Dương', address: 'QL13' },
        destination: { name: 'Tòa nhà Bitexco Quận 1', address: 'Quận 1' },
        driver: { name: 'Nguyễn Minh Tuấn', rating_avg: 4.95, vehicle: 'Toyota Vios G (Trắng) · 61A-889.92' },
        available_seats: 2,
        cost_policy: 'Tiền xăng tùy lòng'
      }
    ];
  }
}

export async function fetchStats(): Promise<Stats> {
  try {
    const res = await fetch(`${API_BASE}/stats`);
    if (!res.ok) throw new Error('Failed to fetch stats');
    const json = await res.json();
    return json.data;
  } catch {
    return {
      active_trips_count: 2,
      available_seats_count: 5,
      total_bookings_count: 3,
      pending_bookings_count: 1,
      platform_fee: 0
    };
  }
}

export async function fetchBookings(): Promise<Booking[]> {
  try {
    const res = await fetch(`${API_BASE}/bookings`);
    if (!res.ok) throw new Error('Failed to fetch bookings');
    const json = await res.json();
    return json.data || [];
  } catch {
    return [
      {
        id: 1,
        trip_instance_id: 1,
        passenger_id: 3,
        seats_requested: 1,
        pickup_name: 'Lotte Mart Bình Dương',
        pickup_address: 'Đại lộ Bình Dương',
        dropoff_name: 'Ngã tư Hàng Xanh',
        dropoff_address: 'Điện Biên Phủ',
        status: 'accepted',
        note: 'Em đứng ngay cổng vào bãi xe Lotte Mart ạ',
        passenger: {
          id: 3,
          name: 'Lê Phương Thảo',
          email: 'passenger@carpooling.vn',
          role: 'passenger',
          phone: '0938112233'
        }
      }
    ];
  }
}

export async function createTrip(data: {
  origin_name: string;
  destination_name: string;
  departure_time: string;
  available_seats: number;
  vehicle_model?: string;
  vehicle_plate?: string;
}): Promise<Trip> {
  const res = await fetch(`${API_BASE}/trips`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });

  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.message || 'Lỗi lưu chuyến đi');
  }

  const json = await res.json();
  return json.data;
}
