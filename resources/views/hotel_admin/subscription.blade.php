@extends('layouts.hotel_admin')

@section('title', 'Plan & Billing')
@section('page_title', 'Subscription Plan & Billing')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="max-w-5xl mx-auto space-y-6" id="subApp">
    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    <div id="subStatus"></div>
    <div id="subPlans"></div>
    <div id="subCheckout"></div>
</div>
@endsection
