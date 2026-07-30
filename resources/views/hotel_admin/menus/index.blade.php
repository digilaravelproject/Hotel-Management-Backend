@extends('layouts.hotel_admin')

@section('title', 'Manage Menus - Hotel Admin')
@section('page_title', 'Global Menu Visibility Control')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <form action="{{ url('/hotel/menus') }}" method="POST">
            @csrf
            
            <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--bg-dark);">Hotel Default TV Menu Items</h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
                        Enable (Show) or Disable (Hide) standard menu items on TV screens across your hotel.
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                @foreach($defaultMenus as $menu)
                    @php
                        // Default to show if setting not explicitly hide
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
                <a href="{{ route('hotel.dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i>Save Global Menu Settings
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
