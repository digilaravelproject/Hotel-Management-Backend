@extends('layouts.hotel_admin')

@section('title', 'Manage Menus - Hotel Admin')
@section('page_title', 'Global TV Menu Visibility')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-list-check text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hotel Global Default TV Menus</h3>
                <p class="text-xs text-slate-500 font-medium">Toggle visibility of home screen features across all connected TVs in your hotel.</p>
            </div>
        </div>

        <form action="{{ url('/hotel/menus') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($defaultMenus as $menu)
                    @php
                        $isShown = !isset($currentSettings[$menu['id']]) || $currentSettings[$menu['id']] !== 'hide';
                    @endphp
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $menu['name'] }}</h4>
                            <span class="text-[10px] font-mono text-slate-400">id: {{ $menu['id'] }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="menus[{{ $menu['id'] }}]" value="1" {{ $isShown ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                    Save Global Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
