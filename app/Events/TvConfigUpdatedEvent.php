<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TvConfigUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?int $hotelId;
    public string $scope;
    public ?string $roomNo;
    public array $extraData;

    /**
     * Create a new event instance.
     *
     * @param int|null $hotelId Null if global update (e.g. template APK version)
     * @param string $scope Scope of change (e.g., 'GUEST', 'HOTEL_INFO', 'MENU', 'AMENITY', 'OTT', 'TEMPLATE', 'ALL')
     * @param string|null $roomNo Room number if targeted to specific room
     * @param array $extraData Additional payload data
     */
    public function __construct(?int $hotelId, string $scope = 'ALL', ?string $roomNo = null, array $extraData = [])
    {
        $this->hotelId = $hotelId;
        $this->scope = strtoupper($scope);
        $this->roomNo = $roomNo;
        $this->extraData = $extraData;
    }
}
