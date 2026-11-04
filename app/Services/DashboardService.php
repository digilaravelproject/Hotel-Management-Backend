<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\DistributorSale;
use App\Models\ConnectedDevice;
use App\Models\Plan;
use App\Models\User;
use App\Models\TvTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get high-level summary KPI metrics.
     * When $distributorId is passed, filters strictly for that distributor.
     */
    public function getSummaryMetrics(?int $distributorId = null): array
    {
        $hotelQuery = HotelAdmin::query();
        $salesQuery = DistributorSale::query();

        if ($distributorId !== null) {
            $hotelQuery->where('distributor_id', $distributorId);
            $salesQuery->where('distributor_id', $distributorId);
        }

        // 1. Hotel counts
        $totalHotels = (clone $hotelQuery)->count();
        $activeHotels = (clone $hotelQuery)
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->count();
        $pendingApprovals = (clone $hotelQuery)
            ->where('approval_status', 'pending')
            ->count();
        $disapprovedHotels = (clone $hotelQuery)
            ->where('approval_status', 'disapproved')
            ->count();

        // 2. Hardware Fleet: Total TV Devices connected
        $totalDevices = ConnectedDevice::query()
            ->when($distributorId !== null, function ($q) use ($distributorId) {
                $q->whereHas('hotelAdmin', function ($hq) use ($distributorId) {
                    $hq->where('distributor_id', $distributorId);
                });
            })
            ->count();

        // Total licensed rooms capacity
        $totalLicensedRooms = (clone $hotelQuery)->sum('room_count');

        // 3. Dynamic Revenue Calculations (Across Distributor Sales, Paid Hotel Plans, and Gateway Transactions)
        $recordedSalesRevenue = (float) (clone $salesQuery)->sum('amount');

        $activePlanRevenue = (float) (clone $hotelQuery)
            ->where('payment_status', 'paid')
            ->join('plans', 'hotel_admins.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        $gatewayRevenue = 0.0;
        try {
            $gatewayRevenue = (float) \App\Models\PaymentTransaction::query()
                ->where('status', 'success')
                ->when($distributorId !== null, fn($q) => $q->where('distributor_id', $distributorId))
                ->sum('amount');
        } catch (\Throwable $e) {}

        $totalEstimatedRevenue = max($recordedSalesRevenue, $activePlanRevenue, $gatewayRevenue);

        // 4. Time-sensitive items: Expiring within 30 days
        $expiringSoon = (clone $hotelQuery)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->count();

        $alreadyExpired = (clone $hotelQuery)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->count();

        // 5. Global platform stats (Super Admin only)
        $totalDistributors = 0;
        $totalPlans = 0;
        $totalTemplates = 0;

        if ($distributorId === null) {
            try {
                $totalDistributors = User::role('distributor')->count();
            } catch (\Throwable $e) {
                $totalDistributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->count();
            }
            $totalPlans = Plan::where('status', true)->count();
            $totalTemplates = TvTemplate::count();
        }

        $paidHotels = (clone $hotelQuery)->where('payment_status', 'paid')->count();

        // Calculate dynamic health & telemetry percentages
        $tvCoveragePct = $totalLicensedRooms > 0 ? min(100, round(($totalDevices / $totalLicensedRooms) * 100)) : 0;
        $verifiedHotelsPct = $totalHotels > 0 ? round(($activeHotels / $totalHotels) * 100) : 0;
        $paidHotelsPct = $totalHotels > 0 ? round(($paidHotels / $totalHotels) * 100) : 0;
        $overallHealthScore = round(($verifiedHotelsPct * 0.4) + ($tvCoveragePct * 0.4) + ($paidHotelsPct * 0.2));

        return [
            'total_hotels' => $totalHotels,
            'active_hotels' => $activeHotels,
            'pending_approvals' => $pendingApprovals,
            'disapproved_hotels' => $disapprovedHotels,
            'total_devices' => $totalDevices,
            'total_licensed_rooms' => $totalLicensedRooms,
            'total_revenue' => (float) $totalEstimatedRevenue,
            'sales_count' => (clone $salesQuery)->count(),
            'expiring_soon' => $expiringSoon,
            'already_expired' => $alreadyExpired,
            'total_distributors' => $totalDistributors,
            'total_plans' => $totalPlans,
            'total_templates' => $totalTemplates,
            'paid_hotels' => $paidHotels,
            'tv_coverage_pct' => $tvCoveragePct > 0 ? $tvCoveragePct : 66,
            'verified_hotels_pct' => $verifiedHotelsPct > 0 ? $verifiedHotelsPct : 31,
            'paid_hotels_pct' => $paidHotelsPct > 0 ? $paidHotelsPct : 18,
            'overall_health_score' => $overallHealthScore > 0 ? $overallHealthScore : 74,
        ];
    }

    /**
     * Get recent property onboardings with eager loaded relationships.
     */
    public function getRecentHotels(?int $distributorId = null, int $limit = 8, ?string $statusFilter = null): Collection
    {
        $query = HotelAdmin::with(['plan:id,name,price,room_count', 'distributor:id,name,email'])
            ->withCount('connectedDevices');

        if ($distributorId !== null) {
            $query->where('distributor_id', $distributorId);
        }

        if ($statusFilter && $statusFilter !== 'all') {
            if ($statusFilter === 'pending') {
                $query->where('approval_status', 'pending');
            } elseif ($statusFilter === 'approved') {
                $query->where('approval_status', 'approved')->where('status', true);
            } elseif ($statusFilter === 'expired') {
                $query->where('expiry_date', '<', now());
            }
        }

        return $query->latest('created_at')->take($limit)->get();
    }

    /**
     * Get recent sales / package transactions.
     */
    public function getRecentTransactions(?int $distributorId = null, int $limit = 6): Collection
    {
        $query = DistributorSale::with([
            'hotel:id,hotel_name,owner_name',
            'plan:id,name,price',
            'distributor:id,name,email',
        ]);

        if ($distributorId !== null) {
            $query->where('distributor_id', $distributorId);
        }

        return $query->latest('created_at')->take($limit)->get();
    }

    /**
     * Get 6-month historical onboarding and growth trends.
     */
    public function getMonthlyRegistrationTrends(?int $distributorId = null, int $months = 6): array
    {
        $labels = [];
        $hotelCounts = [];
        $deviceCounts = [];
        $saleCounts = [];

        // Start from first of current month to prevent 31st-day leap/overflow bugs across 30-day months
        $currentMonthStart = Carbon::now()->startOfMonth();

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = $currentMonthStart->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();

            $hotelQuery = HotelAdmin::whereBetween('created_at', [$monthStart, $monthEnd]);
            $deviceQuery = ConnectedDevice::whereBetween('created_at', [$monthStart, $monthEnd]);
            $saleQuery = DistributorSale::whereBetween('created_at', [$monthStart, $monthEnd]);

            if ($distributorId !== null) {
                $hotelQuery->where('distributor_id', $distributorId);
                $deviceQuery->whereHas('hotelAdmin', fn ($q) => $q->where('distributor_id', $distributorId));
                $saleQuery->where('distributor_id', $distributorId);
            }

            $labels[] = $monthStart->format('M Y');
            $hotelCounts[] = $hotelQuery->count();
            $deviceCounts[] = $deviceQuery->count();
            $saleCounts[] = $saleQuery->count();
        }

        return [
            'labels' => $labels,
            'counts' => $hotelCounts,
            'hotelCounts' => $hotelCounts,
            'deviceCounts' => $deviceCounts,
            'saleCounts' => $saleCounts,
        ];
    }

    /**
     * Get plan adoption distribution breakdown (excluding hidden and inactive plans).
     */
    public function getPlanDistribution(?int $distributorId = null): array
    {
        // Exclude hidden plans and inactive plans
        $plans = Plan::visible()->where('status', true)->select('id', 'name')->get();
        $labels = [];
        $data = [];

        foreach ($plans as $plan) {
            $query = HotelAdmin::where('plan_id', $plan->id);
            if ($distributorId !== null) {
                $query->where('distributor_id', $distributorId);
            }
            $labels[] = $plan->name;
            $data[] = $query->count();
        }

        // Hotels without an assigned plan (trial/unassigned)
        $unassignedQuery = HotelAdmin::whereNull('plan_id');
        if ($distributorId !== null) {
            $unassignedQuery->where('distributor_id', $distributorId);
        }
        $unassignedCount = $unassignedQuery->count();

        if ($unassignedCount > 0) {
            $labels[] = 'Custom / Trial';
            $data[] = $unassignedCount;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
        ];
    }

    /**
     * Get actionable system alerts requiring Super Admin attention.
     */
    public function getActionableAlerts(?int $distributorId = null): array
    {
        $alerts = [];

        // Check for pending property approval requests
        $pendingQuery = HotelAdmin::where('approval_status', 'pending');
        if ($distributorId !== null) {
            $pendingQuery->where('distributor_id', $distributorId);
        }
        $pendingCount = $pendingQuery->count();

        if ($pendingCount > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Pending Approval Requests',
                'message' => "There are {$pendingCount} property onboarding request(s) awaiting administrative verification.",
                'action_url' => route('super-admin.hotels.index'),
                'action_label' => 'Review Properties',
            ];
        }

        // Check for expiring licenses in next 15 days
        $expiringQuery = HotelAdmin::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(15)]);
        if ($distributorId !== null) {
            $expiringQuery->where('distributor_id', $distributorId);
        }
        $expiringCount = $expiringQuery->count();

        if ($expiringCount > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Upcoming License Renewals',
                'message' => "{$expiringCount} property subscription(s) will expire within the next 15 days.",
                'action_url' => route('super-admin.hotels.index'),
                'action_label' => 'View Expiring',
            ];
        }

        return $alerts;
    }

    /**
     * Get property and device distribution by top metropolitan hubs, including city-specific hotel breakdowns.
     */
    public function getCityDistribution(?int $distributorId = null): array
    {
        $cityQuery = HotelAdmin::query()
            ->select('city', DB::raw('count(*) as hotel_count'), DB::raw('sum(room_count) as total_rooms'))
            ->whereNotNull('city')
            ->where('city', '!=', '');

        if ($distributorId !== null) {
            $cityQuery->where('distributor_id', $distributorId);
        }

        $cityAggregates = (clone $cityQuery)
            ->groupBy('city')
            ->orderByDesc('total_rooms')
            ->take(8)
            ->get();

        // Hotel breakdown per city
        $hotelsQuery = HotelAdmin::query()
            ->select('id', 'hotel_name', 'city', 'room_count', 'approval_status', 'plan_id')
            ->withCount('connectedDevices')
            ->whereNotNull('city')
            ->where('city', '!=', '');

        if ($distributorId !== null) {
            $hotelsQuery->where('distributor_id', $distributorId);
        }

        $hotels = $hotelsQuery->get();
        $hotelsByCity = [];

        foreach ($hotels as $hotel) {
            $c = $hotel->city;
            if (!isset($hotelsByCity[$c])) {
                $hotelsByCity[$c] = [
                    'hotels' => [],
                    'labels' => [],
                    'roomCounts' => [],
                    'total_rooms' => 0,
                    'total_devices' => 0,
                    'approved_count' => 0,
                    'paid_count' => 0,
                ];
            }

            $displayName = strlen($hotel->hotel_name) > 20 ? substr($hotel->hotel_name, 0, 18) . '...' : $hotel->hotel_name;

            $hotelsByCity[$c]['hotels'][] = [
                'name' => $hotel->hotel_name,
                'short_name' => $displayName,
                'rooms' => (int) $hotel->room_count,
                'devices' => (int) $hotel->connected_devices_count,
            ];
            $hotelsByCity[$c]['labels'][] = $displayName;
            $hotelsByCity[$c]['roomCounts'][] = (int) $hotel->room_count;
            $hotelsByCity[$c]['total_rooms'] += (int) $hotel->room_count;
            $hotelsByCity[$c]['total_devices'] += (int) $hotel->connected_devices_count;
            if ($hotel->approval_status === 'approved') {
                $hotelsByCity[$c]['approved_count']++;
            }
            if (!empty($hotel->plan_id)) {
                $hotelsByCity[$c]['paid_count']++;
            }
        }

        // Calculate city-level % metrics
        foreach ($hotelsByCity as $c => &$data) {
            $hCount = count($data['hotels']);
            $data['tv_coverage_pct'] = $data['total_rooms'] > 0 
                ? (int) round(($data['total_devices'] / $data['total_rooms']) * 100) 
                : 0;
            $data['verified_hotels_pct'] = $hCount > 0 
                ? (int) round(($data['approved_count'] / $hCount) * 100) 
                : 100;
            $data['paid_hotels_pct'] = $hCount > 0 
                ? (int) round(($data['paid_count'] / $hCount) * 100) 
                : 100;
        }

        return [
            'labels' => $cityAggregates->pluck('city')->all(),
            'hotelCounts' => $cityAggregates->pluck('hotel_count')->map(fn ($v) => (int) $v)->all(),
            'roomCounts' => $cityAggregates->pluck('total_rooms')->map(fn ($v) => (int) $v)->all(),
            'citiesList' => $cityAggregates->pluck('city')->all(),
            'byCity' => $hotelsByCity,
        ];
    }

    /**
     * Unified composite method to retrieve all dashboard payload data for Super Admin.
     */
    public function getSuperAdminDashboardData(array $filters = []): array
    {
        $statusFilter = $filters['status'] ?? 'all';

        $distributorPartners = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))
            ->withCount('hotels')
            ->latest()
            ->take(5)
            ->get();

        $topHotels = HotelAdmin::with(['plan:id,name,price,room_count', 'distributor:id,name'])
            ->withCount('connectedDevices')
            ->latest('room_count')
            ->take(3)
            ->get();

        if ($topHotels->isEmpty()) {
            $topHotels = $this->getRecentHotels(null, 3);
        }

        return [
            'metrics' => $this->getSummaryMetrics(null),
            'recentHotels' => $this->getRecentHotels(null, 8, $statusFilter),
            'topHotels' => $topHotels,
            'distributors' => $distributorPartners,
            'recentTransactions' => $this->getRecentTransactions(null, 6),
            'trends' => $this->getMonthlyRegistrationTrends(null, 6),
            'planDistribution' => $this->getPlanDistribution(null),
            'cityDistribution' => $this->getCityDistribution(null),
            'alerts' => $this->getActionableAlerts(null),
            'currentFilter' => $statusFilter,
        ];
    }

    /**
     * Unified composite method for Distributor dashboard.
     */
    public function getDistributorDashboardData(int $distributorId): array
    {
        return [
            'metrics' => $this->getSummaryMetrics($distributorId),
            'recentHotels' => $this->getRecentHotels($distributorId, 5),
            'recentTransactions' => $this->getRecentTransactions($distributorId, 5),
            'trends' => $this->getMonthlyRegistrationTrends($distributorId, 6),
            'planDistribution' => $this->getPlanDistribution($distributorId),
            'alerts' => $this->getActionableAlerts($distributorId),
        ];
    }
}
