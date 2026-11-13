<div id="popup-demo" class="paxtv-modal-overlay" aria-hidden="true" 
     style="display:none; position: fixed !important; inset: 0 !important; width: 100% !important; height: 100% !important; z-index: 1000050 !important; background-color: rgba(6, 3, 18, 0.88) !important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); overflow: hidden !important; align-items: center; justify-content: center; padding: 12px sm:padding: 16px;">
    
    <div class="paxtv-modal-dialog relative w-full my-auto" 
         style="max-width: 520px !important; width: 100% !important; max-height: calc(100dvh - 24px) !important; overflow-y: auto !important; overflow-x: hidden !important; -webkit-overflow-scrolling: touch; background: linear-gradient(145deg, #0d0628 0%, #150A42 55%, #0e0725 100%) !important; border: 1px solid rgba(168, 85, 247, 0.22) !important; border-top: 2px solid #A855F7 !important; border-radius: 24px !important; box-shadow: 0 30px 90px rgba(0, 0, 0, 0.85), 0 0 50px rgba(124, 58, 237, 0.12) !important;">
        
        <div class="p-5 sm:p-8">
            <!-- Close Button -->
            <button type="button" class="paxtv-modal-close transition-all duration-200 focus:outline-none cursor-pointer z-10" 
                    style="position: absolute; top: 16px; right: 16px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.12) !important; border-radius: 9999px !important; color: #CBD5E1; padding: 0; box-shadow: none;" 
                    aria-label="Close modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Header (Clean, Crisp Typography, No Blurry Glow) -->
            <div class="mb-4 sm:mb-5 text-left pr-8">
                <span class="inline-block text-[10.5px] sm:text-[11px] font-bold tracking-[2px] text-[#A855F7] uppercase mb-1 font-sans">
                    GET IN TOUCH
                </span>
                <h2 class="text-[22px] sm:text-[28px] font-extrabold text-white leading-[1.2] mb-1 font-sans tracking-tight">
                    Request a Demo
                </h2>
                <p class="text-[12.5px] sm:text-[14px] text-slate-300 font-normal leading-relaxed">
                    Fill in the form and our team will get back to you shortly.
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('contact.submit') }}" id="demo-request-form">
                @csrf
                
                <!-- Anti-spam Honeypot Field -->
                <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                    <label for="form_field_website_url">Leave this field blank</label>
                    <input type="text" name="website_url" id="form_field_website_url" tabindex="-1" autocomplete="off" value="">
                </div>

                <div class="space-y-3.5 sm:space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="form_field_name" class="block text-[12px] sm:text-[13px] font-semibold text-slate-300 mb-1.5 font-sans">
                            Full Name <span class="text-[#FF5722]">*</span>
                        </label>
                        <input type="text" name="name" id="form_field_name" required
                               placeholder="Enter your full name"
                               class="w-full px-4 py-3 rounded-xl text-[13.5px] sm:text-[14px] text-white font-medium placeholder-slate-500 focus:outline-none focus:border-[#A855F7] focus:ring-2 focus:ring-purple-500/25 focus:bg-white/[0.08] transition duration-200"
                               style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12);">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="form_field_email" class="block text-[12px] sm:text-[13px] font-semibold text-slate-300 mb-1.5 font-sans">
                            Email Address <span class="text-[#FF5722]">*</span>
                        </label>
                        <input type="email" name="email" id="form_field_email" required
                               placeholder="you@company.com"
                               class="w-full px-4 py-3 rounded-xl text-[13.5px] sm:text-[14px] text-white font-medium placeholder-slate-500 focus:outline-none focus:border-[#A855F7] focus:ring-2 focus:ring-purple-500/25 focus:bg-white/[0.08] transition duration-200"
                               style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12);">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label for="form_field_phone" class="block text-[12px] sm:text-[13px] font-semibold text-slate-300 mb-1.5 font-sans">
                            Mobile Number <span class="text-[#FF5722]">*</span>
                        </label>
                        <input type="tel" name="phone" id="form_field_phone" required
                               placeholder="+91 00000 00000"
                               pattern="[0-9()#&+*\-=. ]+"
                               title="Only numbers and phone characters (#, -, *, etc) are accepted."
                               class="w-full px-4 py-3 rounded-xl text-[13.5px] sm:text-[14px] text-white font-medium placeholder-slate-500 focus:outline-none focus:border-[#A855F7] focus:ring-2 focus:ring-purple-500/25 focus:bg-white/[0.08] transition duration-200"
                               style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12);">
                    </div>

                    @php
                        $turnstile = app(\App\Services\TurnstileService::class);
                    @endphp
                    @if($turnstile->isEnabled())
                        <!-- Cloudflare Turnstile Auto-Verify Widget (Clean, Seamless, No White Outline) -->
                        <div class="pt-1 pb-0.5 flex justify-center items-center overflow-hidden">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstile->getSiteKey() }}" data-theme="dark" data-size="normal"></div>
                        </div>
                    @endif

                    <!-- Submit Button (Matches Login Page Signature Gradient & Typography) -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-6 rounded-xl font-sans text-[14px] sm:text-[15px] font-extrabold text-white bg-gradient-to-r from-[#FF5722] to-[#FF3D00] hover:from-[#f4511e] hover:to-[#e63900] shadow-lg shadow-[#FF5722]/30 hover:shadow-xl hover:shadow-[#FF5722]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 cursor-pointer flex items-center justify-center space-x-2">
                            <span>Request Demo</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    <!-- Status Container -->
                    <div id="demo-form-status" class="hidden font-sans text-[13px] sm:text-[14px] text-center pt-1 font-semibold"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Prevent any external stylesheets from forcing ugly white border on Turnstile */
    #popup-demo .cf-turnstile,
    #popup-demo .cf-turnstile iframe {
        border: 0 !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    #popup-demo .paxtv-modal-close:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #FFFFFF !important;
        border-color: rgba(168, 85, 247, 0.4) !important;
    }
</style>

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
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                           || form.querySelector('input[name="_token"]')?.value;

            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(form.action, {
                method: 'POST',
                headers: headers,
                body: formData
            });

            const data = await response.json();
            if (response.ok && data.success) {
                statusDiv.className = 'font-sans text-[14px] text-center pt-2 text-emerald-400 font-semibold';
                statusDiv.textContent = data.message || 'Thank you! Redirecting...';
                statusDiv.classList.remove('hidden');
                form.reset();
                if (data.redirect_url) {
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 600);
                } else {
                    setTimeout(() => {
                        const modal = document.getElementById('popup-demo');
                        if (modal && typeof closeModal === 'function') {
                            closeModal(modal);
                        } else if (modal) {
                            modal.classList.remove('active');
                            modal.style.display = 'none';
                        }
                    }, 2500);
                }
            } else {
                statusDiv.className = 'font-sans text-[14px] text-center pt-2 text-rose-400 font-medium';
                statusDiv.textContent = data.message || 'Something went wrong. Please check your fields and try again.';
                statusDiv.classList.remove('hidden');
            }
        } catch (err) {
            statusDiv.className = 'font-sans text-[14px] text-center pt-2 text-rose-400 font-medium';
            statusDiv.textContent = 'Network error. Please try again.';
            statusDiv.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});
</script>

@if(app(\App\Services\TurnstileService::class)->isEnabled())
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
