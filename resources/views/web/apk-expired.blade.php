@extends('web.layouts.app')

@section('title', 'Download Link Expired | Pax TV Launcher')

@section('content')
<main class="min-h-[85vh] flex items-center justify-center relative overflow-hidden bg-[#050B18] py-20 px-4 sm:px-6">
    <!-- Ambient Glow Effects -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-[#00fbff]/10 rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="absolute bottom-10 right-10 w-[350px] h-[350px] bg-[#0E2556]/40 rounded-full blur-[120px] pointer-events-none -z-0"></div>

    <div class="relative z-10 max-w-[620px] w-full text-center">
        <!-- Glowing Alert Icon -->
        <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 sm:mb-8 rounded-full bg-gradient-to-tr from-[#0E2556] to-[#146A82] border-2 border-[#00fbff]/60 flex items-center justify-center shadow-[0_0_35px_rgba(0,251,255,0.35)]">
            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#00fbff]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Eyebrow Tag -->
        <span class="inline-block px-3.5 py-1 rounded-full text-[11px] sm:text-[12px] font-semibold tracking-[2px] uppercase bg-amber-500/15 text-amber-400 border border-amber-500/30 mb-4 font-heading">
            7-DAY ACCESS EXPIRED
        </span>

        <!-- Main Headline -->
        <h1 class="font-heading text-[26px] sm:text-[36px] md:text-[42px] font-bold text-white leading-[1.2] mb-4">
            Download Link Has Expired
        </h1>

        <!-- Subtitle -->
        <p class="font-heading text-[14.5px] sm:text-[16px] text-[#CBD5E1] font-normal leading-[1.6] max-w-[520px] mx-auto mb-8">
            To ensure the security and integrity of the <strong>PAX TV Hospitality</strong> application package, download links are time-limited to <strong>7 days</strong>.
        </p>

        <!-- Information Card -->
        <div class="bg-gradient-to-br from-[#0E2556]/70 to-[#146A82]/40 border border-white/10 rounded-[16px] p-6 sm:p-7 backdrop-blur-md mb-8 text-left shadow-2xl">
            <h3 class="font-heading text-[15px] font-bold text-white mb-2.5 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-[#00fbff]"></i>
                How do I get a new download link?
            </h3>
            <p class="font-heading text-[13.5px] text-[#CBD5E1] leading-[1.6] m-0">
                You can easily request a fresh download link by submitting a demo request on our website, or you can contact our technical support directly at
                <a href="mailto:{{ $supportEmail ?? 'support@paxtvlauncher.com' }}" class="text-[#00fbff] font-bold underline hover:text-[#33fcff] ml-1">
                    {{ $supportEmail ?? 'support@paxtvlauncher.com' }}
                </a>.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
            <a href="https://paxtvlauncher.com" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3 rounded-[10px] text-[14px] font-bold text-[#0E2556] bg-[#00fbff] font-heading hover:bg-[#33fcff] transition-all duration-300 shadow-[0_4px_18px_rgba(0,251,255,0.4)]">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                <span>Back to Homepage</span>
            </a>
            <a href="https://paxtvlauncher.com/#contactus" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-[10px] text-[14px] font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-sm font-heading transition-all duration-300">
                <i class="fa-solid fa-envelope mr-2 text-xs text-[#00fbff]"></i>
                <span>Request New Demo</span>
            </a>
        </div>
    </div>
</main>
@endsection

