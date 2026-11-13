@extends('layouts.super_admin')

@section('title', 'Tax Management - Super Admin')
@section('page_title', 'Tax Management')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header & Action CTA -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.plans.index') }}" class="hover:text-[#150A42] transition-colors">
                    <span>Plans & Subscriptions</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42]">Taxes</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Tax & GST Management</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configure tax percentages, CGST/SGST splits, and invoice tax compliance rules.</p>
        </div>

        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <button type="button" onclick="openTaxModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Create New Tax Rate</span>
            </button>
        </div>
    </div>


    <!-- Taxes Table Card -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900">Configured Tax Rates ({{ $taxes->count() }})</h3>
            </div>
            <span class="text-[11px] font-mono text-slate-400">Linked to Plans & Invoices</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-6">Tax Name</th>
                        <th class="py-3.5 px-6">Total Rate %</th>
                        <th class="py-3.5 px-6">Invoice Breakdown</th>
                        <th class="py-3.5 px-6">Linked Plans</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($taxes as $tax)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2.5">
                                    <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                                        {{ $tax->name }}
                                    </div>
                                    @if($tax->is_default)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-mono font-extrabold">DEFAULT</span>
                                    @endif
                                </div>
                                @if($tax->description)
                                    <p class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $tax->description }}</p>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-mono text-sm font-black text-[#150A42]">
                                {{ number_format($tax->rate, 2) }}%
                            </td>
                            <td class="py-4 px-6">
                                @if($tax->is_split)
                                    <div class="space-y-0.5 font-mono text-[11px]">
                                        <div class="text-slate-700 font-bold">{{ $tax->split_name_1 ?? 'CGST' }}: {{ number_format($tax->split_rate_1, 2) }}%</div>
                                        <div class="text-slate-700 font-bold">{{ $tax->split_name_2 ?? 'SGST' }}: {{ number_format($tax->split_rate_2, 2) }}%</div>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-mono text-[11px]">Single Flat {{ number_format($tax->rate, 2) }}%</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 font-mono font-bold text-[11px]">
                                    {{ $tax->plans_count ?? 0 }} Plans
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleTaxStatus({{ $tax->id }})" {{ $tax->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="editTax({{ json_encode($tax) }})" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-[#150A42] hover:bg-purple-50 flex items-center justify-center transition-all cursor-pointer" title="Edit Tax">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    @if(($tax->plans_count ?? 0) == 0)
                                        <form action="{{ route('super-admin.taxes.destroy', $tax->id) }}" method="POST" onsubmit="return confirm('Delete this tax rate permanently?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all cursor-pointer" title="Delete Tax">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300"></i>
                                <p class="font-bold">No tax rates defined yet.</p>
                                <p class="text-xs mt-1">Create GST 18% or flat VAT to attach to subscription plans.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Tax Create / Edit Modal -->
<div id="taxModal" class="fixed inset-0 z-50 bg-[#0A051B]/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] border border-slate-200 shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 animate-scaleIn">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-base font-extrabold text-slate-900">Create Tax Rate</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Set rate percentage and invoice presentation</p>
                </div>
            </div>
            <button type="button" onclick="closeTaxModal()" class="w-8 h-8 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="taxForm" action="{{ route('super-admin.taxes.store') }}" method="POST" class="space-y-4">
            @csrf
            <div id="methodContainer"></div>

            <!-- Tax Name -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tax Name / Label <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="tax_name" placeholder="e.g. GST 18% or Standard VAT" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-400/20 focus:border-[#150A42] font-semibold">
            </div>

            <!-- Total Rate % -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Total Rate Percentage (%) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" step="0.01" min="0" max="100" name="rate" id="tax_rate" placeholder="18.00" required oninput="autoComputeSplits()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-purple-400/20 focus:border-[#150A42]">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono font-extrabold text-slate-400 text-xs">%</span>
                </div>
            </div>

            <!-- Split Option Checkbox -->
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-3">
                <label class="flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox" name="is_split" id="is_split" value="1" onchange="toggleSplitFields()" class="w-4 h-4 rounded text-[#150A42] focus:ring-purple-400 border-slate-300">
                    <span class="text-xs font-bold text-slate-800">Split into CGST & SGST on Invoices (Indian GST Standard)</span>
                </label>

                <!-- Sub-split inputs -->
                <div id="splitFields" class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200/60 hidden">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Split 1 Name & %</label>
                        <div class="flex items-center space-x-1.5">
                            <input type="text" name="split_name_1" id="split_name_1" value="CGST" placeholder="CGST" class="w-1/2 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                            <input type="number" step="0.01" name="split_rate_1" id="split_rate_1" placeholder="9.00" class="w-1/2 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-mono font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Split 2 Name & %</label>
                        <div class="flex items-center space-x-1.5">
                            <input type="text" name="split_name_2" id="split_name_2" value="SGST" placeholder="SGST" class="w-1/2 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                            <input type="number" step="0.01" name="split_rate_2" id="split_rate_2" placeholder="9.00" class="w-1/2 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-mono font-bold">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                <textarea name="description" id="tax_description" rows="2" placeholder="Applicable on all in-room hospitality IPTV services" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <!-- Default Checkbox -->
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="is_default" id="is_default" value="1" class="w-4 h-4 rounded text-[#150A42] focus:ring-purple-400 border-slate-300">
                <span class="text-xs font-bold text-slate-700">Set as Default Tax for new Plans</span>
            </label>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeTaxModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white text-xs font-extrabold shadow-md cursor-pointer">
                    Save Tax Rate
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTaxModal() {
        document.getElementById('taxModal').classList.remove('hidden');
        document.getElementById('taxForm').action = "{{ route('super-admin.taxes.store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('modalTitle').textContent = 'Create Tax Rate';
        document.getElementById('tax_name').value = '';
        document.getElementById('tax_rate').value = '';
        document.getElementById('is_split').checked = false;
        document.getElementById('split_name_1').value = 'CGST';
        document.getElementById('split_rate_1').value = '';
        document.getElementById('split_name_2').value = 'SGST';
        document.getElementById('split_rate_2').value = '';
        document.getElementById('tax_description').value = '';
        document.getElementById('is_default').checked = false;
        toggleSplitFields();
    }

    function editTax(tax) {
        document.getElementById('taxModal').classList.remove('hidden');
        document.getElementById('taxForm').action = `/super-admin/taxes/${tax.id}`;
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').textContent = 'Edit Tax Rate: ' + tax.name;
        document.getElementById('tax_name').value = tax.name || '';
        document.getElementById('tax_rate').value = tax.rate || '';
        document.getElementById('is_split').checked = !!tax.is_split;
        document.getElementById('split_name_1').value = tax.split_name_1 || 'CGST';
        document.getElementById('split_rate_1').value = tax.split_rate_1 || '';
        document.getElementById('split_name_2').value = tax.split_name_2 || 'SGST';
        document.getElementById('split_rate_2').value = tax.split_rate_2 || '';
        document.getElementById('tax_description').value = tax.description || '';
        document.getElementById('is_default').checked = !!tax.is_default;
        toggleSplitFields();
    }

    function closeTaxModal() {
        document.getElementById('taxModal').classList.add('hidden');
    }

    function toggleSplitFields() {
        const isChecked = document.getElementById('is_split').checked;
        const fields = document.getElementById('splitFields');
        if (isChecked) {
            fields.classList.remove('hidden');
            autoComputeSplits();
        } else {
            fields.classList.add('hidden');
        }
    }

    function autoComputeSplits() {
        const rate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const isChecked = document.getElementById('is_split').checked;
        if (isChecked && rate > 0) {
            const half = (rate / 2).toFixed(2);
            document.getElementById('split_rate_1').value = half;
            document.getElementById('split_rate_2').value = half;
        }
    }

    function toggleTaxStatus(id) {
        fetch(`/super-admin/taxes/${id}/toggle-status`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }
</script>
@endsection

