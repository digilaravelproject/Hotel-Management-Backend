@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => '',
    'id' => null,
    'required' => false,
    'theme' => 'sa', // 'sa' (Super Admin), 'dist' (Distributor), 'hotel' (Hotel), 'default'
    'selectClass' => '',
    'wrapClass' => '',
])

@php
    $selectId = $id ?? ($name ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) : 'select_' . uniqid());
    $selectedVal = old($name, $selected);

    $initialLabel = $label ?: 'Select an option';
    if (!empty($options) && isset($options[$selectedVal])) {
        $initialLabel = $options[$selectedVal];
    } elseif ($selectedVal !== '' && $selectedVal !== null) {
        $initialLabel = ucfirst((string)$selectedVal);
    }

    $selectedValLower = strtolower((string)$selectedVal);
    $statusPillClasses = match($selectedValLower) {
        'approved', 'active', 'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200/90 hover:bg-emerald-100/70',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/90 hover:bg-amber-100/70',
        'disapproved', 'inactive', 'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200/90 hover:bg-rose-100/70',
        default => 'bg-white text-slate-800 border-slate-200 hover:bg-slate-50/90',
    };

    $statusDotColor = match($selectedValLower) {
        'approved', 'active', 'paid' => 'bg-emerald-500',
        'pending' => 'bg-amber-500',
        'disapproved', 'inactive', 'unpaid' => 'bg-rose-500',
        default => null,
    };
@endphp

<div class="global-custom-dropdown relative inline-block text-left {{ $wrapClass }}" data-dropdown-id="{{ $selectId }}">
    <!-- Hidden Native Select (For Form Submissions & Event Listeners) -->
    <select 
        @if($name) name="{{ $name }}" @endif
        id="{{ $selectId }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'sr-only global-dropdown-native-select']) }}>
        
        @php
            $slotHtml = (string) $slot;
            $hasEmptyInSlot = !empty($options) ? false : (bool) (
                preg_match('/<option\s+value=["\']{2}/i', $slotHtml) || 
                ($label && stripos($slotHtml, trim($label)) !== false)
            );
        @endphp

        @if($label && !$hasEmptyInSlot)
            <option value="" {{ empty($selectedVal) && $selectedVal !== '0' && $selectedVal !== 0 ? 'selected' : '' }}>{{ $label }}</option>
        @endif

        @if(!empty($options))
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ (string)$val === (string)$selectedVal ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>

    <!-- Custom Dropdown Trigger Button -->
    <button type="button" 
        id="btn_{{ $selectId }}"
        onclick="toggleGlobalDropdown('{{ $selectId }}', event)"
        class="global-dropdown-btn w-full flex items-center justify-between gap-1.5 border rounded-xl shadow-xs transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 cursor-pointer {{ $statusPillClasses }} {{ $selectClass ?: 'px-3.5 py-2 text-xs font-semibold' }}"
        aria-haspopup="listbox"
        aria-expanded="false"
        title="{{ $initialLabel }}">
        <span class="flex items-center space-x-1.5 min-w-0 truncate">
            <span id="dot_{{ $selectId }}" class="w-1.5 h-1.5 rounded-full shrink-0 {{ $statusDotColor ?? 'hidden' }} {{ empty($statusDotColor) ? 'hidden' : '' }}"></span>
            <span id="label_{{ $selectId }}" class="truncate font-bold">{{ $initialLabel }}</span>
        </span>
        <i id="chevron_{{ $selectId }}" class="fa-solid fa-chevron-down text-[9px] opacity-60 shrink-0 transition-transform duration-200"></i>
    </button>

    <!-- Floating Custom Dropdown Menu -->
    <div id="menu_{{ $selectId }}"
         class="global-custom-dropdown-menu hidden absolute left-0 top-full mt-1.5 min-w-[130px] w-full max-h-60 overflow-y-auto bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 backdrop-blur-sm scrollbar-thin">
        @if(!empty($options))
            <div class="py-0.5 space-y-0.5">
                @if($label)
                    <button type="button" 
                        onclick="selectGlobalDropdownOption('{{ $selectId }}', '', '{{ addslashes($label) }}', event)"
                        class="global-dropdown-option w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors cursor-pointer text-left {{ empty($selectedVal) && $selectedVal !== '0' && $selectedVal !== 0 ? 'bg-purple-50 text-[#150A42] font-bold' : '' }}"
                        data-value="">
                        <span class="truncate">{{ $label }}</span>
                        @if(empty($selectedVal) && $selectedVal !== '0' && $selectedVal !== 0)
                            <i class="fa-solid fa-check text-purple-600 text-[10px] ml-auto"></i>
                        @endif
                    </button>
                @endif
                @foreach($options as $val => $text)
                    @php
                        $isSelected = (string)$val === (string)$selectedVal;
                        $optValLower = strtolower((string)$val);
                        $optDotClass = match($optValLower) {
                            'approved', 'active', 'paid' => 'bg-emerald-500',
                            'pending' => 'bg-amber-500',
                            'disapproved', 'inactive', 'unpaid' => 'bg-rose-500',
                            default => null,
                        };
                    @endphp
                    <button type="button" 
                        onclick="selectGlobalDropdownOption('{{ $selectId }}', '{{ addslashes($val) }}', '{{ addslashes($text) }}', event)"
                        class="global-dropdown-option w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#150A42] transition-colors cursor-pointer text-left {{ $isSelected ? 'bg-purple-50 text-[#150A42] font-bold' : '' }}"
                        data-value="{{ $val }}">
                        <span class="flex items-center space-x-2 truncate">
                            @if($optDotClass)
                                <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $optDotClass }}"></span>
                            @endif
                            <span class="truncate">{{ $text }}</span>
                        </span>
                        @if($isSelected)
                            <i class="fa-solid fa-check text-purple-600 text-[10px] ml-auto"></i>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>

@once
<script>
    function toggleGlobalDropdown(selectId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const menu = document.getElementById('menu_' + selectId);
        const btn = document.getElementById('btn_' + selectId);
        const chevron = document.getElementById('chevron_' + selectId);
        if (!menu || !btn) return;

        const isClosed = menu.classList.contains('hidden');

        // Close any other open dropdowns
        document.querySelectorAll('.global-custom-dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.global-dropdown-btn').forEach(b => b.setAttribute('aria-expanded', 'false'));
        document.querySelectorAll('.global-dropdown-btn i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
        document.querySelectorAll('tr').forEach(tr => tr.classList.remove('z-40', 'relative'));

        if (isClosed) {
            // Collision detection with viewport bottom
            const rect = btn.getBoundingClientRect();
            if (window.innerHeight - rect.bottom < 230) {
                menu.classList.remove('top-full', 'mt-1.5');
                menu.classList.add('bottom-full', 'mb-1.5');
            } else {
                menu.classList.remove('bottom-full', 'mb-1.5');
                menu.classList.add('top-full', 'mt-1.5');
            }

            menu.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.classList.add('rotate-180');
            btn.closest('tr')?.classList.add('z-40', 'relative');
        }
    }

    function syncDropdownStatusStyle(selectId, val) {
        const btn = document.getElementById('btn_' + selectId);
        const dot = document.getElementById('dot_' + selectId);
        if (!btn) return;

        const valLower = String(val || '').toLowerCase();

        btn.classList.remove(
            'bg-emerald-50', 'text-emerald-700', 'border-emerald-200/90', 'hover:bg-emerald-100/70',
            'bg-amber-50', 'text-amber-700', 'border-amber-200/90', 'hover:bg-amber-100/70',
            'bg-rose-50', 'text-rose-700', 'border-rose-200/90', 'hover:bg-rose-100/70',
            'bg-white', 'text-slate-800', 'border-slate-200'
        );

        if (dot) {
            dot.className = 'w-1.5 h-1.5 rounded-full shrink-0 hidden';
        }

        if (['approved', 'active', 'paid'].includes(valLower)) {
            btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200/90', 'hover:bg-emerald-100/70');
            if (dot) {
                dot.className = 'w-1.5 h-1.5 rounded-full shrink-0 bg-emerald-500';
            }
        } else if (['pending'].includes(valLower)) {
            btn.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-200/90', 'hover:bg-amber-100/70');
            if (dot) {
                dot.className = 'w-1.5 h-1.5 rounded-full shrink-0 bg-amber-500';
            }
        } else if (['disapproved', 'inactive', 'unpaid'].includes(valLower)) {
            btn.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-200/90', 'hover:bg-rose-100/70');
            if (dot) {
                dot.className = 'w-1.5 h-1.5 rounded-full shrink-0 bg-rose-500';
            }
        } else {
            btn.classList.add('bg-white', 'text-slate-800', 'border-slate-200');
            if (dot) {
                dot.classList.add('hidden');
            }
        }
    }

    function selectGlobalDropdownOption(selectId, val, text, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const select = document.getElementById(selectId);
        if (!select) return;

        select.value = val;

        const label = document.getElementById('label_' + selectId);
        if (label) label.textContent = text;

        syncDropdownStatusStyle(selectId, val);

        const menu = document.getElementById('menu_' + selectId);
        if (menu) {
            menu.querySelectorAll('.global-dropdown-option').forEach(optBtn => {
                const isMatch = optBtn.dataset.value === String(val);
                if (isMatch) {
                    optBtn.classList.add('bg-purple-50', 'text-[#150A42]', 'font-bold');
                    if (!optBtn.querySelector('.fa-check')) {
                        const checkIcon = document.createElement('i');
                        checkIcon.className = 'fa-solid fa-check text-purple-600 text-[10px] ml-auto';
                        optBtn.appendChild(checkIcon);
                    }
                } else {
                    optBtn.classList.remove('bg-purple-50', 'text-[#150A42]', 'font-bold');
                    const check = optBtn.querySelector('.fa-check');
                    if (check) check.remove();
                }
            });
            menu.classList.add('hidden');
        }

        const chevron = document.getElementById('chevron_' + selectId);
        if (chevron) chevron.classList.remove('rotate-180');

        const btn = document.getElementById('btn_' + selectId);
        if (btn) {
            btn.setAttribute('aria-expanded', 'false');
            btn.closest('tr')?.classList.remove('z-40', 'relative');
        }

        // Fire change event
        select.dispatchEvent(new Event('change', { bubbles: true }));
        if (typeof select.onchange === 'function') {
            select.onchange();
        }
    }

    function initGlobalCustomDropdown(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        const menu = document.getElementById('menu_' + selectId);
        const label = document.getElementById('label_' + selectId);
        if (!menu) return;

        if (menu.children.length === 0) {
            const listDiv = document.createElement('div');
            listDiv.className = 'py-0.5 space-y-0.5';

            const seenKeys = new Set();
            let hasEmptySeen = false;

            Array.from(select.options).forEach(opt => {
                const optVal = (opt.value || '').trim();
                const optText = (opt.text || '').trim();

                // Deduplicate empty/default options
                if (!optVal) {
                    if (hasEmptySeen) return;
                    hasEmptySeen = true;
                }

                const dedupeKey = (optVal || '__EMPTY__') + ':::' + optText.toLowerCase();
                if (seenKeys.has(dedupeKey)) return;
                seenKeys.add(dedupeKey);

                const isSelected = opt.selected;
                const valLower = optVal.toLowerCase();

                let dotBg = null;
                if (['approved', 'active', 'paid'].includes(valLower)) dotBg = 'bg-emerald-500';
                else if (['pending'].includes(valLower)) dotBg = 'bg-amber-500';
                else if (['disapproved', 'inactive', 'unpaid'].includes(valLower)) dotBg = 'bg-rose-500';

                const optBtn = document.createElement('button');
                optBtn.type = 'button';
                optBtn.className = `global-dropdown-option w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#150A42] transition-colors cursor-pointer text-left ${isSelected ? 'bg-purple-50 text-[#150A42] font-bold' : ''}`;
                optBtn.dataset.value = optVal;

                const wrapSpan = document.createElement('span');
                wrapSpan.className = 'flex items-center space-x-2 truncate';
                if (dotBg) {
                    const dotSpan = document.createElement('span');
                    dotSpan.className = `w-1.5 h-1.5 rounded-full shrink-0 ${dotBg}`;
                    wrapSpan.appendChild(dotSpan);
                }
                const labelSpan = document.createElement('span');
                labelSpan.className = 'truncate';
                labelSpan.textContent = optText;
                wrapSpan.appendChild(labelSpan);
                optBtn.appendChild(wrapSpan);
                if (isSelected) {
                    const checkIcon = document.createElement('i');
                    checkIcon.className = 'fa-solid fa-check text-purple-600 text-[10px] ml-auto';
                    optBtn.appendChild(checkIcon);
                }
                optBtn.onclick = function(e) {
                    selectGlobalDropdownOption(selectId, optVal, optText, e);
                };
                listDiv.appendChild(optBtn);
            });
            menu.appendChild(listDiv);
        }

        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            if (label) label.textContent = selectedOption.text;
            syncDropdownStatusStyle(selectId, selectedOption.value);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.global-custom-dropdown').forEach(dropdown => {
            const selectId = dropdown.getAttribute('data-dropdown-id');
            if (selectId) initGlobalCustomDropdown(selectId);
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.global-custom-dropdown')) {
            document.querySelectorAll('.global-custom-dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.global-dropdown-btn').forEach(b => b.setAttribute('aria-expanded', 'false'));
            document.querySelectorAll('.global-dropdown-btn i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
            document.querySelectorAll('tr').forEach(tr => tr.classList.remove('z-40', 'relative'));
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.global-custom-dropdown-menu').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('.global-dropdown-btn').forEach(b => b.setAttribute('aria-expanded', 'false'));
            document.querySelectorAll('.global-dropdown-btn i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
            document.querySelectorAll('tr').forEach(tr => tr.classList.remove('z-40', 'relative'));
        }
    });

    window.toggleGlobalDropdown = toggleGlobalDropdown;
    window.selectGlobalDropdownOption = selectGlobalDropdownOption;
    window.syncDropdownStatusStyle = syncDropdownStatusStyle;
    window.initGlobalCustomDropdown = initGlobalCustomDropdown;
</script>
@endonce
