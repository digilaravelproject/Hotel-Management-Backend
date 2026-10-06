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

        // 3. Revenue calculations
        // A) Sales recorded through distributor package sales ledger
        $recordedSalesRevenue = (clone $salesQuery)->sum('amount');

        // B) Estimated monthly recurring revenue from directly paid plans
        $activePlanRevenue = (clone $hotelQuery)
            ->where('payment_status', 'paid')
            ->join('plans', 'hotel_admins.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        $totalEstimatedRevenue = max($recordedSalesRevenue, $activePlanRevenue);

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
        $counts = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $query = HotelAdmin::whereBetween('created_at', [$monthStart, $monthEnd]);
            if ($distributorId !== null) {
                $query->where('distributor_id', $distributorId);
            }

            $labels[] = $date->format('M Y');
            $counts[] = $query->count();
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }

    /**
     * Get plan adoption distribution breakdown.
     */
    public function getPlanDistribution(?int $distributorId = null): array
    {
        $plans = Plan::select('id', 'name')->get();
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
     * Unified composite method to retrieve all dashboard payload data for Super Admin.
     */
    public function getSuperAdminDashboardData(array $filters = []): array
    {
        $statusFilter = $filters['status'] ?? 'all';

        return [
            'metrics' => $this->getSummaryMetrics(null),
            'recentHotels' => $this->getRecentHotels(null, 8, $statusFilter),
            'recentTransactions' => $this->getRecentTransactions(null, 6),
            'trends' => $this->getMonthlyRegistrationTrends(null, 6),
            'planDistribution' => $this->getPlanDistribution(null),
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
