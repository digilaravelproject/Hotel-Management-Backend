@extends('layouts.hotel_admin')

@section('title', 'Manage Our City - Hotel Admin')
@section('page_title', 'Our City & Local Attractions')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-city text-amber-500"></i>
                Our City & Local Attractions
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure local tourist spots, heritage landmarks, distance, descriptions, and 16:9 widescreen visuals for guest smart TVs.</p>
        </div>
        <button onclick="openOurCityModal()" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New City Place</span>
        </button>
    </div>

    <!-- Table of City Places -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active City Attractions List ({{ count($cityPlaces) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Place / Attraction</th>
                        <th class="px-6 py-4">Highlights / Tags (Max 4)</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cityPlaces as $place)
                        <tr id="city-row-{{ $place->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-amber-600">#{{ $place->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($place->image)
                                    <img src="{{ asset($place->image) }}" alt="{{ $place->title }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-amber-50 text-amber-600 font-bold flex items-center justify-center border border-amber-100 text-xs">
                                        #{{ $place->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $place->title }}</td>
                            <td class="px-6 py-4">
                                @if(!empty($place->attractions) && is_array($place->attractions))
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($place->attractions as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-location-dot text-[9px] mr-1 text-amber-500"></i> {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No highlights</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $place->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleOurCityStatus({{ $place->id }})" {{ $place->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-city="{{ json_encode($place) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($place) }})" title="Edit City Place" class="p-2 rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.our-city.destroy', $place->id) }}" method="POST" onsubmit="return confirm('Delete this city attraction?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete City Place" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-city text-4xl block mb-3 text-slate-300"></i>
                                No city attraction items found. Click 'Add New City Place' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('hotel_admin.our_city.modal')
@endsection
