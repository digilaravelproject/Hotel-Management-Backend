@extends('layouts.hotel_admin')

@section('title', 'My Package - Hotel Admin')
@section('page_title', 'My Subscription Package')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    
    <!-- Package Details Header Card -->
    <div class="card" style="margin-bottom: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <span style="display: inline-block; background-color: var(--primary-light); color: var(--primary); font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; margin-bottom: 8px;">
                    Active Plan
                </span>
                <h2 style="margin: 0 0 6px 0; font-size: 24px; font-weight: 800; color: var(--bg-dark);">
                    {{ $plan ? $plan->name : 'No Active Plan Assigned' }}
                </h2>
                <p style="margin: 0; color: var(--text-muted); font-size: 14px;">
                    {{ $plan->description ?? 'Standard system licensing plan assigned by Super Admin.' }}
                </p>
            </div>
            
            <div style="text-align: right;">
                <div style="font-size: 28px; font-weight: 800; color: var(--primary);">
                    ₹{{ $plan ? number_format($plan->price, 0) : '0' }}<span style="font-size: 14px; color: var(--text-muted); font-weight: 400;">/month</span>
                </div>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                    Max TVs / Rooms: <strong style="color: var(--bg-dark);">{{ $hotel->allowed_device_limit }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Available OTT Platforms Card -->
    <div class="card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--bg-dark);">
                    <i class="fa-solid fa-tv" style="color: var(--primary); margin-right: 8px;"></i>Included OTT Platforms
                </h3>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
                    These OTT platforms are enabled under your subscription plan.
                </p>
            </div>
            <a href="{{ route('hotel.ott-settings') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-sliders" style="margin-right: 6px;"></i>Configure Global Access
            </a>
        </div>

        @if(count($assignedPlatforms) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
                @foreach($assignedPlatforms as $ott)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="width: 36px; height: 36px; background: #e0f2fe; color: #0369a1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div style="display: flex; flex-direction: column; overflow: hidden;">
                            <span style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $ott['name'] }}</span>
                            <span style="font-size: 11px; color: #64748b; font-family: monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                <i class="fa-solid fa-tv" style="font-size: 36px; color: var(--text-light); margin-bottom: 12px; display: block;"></i>
                No OTT platforms enabled in your plan yet. Please contact Super Admin to activate OTT features.
            </div>
        @endif
    </div>

</div>
@endsection
