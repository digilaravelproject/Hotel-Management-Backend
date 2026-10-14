@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => '',
    'id' => null,
    'required' => false,
    'theme' => 'sa', // 'sa' (Super Admin #D4AF37), 'dist' (Distributor #E5A93C), 'hotel' (Hotel #C5A059), 'default'
    'selectClass' => '',
    'wrapClass' => '',
])

@php
    $selectId = $id ?? ($name ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) : 'select_' . uniqid());
    $themeClass = match($theme) {
        'dist' => 'custom-select-dist',
        'hotel' => 'custom-select-hotel',
        default => '',
    };
@endphp

<div class="custom-dropdown-wrap relative {{ $wrapClass }}">
    <select 
        @if($name) name="{{ $name }}" @endif
        id="{{ $selectId }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => "custom-select {$themeClass} {$selectClass}"]) }}>
        
        @if($label)
            <option value="" {{ empty($selected) && $selected !== '0' && $selected !== 0 ? 'selected' : '' }}>{{ $label }}</option>
        @endif

        @if(!empty($options))
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ (string)$val === (string)$selected ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
    <div class="custom-select-icon">
        <i class="fa-solid fa-chevron-down text-[10px]"></i>
    </div>
</div>
