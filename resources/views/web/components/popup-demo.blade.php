<div id="popup-demo" class="paxtv-modal-overlay" aria-hidden="true" style="display:none;">
    <div class="paxtv-modal-dialog p-6 sm:p-8">
        <button type="button" class="paxtv-modal-close" aria-label="Close modal">&times;</button>
        
        <div class="space-y-2 mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-500/10 text-teal-400 border border-teal-500/20 font-mono">
                <i class="fa-solid fa-sparkles mr-1.5 text-[10px]"></i> Get In Touch
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-white font-heading tracking-tight">
                Request a Demo
            </h2>
            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                See how Pax TV Hospitality can transform your guests' in-room experience. Fill in your details below and our team will get back to you shortly.
            </p>
        </div>

        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="demo_name" class="block text-xs font-semibold text-slate-300">
                        Full Name <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="name" id="demo_name" required
                           placeholder="Enter your full name"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]">
                </div>

                <div class="space-y-1.5">
                    <label for="demo_phone" class="block text-xs font-semibold text-slate-300">
                        Phone Number <span class="text-rose-400">*</span>
                    </label>
                    <input type="tel" name="phone" id="demo_phone" required
                           placeholder="+91 99000 00000"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="demo_email" class="block text-xs font-semibold text-slate-300">
                    Business Email Address <span class="text-rose-400">*</span>
                </label>
                <input type="email" name="email" id="demo_email" required
                       placeholder="gm@luxuryresort.com"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="demo_property" class="block text-xs font-semibold text-slate-300">
                        Hotel / Property Name
                    </label>
                    <input type="text" name="property" id="demo_property"
                           placeholder="Name of your hotel"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]">
                </div>

                <div class="space-y-1.5">
                    <label for="demo_rooms" class="block text-xs font-semibold text-slate-300">
                        Total Rooms / TVs
                    </label>
                    <input type="number" name="rooms" id="demo_rooms"
                           placeholder="e.g. 150" min="1"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="demo_message" class="block text-xs font-semibold text-slate-300">
                    Specific Requirements or Questions
                </label>
                <textarea name="message" id="demo_message" rows="3"
                          placeholder="Tell us about your property and current TV setup..."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-[#1A9C9E] focus:ring-1 focus:ring-[#1A9C9E]"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-lg shadow-[#1A9C9E]/30 flex items-center justify-center space-x-2">
                    <span>Submit Demo Request</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>
            <p class="text-center text-[11px] text-slate-500">
                <i class="fa-solid fa-lock text-[10px] mr-1"></i> Your information is kept strictly confidential. No spam guaranteed.
            </p>
        </form>
    </div>
</div>
