@extends('layouts.hotel_admin')

@section('title', 'Room Menu Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' Menu Configuration')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('hotel.devices.index') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to Connected TVs
        </a>

        @if($hasOverride)
            <form action="{{ route('hotel.devices.menus.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default Menu settings?');">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--warning); border-color: rgba(245, 158, 11, 0.4);">
                    <i class="fa-solid fa-rotate-left" style="margin-right: 6px;"></i>Reset to Hotel Global Default
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--bg-dark);">
                    Room {{ $device->room_no }} Specific Menu Items
                </h3>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
                    Device ID: <span style="font-family: monospace; font-weight: 600;">{{ $device->device_id }}</span> | MAC: <span style="font-family: monospace;">{{ $device->mac_address }}</span>
                </p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span style="font-size: 12px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-sliders"></i> Custom Room Override Active
                    </span>
                @else
                    <span style="font-size: 12px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-globe"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ url('/hotel/devices/' . $device->id . '/menus') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                @foreach($defaultMenus as $menu)
                    @php
                        $isShown = !isset($currentSettings[$menu['id']]) || $currentSettings[$menu['id']] !== 'hide';
                    @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $menu['name'] }}</span>
                            <span style="font-size: 11px; color: #64748b; font-family: monospace;">id: {{ $menu['id'] }}</span>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="menus[{{ $menu['id'] }}]" value="1" {{ $isShown ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('hotel.devices.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i>Save Room Menu Configuration
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
