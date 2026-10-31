<?php

namespace Tests\Feature;

use App\Models\TripInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripSearchTest extends TestCase
{
    public function test_corridor_matching_search_api_returns_matching_rides(): void
    {
        // Điểm đón: Lotte Mart Thuận An Bình Dương (106.7025, 10.9021)
        // Điểm trả: Ngã tư Hàng Xanh Bình Thạnh (106.7118, 10.8012)
        $response = $this->getJson('/api/v1/trips/search?' . http_build_query([
            'pickup_lng' => 106.7025,
            'pickup_lat' => 10.9021,
            'dropoff_lng' => 106.7118,
            'dropoff_lat' => 10.8012,
            'seats' => 1,
            'buffer_meters' => 2000,
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_corridor_matching_discards_reverse_direction(): void
    {
        // Khách đi ngược chiều xe chạy: từ Hàng Xanh về Lotte Mart
        $response = $this->getJson('/api/v1/trips/search?' . http_build_query([
            'pickup_lng' => 106.7118,
            'pickup_lat' => 10.8012,
            'dropoff_lng' => 106.7025,
            'dropoff_lat' => 10.9021,
            'seats' => 1,
            'buffer_meters' => 2000,
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('count', 0);
    }

    public function test_user_can_login_and_retrieve_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'passenger@carpooling.vn',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'token',
                'user' => ['id', 'name', 'email', 'phone', 'role'],
            ]);
    }
}
