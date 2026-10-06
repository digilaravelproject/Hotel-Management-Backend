<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    private function getOrCreateSuperAdmin(): SuperAdmin
    {
        return SuperAdmin::first() ?? SuperAdmin::create([
            'name' => 'Test Super Admin',
            'email' => 'testadmin@hotel.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_dashboard_service_returns_accurate_summary_metrics(): void
    {
        $service = app(DashboardService::class);
        $data = $service->getSuperAdminDashboardData(['status' => 'all']);

        $this->assertArrayHasKey('metrics', $data);
        $this->assertArrayHasKey('recentHotels', $data);
        $this->assertArrayHasKey('recentTransactions', $data);
        $this->assertArrayHasKey('trends', $data);
        $this->assertArrayHasKey('planDistribution', $data);
        $this->assertArrayHasKey('alerts', $data);
        $this->assertArrayHasKey('currentFilter', $data);

        $metrics = $data['metrics'];
        $this->assertArrayHasKey('total_hotels', $metrics);
        $this->assertArrayHasKey('active_hotels', $metrics);
        $this->assertArrayHasKey('pending_approvals', $metrics);
        $this->assertArrayHasKey('total_devices', $metrics);
        $this->assertArrayHasKey('total_revenue', $metrics);
        $this->assertArrayHasKey('total_distributors', $metrics);
    }

    public function test_super_admin_dashboard_route_is_accessible_when_authenticated(): void
    {
        $admin = $this->getOrCreateSuperAdmin();

        $response = $this->actingAs($admin, 'super_admin')
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('super_admin.dashboard');
        $response->assertViewHas(['metrics', 'recentHotels', 'recentTransactions', 'trends', 'planDistribution']);
    }

    public function test_super_admin_dashboard_filters_by_status(): void
    {
        $admin = $this->getOrCreateSuperAdmin();

        $response = $this->actingAs($admin, 'super_admin')
            ->get(route('super-admin.dashboard', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertViewHas('currentFilter', 'pending');
    }
}
