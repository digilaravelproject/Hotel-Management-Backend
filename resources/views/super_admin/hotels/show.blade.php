@extends('layouts.super_admin')

@section('title', 'Hotel Vendor Details - Super Admin')
@section('page_title', 'Hotel Details')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('super-admin.hotels.index') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to list
        </a>
        <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-pen-to-square"></i> Edit Vendor Account
        </a>
    </div>

    <div class="card" style="box-shadow: var(--shadow-md); padding: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-color); padding-bottom: 24px; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 26px; font-weight: 800; color: var(--bg-dark); margin-bottom: 6px;">{{ $hotel->hotel_name }}</h1>
                <p style="color: var(--text-muted); font-size: 15px;">
                    <i class="fa-solid fa-location-dot" style="margin-right: 6px; color: var(--primary);"></i>{{ $hotel->hotel_location }}
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                @if($hotel->approval_status === 'approved')
                    <span class="badge badge-success" style="padding: 6px 12px; font-size: 13px;">Approved</span>
                @elseif($hotel->approval_status === 'disapproved')
                    <span class="badge badge-danger" style="padding: 6px 12px; font-size: 13px;">Disapproved</span>
                @else
                    <span class="badge badge-warning" style="padding: 6px 12px; font-size: 13px;">Pending Approval</span>
                @endif

                @if($hotel->status)
                    <span class="badge badge-success" style="padding: 6px 12px; font-size: 13px;">Active</span>
                @else
                    <span class="badge badge-danger" style="padding: 6px 12px; font-size: 13px;">Suspended</span>
                @endif
            </div>
        </div>

        <div class="grid grid-3" style="margin-bottom: 30px; grid-template-columns: 1fr 1fr; gap: 40px;">
            <div>
                <h3 style="font-size: 15px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; margin-bottom: 16px;">
                    Owner Credentials
                </h3>
                <table style="width: 100%; font-size: 14px; line-height: 2;">
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500; width: 40%;">Full Name:</td>
                        <td style="color: var(--bg-dark); font-weight: 600;">{{ $hotel->owner_name }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">Email Address:</td>
                        <td style="color: var(--bg-dark); font-weight: 600;"><code>{{ $hotel->email }}</code></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">Phone Line:</td>
                        <td style="color: var(--bg-dark); font-weight: 600;">{{ $hotel->phone }}</td>
                    </tr>
                </table>
            </div>

            <div>
                <h3 style="font-size: 15px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; margin-bottom: 16px;">
                    Licensing & Plan
                </h3>
                <table style="width: 100%; font-size: 14px; line-height: 2;">
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500; width: 40%;">Room / TV Limit:</td>
                        <td style="color: var(--bg-dark); font-weight: 600;">{{ $hotel->room_count }} connected TVs</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">Active Plan:</td>
                        <td style="color: var(--primary); font-weight: 600;">{{ $hotel->plan->name ?? 'None' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">Payment State:</td>
                        <td style="color: var(--bg-dark); font-weight: 600;">
                            @if($hotel->payment_status === 'paid')
                                <span style="color: var(--success); font-weight: 700;">Paid (Razorpay)</span>
                            @else
                                <span style="color: var(--danger); font-weight: 700;">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; margin-bottom: 30px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                Connected Device License Key
            </h4>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                @if($hotel->license_key)
                    <code style="font-size: 24px; font-weight: 800; color: var(--primary-hover); letter-spacing: 2px; font-family: monospace;">
                        {{ $hotel->license_key }}
                    </code>
                @else
                    <span style="color: var(--text-muted); font-style: italic;">No key generated. Complete payment first.</span>
                @endif
                <span class="badge badge-primary" style="padding: 6px 12px; font-size: 12px;">
                    Valid for {{ $hotel->room_count }} TV connection slots
                </span>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 24px; font-size: 12px; color: var(--text-muted); display: flex; justify-content: space-between;">
            <span>Registered on: {{ $hotel->created_at->format('d M, Y H:i') }}</span>
            <span>Last database update: {{ $hotel->updated_at->format('d M, Y H:i') }}</span>
        </div>
    </div>
</div>
@endsection
