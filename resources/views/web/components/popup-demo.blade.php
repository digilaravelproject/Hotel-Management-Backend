<div id="popup-demo" class="paxtv-modal-overlay" aria-hidden="true" 
     style="display:none; position: fixed !important; inset: 0 !important; width: 100% !important; height: 100% !important; z-index: 1000050 !important; background-color: rgba(15, 23, 42, 0.85) !important; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); overflow: hidden !important; align-items: center; justify-content: center; padding: 12px sm:padding: 16px;">
    
    <div class="paxtv-modal-dialog relative w-full rounded-[16px] my-auto" 
         style="max-width: 540px !important; width: 100% !important; max-height: calc(100dvh - 24px) !important; overflow-y: auto !important; overflow-x: hidden !important; -webkit-overflow-scrolling: touch; background-image: linear-gradient(135deg, #0E2556 0%, #146A82 100%) !important; border-top: 3px solid #1A9C9E !important; border-left: 0 !important; border-right: 0 !important; border-bottom: 0 !important; border-radius: 16px !important; box-shadow: 0px 24px 60px 0px rgba(0, 0, 0, 0.45) !important;">
        
        <div class="p-4 sm:p-7 md:p-[32px_36px]">
            <!-- Close Button -->
            <button type="button" class="paxtv-modal-close hover:text-white transition-colors duration-200 focus:outline-none cursor-pointer z-10" 
                    style="position: absolute; top: 12px; right: 14px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.08) !important; border: 1px solid rgba(255,255,255,0.15) !important; border-radius: 9999px !important; color: #5EC9CB; padding: 0; box-shadow: none;" 
                    aria-label="Close modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Header (Compact & Clean) -->
            <div class="mb-3 sm:mb-4 text-left pr-8">
                <span class="block font-heading text-[10.5px] sm:text-[11px] font-semibold tracking-[2px] text-[#5EC9CB] uppercase mb-0.5">
                    GET IN TOUCH
                </span>
                <h2 class="font-heading text-[20px] sm:text-[26px] font-bold text-white leading-[1.2] mb-1">
                    Request a Demo
                </h2>
                <p class="font-heading text-[12px] sm:text-[13.5px] text-[#CBD5E1] font-normal leading-[1.4] sm:leading-[1.5]">
                    Fill in the form and our team will get back to you shortly.
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('contact.submit') }}" id="demo-request-form">
                @csrf
                
                <div class="space-y-2 sm:space-y-3">
                    <!-- Row 1: Full Name & Phone Number -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        <div>
                            <label for="form_field_name" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1">
                                Full Name <span class="text-[#EF4444]">*</span>
                            </label>
                            <input type="text" name="name" id="form_field_name" required
                                   placeholder="Enter your full name"
                                   class="w-full px-3 py-2 sm:py-2.5 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition"
                                   style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);">
                        </div>

                        <div>
                            <label for="form_field_phone" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1">
                                Phone Number <span class="text-[#EF4444]">*</span>
                            </label>
                            <input type="tel" name="phone" id="form_field_phone" required
                                   placeholder="+91 00000 00000"
                                   pattern="[0-9()#&+*\-=. ]+"
                                   title="Only numbers and phone characters (#, -, *, etc) are accepted."
                                   class="w-full px-3 py-2 sm:py-2.5 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition"
                                   style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);">
                        </div>
                    </div>

                    <!-- Row 2: Email Address -->
                    <div>
                        <label for="form_field_email" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1">
                            Email Address <span class="text-[#EF4444]">*</span>
                        </label>
                        <input type="email" name="email" id="form_field_email" required
                               placeholder="you@company.com"
                               class="w-full px-3 py-2 sm:py-2.5 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition"
                               style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);">
                    </div>

                    <!-- Row 3: Hotel / Property Name & Number of Rooms (2-column on mobile too!) -->
                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <div>
                            <label for="form_field_property" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1 truncate">
                                Hotel / Property
                            </label>
                            <input type="text" name="property" id="form_field_property"
                                   placeholder="Hotel name"
                                   class="w-full px-3 py-2 sm:py-2.5 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition"
                                   style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);">
                        </div>

                        <div>
                            <label for="form_field_rooms" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1 truncate">
                                No. of Rooms
                            </label>
                            <input type="number" name="rooms" id="form_field_rooms"
                                   placeholder="e.g. 120" min="1"
                                   class="w-full px-3 py-2 sm:py-2.5 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition"
                                   style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);">
                        </div>
                    </div>

                    <!-- Row 4: Message (Compact 2 rows) -->
                    <div>
                        <label for="form_field_message" class="block font-heading text-[11.5px] sm:text-[13px] font-medium text-[#CBD5E1] mb-1">
                            Message
                        </label>
                        <textarea name="message" id="form_field_message" rows="2"
                                  placeholder="Tell us about your requirements"
                                  class="w-full px-3 py-1.5 sm:py-2 rounded-[8px] sm:rounded-[10px] text-[13px] sm:text-[14px] text-white font-heading placeholder-slate-400 focus:outline-none focus:border-[#5EC9CB] focus:ring-1 focus:ring-[#5EC9CB] transition resize-none"
                                  style="background-color: rgba(255, 255, 255, 0.06); border: 1px solid rgba(94, 201, 203, 0.35);"></textarea>
                    </div>

                    <!-- Submit Button (Prominent & Always In View) -->
                    <div class="pt-1">
                        <button type="submit"
                                class="w-full py-2.5 sm:py-3.5 px-6 rounded-[8px] sm:rounded-[10px] font-heading text-[14px] sm:text-[15px] font-semibold text-white bg-[#0E2556] hover:bg-[#5EC9CB] hover:text-[#0E2556] transition-all duration-300 shadow-md cursor-pointer flex items-center justify-center">
                            <span>Request Demo</span>
                        </button>
                    </div>

                    <!-- Success / Error Status Message Container -->
                    <div id="demo-form-status" class="hidden font-heading text-[13px] sm:text-[14px] text-center pt-1"></div>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('demo-request-form');
    const statusDiv = document.getElementById('demo-form-status');
    if (!form || !statusDiv) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Submitting...</span>';
        statusDiv.classList.add('hidden');

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            if (response.ok && data.success) {
                statusDiv.className = 'font-heading text-[14px] text-center pt-2 text-[#5EC9CB]';
                statusDiv.textContent = data.message || 'Thank you! Your request has been sent.';
                statusDiv.classList.remove('hidden');
                form.reset();
                setTimeout(() => {
                    const modal = document.getElementById('popup-demo');
                    if (modal && typeof closeModal === 'function') {
                        closeModal(modal);
                    } else if (modal) {
                        modal.classList.remove('active');
                        modal.style.display = 'none';
                    }
                }, 2500);
            } else {
                statusDiv.className = 'font-heading text-[14px] text-center pt-2 text-[#FCA5A5]';
                statusDiv.textContent = data.message || 'Something went wrong. Please check your fields and try again.';
                statusDiv.classList.remove('hidden');
            }
        } catch (err) {
            statusDiv.className = 'font-heading text-[14px] text-center pt-2 text-[#FCA5A5]';
            statusDiv.textContent = 'Network error. Please try again.';
            statusDiv.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});
</script>