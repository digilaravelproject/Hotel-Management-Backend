<header class="elementor elementor-8">
<div class="elementor-element elementor-element-6696936a e-con-full e-con e-parent">
<div class="elementor-element elementor-element-6d4e4f3c e-con-inner e-con e-child">
<div class="elementor-element elementor-element-6be5d8a elementor-widget elementor-widget-image">
<div class="elementor-widget-container">
<img src="{{ asset('uploads/') }}/2026/11/PAX-TV-LAUNCHER-2-2.png" alt="Pax TV Launcher" loading="lazy" class="attachment-full size-full" />
</div>
</div>
<div class="elementor-element elementor-element-2f403251 elementor-widget elementor-widget-nav-menu">
<div class="elementor-widget-container">

        <nav class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal e--pointer-none">
            <ul id="menu-1-header" class="elementor-nav-menu">
                <li class="menu-item"><a href="#home" class="elementor-item elementor-item-anchor">Home</a></li>
                <li class="menu-item"><a href="#about" class="elementor-item elementor-item-anchor">About</a></li>
                <li class="menu-item"><a href="#solutions" class="elementor-item elementor-item-anchor">Solutions</a></li>
                <li class="menu-item"><a href="#features" class="elementor-item elementor-item-anchor">Features</a></li>
                <li class="menu-item"><a href="#contact" class="elementor-item elementor-item-anchor">Contact Us</a></li>
                <li class="menu-item"><a href="{{ route('hotel.register') }}" class="elementor-item">Register Hotel</a></li>
                <li class="menu-item"><a href="{{ route('login') }}" class="elementor-item">Sign In</a></li>
            </ul>
        </nav>
        <div class="elementor-menu-toggle" role="button" tabindex="0" aria-label="Menu Toggle">
            <i class="fas fa-bars"></i>
        </div>
        <nav class="elementor-nav-menu--dropdown elementor-nav-menu__container" style="display:none;">
            <ul class="elementor-nav-menu">
                <li class="menu-item"><a href="#home" class="elementor-item">Home</a></li>
                <li class="menu-item"><a href="#about" class="elementor-item">About</a></li>
                <li class="menu-item"><a href="#solutions" class="elementor-item">Solutions</a></li>
                <li class="menu-item"><a href="#features" class="elementor-item">Features</a></li>
                <li class="menu-item"><a href="#contact" class="elementor-item">Contact Us</a></li>
                <li class="menu-item"><a href="{{ route('hotel.register') }}" class="elementor-item">Register Hotel</a></li>
                <li class="menu-item"><a href="{{ route('login') }}" class="elementor-item">Sign In</a></li>
            </ul>
        </nav>
        
</div>
</div>
<div class="elementor-element elementor-element-30231dbe elementor-widget elementor-widget-button">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper"><a href="#request-demo" class="elementor-button elementor-button-link elementor-size-sm" role="button" data-open-popup="popup-demo"><span class="elementor-button-content-wrapper"><span class="elementor-button-icon"><i class="fas fa-chevron-right"></i></span> <span class="elementor-button-text">Request Demo </span></span></a></div>
</div>
</div>
</div>
<div class="elementor-element elementor-element-2bf12f3a elementor-widget elementor-widget-html">
<div class="elementor-widget-container">
<script>
document.addEventListener("DOMContentLoaded", function () {
    function updateScroll() {
        document.body.classList.toggle("scrolled", window.scrollY > 0);
    }

    window.addEventListener("scroll", updateScroll, { passive: true });
    updateScroll();
});
</script>
</div>
</div>
</div>
<div class="elementor-element elementor-element-385c6528 e-con-full e-con e-parent">
<div class="elementor-element elementor-element-3c879f97 elementor-widget elementor-widget-form">
<div class="elementor-widget-container">

        <form class="elementor-form" method="POST" action="{{ route('contact.submit') }}">
            @csrf
            <div class="elementor-form-fields-wrapper elementor-labels-above">
                <div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-field-required">
                    <label for="form-field-name" class="elementor-field-label">Full Name</label>
                    <input size="1" type="text" name="name" id="form-field-name" class="elementor-field elementor-size-sm elementor-field-textual" placeholder="Enter your full name" required="required">
                </div>
                <div class="elementor-field-type-tel elementor-field-group elementor-column elementor-field-group-phone elementor-col-50 elementor-field-required">
                    <label for="form-field-phone" class="elementor-field-label">Phone Number</label>
                    <input size="1" type="tel" name="phone" id="form-field-phone" class="elementor-field elementor-size-sm elementor-field-textual" placeholder="+91 00000 00000" required="required">
                </div>
                <div class="elementor-field-type-email elementor-field-group elementor-column elementor-field-group-email elementor-col-100 elementor-field-required">
                    <label for="form-field-email" class="elementor-field-label">Email Address</label>
                    <input size="1" type="email" name="email" id="form-field-email" class="elementor-field elementor-size-sm elementor-field-textual" placeholder="you@company.com" required="required">
                </div>
                <div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-property elementor-col-50">
                    <label for="form-field-property" class="elementor-field-label">Hotel / Property Name</label>
                    <input size="1" type="text" name="property" id="form-field-property" class="elementor-field elementor-size-sm elementor-field-textual" placeholder="Name of your hotel">
                </div>
                <div class="elementor-field-type-number elementor-field-group elementor-column elementor-field-group-rooms elementor-col-50">
                    <label for="form-field-rooms" class="elementor-field-label">Number of Rooms</label>
                    <input size="1" type="number" name="rooms" id="form-field-rooms" class="elementor-field elementor-size-sm elementor-field-textual" placeholder="e.g. 120">
                </div>
                <div class="elementor-field-type-textarea elementor-field-group elementor-column elementor-field-group-message elementor-col-100">
                    <label for="form-field-message" class="elementor-field-label">Message</label>
                    <textarea class="elementor-field-textual elementor-field elementor-size-sm" name="message" id="form-field-message" rows="4" placeholder="Tell us about your requirements"></textarea>
                </div>
                <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                    <button type="submit" class="elementor-button elementor-size-sm">
                        <span><span class="elementor-button-text">Request Demo</span></span>
                    </button>
                </div>
            </div>
        </form>
        
</div>
</div>
</div>
</header>