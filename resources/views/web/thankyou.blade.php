@extends('web.layouts.app')

@section('title', 'Thank You - Demo Request Received | Pax TV Launcher')

@section('content')
<main class="min-h-[80vh] flex items-center justify-center relative overflow-hidden bg-[#050B18] py-20 px-4 sm:px-6">
    <!-- Background Ambient Glow Effects -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-[#00fbff]/15 rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="absolute bottom-10 right-10 w-[350px] h-[350px] bg-[#0E2556]/40 rounded-full blur-[120px] pointer-events-none -z-0"></div>

    <div class="relative z-10 max-w-[620px] w-full text-center">
        <!-- Glowing Checkmark Icon -->
        <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 sm:mb-8 rounded-full bg-gradient-to-tr from-[#0E2556] to-[#146A82] border-2 border-[#00fbff] flex items-center justify-center shadow-[0_0_40px_rgba(0,251,255,0.45)] animate-bounce duration-1000">
            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#00fbff]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Eyebrow Tag -->
        <span class="inline-block px-3.5 py-1 rounded-full text-[11px] sm:text-[12px] font-semibold tracking-[2px] uppercase bg-[#00fbff]/15 text-[#00fbff] border border-[#00fbff]/30 mb-4 font-heading">
            REQUEST RECEIVED
        </span>

        <!-- Main Headline -->
        <h1 class="font-heading text-[28px] sm:text-[40px] md:text-[46px] font-bold text-white leading-[1.2] mb-4">
            Thank You for Connecting!
        </h1>

        <!-- Subtitle -->
        <p class="font-heading text-[15px] sm:text-[17px] text-[#CBD5E1] font-normal leading-[1.6] max-w-[520px] mx-auto mb-8">
            We have received your demo inquiry. Our hospitality technology specialists will review your requirements and reach out within <span class="text-[#00fbff] font-bold">24 hours</span>.
        </p>

        <!-- Next Steps Card -->
        <div class="bg-gradient-to-br from-[#0E2556]/70 to-[#146A82]/50 border border-white/10 rounded-[16px] p-6 sm:p-7 backdrop-blur-md mb-8 text-left shadow-2xl">
            <h3 class="font-heading text-[15px] font-bold text-white mb-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-[#00fbff]"></i>
                What happens next?
            </h3>
            <ul class="space-y-3 font-heading text-[13.5px] text-[#CBD5E1]">
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-[#00fbff]/20 text-[#00fbff] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">1</span>
                    <span>A confirmation email with your details has been dispatched to your inbox.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-[#00fbff]/20 text-[#00fbff] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">2</span>
                    <span>Our product team will prepare a tailored live walkthrough for your property.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-[#00fbff]/20 text-[#00fbff] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">3</span>
                    <span>We will contact you via Phone/Email to coordinate a convenient demonstration time.</span>
                </li>
            </ul>
        </div>

        <!-- Action CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="{{ route('web.home') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-[10px] font-heading text-[14.5px] font-bold text-[#0E2556] bg-[#00fbff] hover:bg-[#33fcff] transition-all duration-300 shadow-[0_4px_20px_rgba(0,251,255,0.4)] cursor-pointer">
                <i class="fa-solid fa-house text-sm"></i>
                <span>Return to Homepage</span>
            </a>

            <a href="{{ route('web.home') }}#solutions" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-[10px] font-heading text-[14.5px] font-medium text-white bg-white/10 hover:bg-white/20 border border-white/15 transition-all duration-300 cursor-pointer">
                <span>Explore Solutions</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</main>
@endsection

