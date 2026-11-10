
<div id="popup-demo" class="paxtv-modal-overlay" aria-hidden="true" style="display:none;">
    <div class="paxtv-modal-dialog">
        <button type="button" class="paxtv-modal-close" aria-label="Close modal">&times;</button>
        <div class="elementor elementor-33">
            <div class="elementor-element elementor-element-6c45adf2 e-con-full e-con e-parent">
<div class="elementor-element elementor-element-5f969ab elementor-widget elementor-widget-heading">
<div class="elementor-widget-container">
<span class="elementor-heading-title elementor-size-default">GET IN TOUCH</span>
</div>
</div>
<div class="elementor-element elementor-element-3429d21f elementor-widget elementor-widget-heading">
<div class="elementor-widget-container">
<h2 class="elementor-heading-title elementor-size-default">Request a Demo</h2>
</div>
</div>
<div class="elementor-element elementor-element-23385367 elementor-widget elementor-widget-text-editor">
<div class="elementor-widget-container">
<div class="elementor-text-editor elementor-clearfix"><p>See how PAX TV Hospitality can transform your guests' in-room experience. Fill in the form and our team will get back to you shortly.</p></div>
</div>
</div>
<div class="elementor-element elementor-element-44ff68b0 elementor-widget elementor-widget-form">
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
        </div>
    </div>
</div>
