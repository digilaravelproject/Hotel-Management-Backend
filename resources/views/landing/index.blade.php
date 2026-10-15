<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/png" href="{{ asset('landing-wp/images/PAX_TV_Hospitality_Logo-removebg-preview.png') }}">
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<title>PAX TV Hospitality | Smart In-Room Entertainment & Guest Experience</title>
<meta name='robots' content='max-image-preview:large' />
<link rel='stylesheet' id='astra-theme-css-css' href='{{ asset('landing-wp/css/main.min.css') }}' media='all' />
<link rel='stylesheet' id='astra-theme-inline-css' href='{{ asset('landing-wp/css/astra-theme-inline.css') }}' media='all' />
<link rel='stylesheet' id='global-styles-css' href='{{ asset('landing-wp/css/global-styles.css') }}' media='all' />
<link rel='stylesheet' id='elementor-frontend-css' href='{{ asset('landing-wp/css/frontend.min.css') }}' media='all' />
<link rel='stylesheet' id='elementor-post-250-css' href='{{ asset('landing-wp/css/post-250.css') }}' media='all' />
<link rel='stylesheet' id='widget-image-css' href='{{ asset('landing-wp/css/widget-image.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-nav-menu-css' href='{{ asset('landing-wp/css/widget-nav-menu.min.css') }}' media='all' />
<link rel='stylesheet' id='e-sticky-css' href='{{ asset('landing-wp/css/sticky.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-heading-css' href='{{ asset('landing-wp/css/widget-heading.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-social-icons-css' href='{{ asset('landing-wp/css/widget-social-icons.min.css') }}' media='all' />
<link rel='stylesheet' id='e-apple-webkit-css' href='{{ asset('landing-wp/css/apple-webkit.min.css') }}' media='all' />
<link rel='stylesheet' id='e-animation-fadeInUp-css' href='{{ asset('landing-wp/css/fadeInUp.min.css') }}' media='all' />
<link rel='stylesheet' id='e-animation-fadeInLeft-css' href='{{ asset('landing-wp/css/fadeInLeft.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-divider-css' href='{{ asset('landing-wp/css/widget-divider.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-icon-list-css' href='{{ asset('landing-wp/css/widget-icon-list.min.css') }}' media='all' />
<link rel='stylesheet' id='widget-form-css' href='{{ asset('landing-wp/css/widget-form.min.css') }}' media='all' />
<link rel='stylesheet' id='e-animation-fadeInRight-css' href='{{ asset('landing-wp/css/fadeInRight.min.css') }}' media='all' />
<link rel='stylesheet' id='e-animation-zoomIn-css' href='{{ asset('landing-wp/css/zoomIn.min.css') }}' media='all' />
<link rel='stylesheet' id='e-popup-css' href='{{ asset('landing-wp/css/popup.min.css') }}' media='all' />
<link rel='stylesheet' id='elementor-post-9-css' href='{{ asset('landing-wp/css/post-9.css') }}' media='all' />
<link rel='stylesheet' id='widget-icon-box-css' href='{{ asset('landing-wp/css/widget-icon-box.min.css') }}' media='all' />
<link rel='stylesheet' id='elementor-post-10-css' href='{{ asset('landing-wp/css/post-10.css') }}' media='all' />
<link rel='stylesheet' id='elementor-post-78-css' href='{{ asset('landing-wp/css/post-78.css') }}' media='all' />
<link rel='stylesheet' id='elementor-post-33-css' href='{{ asset('landing-wp/css/post-33.css') }}' media='all' />
<link rel='stylesheet' id='elementor-gf-poppins-css' href='https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&#038;display=swap' media='all' />
<link rel='stylesheet' id='elementor-gf-playfairdisplay-css' href='https://fonts.googleapis.com/css?family=Playfair+Display:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&#038;display=swap' media='all' />
<link rel='stylesheet' id='elementor-gf-roboto-css' href='https://fonts.googleapis.com/css?family=Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&#038;display=swap' media='all' />
<link rel='stylesheet' id='elementor-gf-robotoslab-css' href='https://fonts.googleapis.com/css?family=Roboto+Slab:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&#038;display=swap' media='all' />
<script id="jquery-core-js" src="{{ asset('landing-wp/js/jquery.min.js') }}"></script>
<script id="jquery-migrate-js" src="{{ asset('landing-wp/js/jquery-migrate.min.js') }}"></script>
<style>
/* Smooth Scroll & Visibility */
html, body {
    overflow-x: hidden !important;
    overflow-y: auto !important;
    height: auto !important;
    min-height: 100vh !important;
    scroll-behavior: smooth;
}
#page {
    overflow: visible !important;
    height: auto !important;
    min-height: 100vh !important;
}

/* Animations: start visible with smooth transitions */
.elementor-invisible {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.elementor-invisible.animated,
.animated {
    opacity: 1 !important;
    visibility: visible !important;
}

@keyframes tajFadeInUp {
    from { opacity: 0; transform: translate3d(0, 30px, 0); }
    to { opacity: 1; transform: translate3d(0, 0, 0); }
}
.fadeInUp { animation: tajFadeInUp 0.8s both !important; }

@keyframes tajFadeInLeft {
    from { opacity: 0; transform: translate3d(-30px, 0, 0); }
    to { opacity: 1; transform: translate3d(0, 0, 0); }
}
.fadeInLeft { animation: tajFadeInLeft 0.8s both !important; }

@keyframes tajFadeInRight {
    from { opacity: 0; transform: translate3d(30px, 0, 0); }
    to { opacity: 1; transform: translate3d(0, 0, 0); }
}
.fadeInRight { animation: tajFadeInRight 0.8s both !important; }

@keyframes tajZoomIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
.zoomIn { animation: tajZoomIn 0.8s both !important; }

/* Modal overlay styling */
#taj-modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999999 !important;
    display: none;
    align-items: center !important;
    justify-content: center !important;
    background-color: rgba(11, 18, 32, 0.85) !important;
    backdrop-filter: blur(8px) !important;
    -webkit-backdrop-filter: blur(8px) !important;
    overflow-y: auto !important;
    padding: 20px !important;
    box-sizing: border-box !important;
}
#taj-modal-backdrop.is-open {
    display: flex !important;
}
#taj-modal-backdrop .dialog-widget-content {
    position: relative !important;
    max-width: 960px !important;
    width: 100% !important;
    margin: auto !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8) !important;
    animation: tajZoomIn 0.35s ease !important;
}
#taj-modal-backdrop [data-elementor-type="popup"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
#taj-modal-backdrop .dialog-close-button {
    position: absolute !important;
    top: 18px !important;
    right: 18px !important;
    width: 38px !important;
    height: 38px !important;
    border-radius: 50% !important;
    background-color: #E8B65A !important;
    color: #0B1220 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    z-index: 99999 !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
}
#taj-modal-backdrop .dialog-close-button:hover {
    background-color: #ffffff !important;
    color: #E8B65A !important;
    transform: rotate(90deg) scale(1.1) !important;
}
.taj-form-alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
    font-weight: 500;
    display: none;
}
.taj-form-alert-success { background-color: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
.taj-form-alert-error { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
</style>

</head>

<body itemtype='https://schema.org/WebPage' itemscope='itemscope' class="home wp-singular page-template-default page page-id-10 wp-embed-responsive wp-theme-astra ast-desktop ast-page-builder-template ast-no-sidebar astra-4.14.0 ast-single-post ast-inherit-site-logo-transparent ast-hfb-header elementor-default elementor-kit-9 elementor-page elementor-page-10">
			<script data-cfasync="false">
			(function(){var mq=window.matchMedia('(max-width:921.99px)');function apply(isMobile){var b=document.body.classList;if(isMobile){b.add('ast-header-break-point');b.remove('ast-desktop');}else{b.remove('ast-header-break-point');b.add('ast-desktop');}}apply(mq.matches);if(mq.addEventListener){mq.addEventListener('change',function(e){apply(e.matches);});}else if(mq.addListener){mq.addListener(function(e){apply(e.matches);});}})();
			</script>
			
<a
	class="skip-link screen-reader-text"
	href="#content">
		Skip to content</a>

<div
class="hfeed site" id="page">
			<header data-elementor-type="header" data-elementor-id="78" class="elementor elementor-78 elementor-location-header" data-elementor-post-type="elementor_library">
			<div class="elementor-element elementor-element-9df812c e-flex e-con-boxed e-con e-parent" data-id="9df812c" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;sticky&quot;:&quot;top&quot;,&quot;position&quot;:&quot;fixed&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_offset&quot;:0,&quot;sticky_effects_offset&quot;:0,&quot;sticky_anchor_link_offset&quot;:0}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-6740b18 e-con-full e-flex e-con e-child" data-id="6740b18" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-2020d0f elementor-widget__width-initial elementor-widget elementor-widget-image" data-id="2020d0f" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
																<a href="">
							<img fetchpriority="high" width="666" height="375" src="{{ asset('landing-wp/images/PAX_TV_Hospitality_Logo-removebg-preview.png') }}" class="attachment-full size-full wp-image-327" alt="" srcset="{{ asset('landing-wp/images/PAX_TV_Hospitality_Logo-removebg-preview.png') }} 666w, {{ asset('landing-wp/images/PAX_TV_Hospitality_Logo-removebg-preview-300x169.png') }} 300w" sizes="(max-width: 666px) 100vw, 666px" />								</a>
															</div>
				<div class="elementor-element elementor-element-442093b elementor-nav-menu__align-end elementor-nav-menu--stretch elementor-nav-menu--dropdown-tablet elementor-nav-menu__text-align-aside elementor-nav-menu--toggle elementor-nav-menu--burger elementor-widget elementor-widget-nav-menu" data-id="442093b" data-element_type="widget" data-e-type="widget" data-settings="{&quot;full_width&quot;:&quot;stretch&quot;,&quot;layout&quot;:&quot;horizontal&quot;,&quot;submenu_icon&quot;:{&quot;value&quot;:&quot;&lt;svg aria-hidden=\&quot;true\&quot; class=\&quot;e-font-icon-svg e-fas-caret-down\&quot; viewBox=\&quot;0 0 320 512\&quot; xmlns=\&quot;http:\/\/www.w3.org\/2000\/svg\&quot;&gt;&lt;path d=\&quot;M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z\&quot;&gt;&lt;\/path&gt;&lt;\/svg&gt;&quot;,&quot;library&quot;:&quot;fa-solid&quot;},&quot;toggle&quot;:&quot;burger&quot;}" data-widget_type="nav-menu.default">
								<nav aria-label="Menu" class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal e--pointer-none">
				<ul id="menu-1-442093b" class="elementor-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-10 current_page_item menu-item-51"><a href="/" aria-current="page" class="elementor-item elementor-item-active menu-link">Home</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-52"><a href="#about" class="elementor-item elementor-item-anchor menu-link">About</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-53"><a href="#solutions" class="elementor-item elementor-item-anchor menu-link">Solutions</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-54"><a href="#gallery" class="elementor-item elementor-item-anchor menu-link">Gallery</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-56"><a href="#contactus" class="elementor-item elementor-item-anchor menu-link">Contact Us</a></li>
</ul>			</nav>
					<div class="elementor-menu-toggle" role="button" tabindex="0" aria-label="Menu Toggle" aria-expanded="false">
			<svg aria-hidden="true" role="presentation" class="elementor-menu-toggle__icon--open e-font-icon-svg e-eicon-menu-bar" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M104 333H896C929 333 958 304 958 271S929 208 896 208H104C71 208 42 237 42 271S71 333 104 333ZM104 583H896C929 583 958 554 958 521S929 458 896 458H104C71 458 42 487 42 521S71 583 104 583ZM104 833H896C929 833 958 804 958 771S929 708 896 708H104C71 708 42 737 42 771S71 833 104 833Z"></path></svg><svg aria-hidden="true" role="presentation" class="elementor-menu-toggle__icon--close e-font-icon-svg e-eicon-close" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M742 167L500 408 258 167C246 154 233 150 217 150 196 150 179 158 167 167 154 179 150 196 150 212 150 229 154 242 171 254L408 500 167 742C138 771 138 800 167 829 196 858 225 858 254 829L496 587 738 829C750 842 767 846 783 846 800 846 817 842 829 829 842 817 846 804 846 783 846 767 842 750 829 737L588 500 833 258C863 229 863 200 833 171 804 137 775 137 742 167Z"></path></svg>		</div>
					<nav class="elementor-nav-menu--dropdown elementor-nav-menu__container" aria-hidden="true">
				<ul id="menu-2-442093b" class="elementor-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-10 current_page_item menu-item-51"><a href="/" aria-current="page" class="elementor-item elementor-item-active menu-link" tabindex="-1">Home</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-52"><a href="#about" class="elementor-item elementor-item-anchor menu-link" tabindex="-1">About</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-53"><a href="#solutions" class="elementor-item elementor-item-anchor menu-link" tabindex="-1">Solutions</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-54"><a href="#gallery" class="elementor-item elementor-item-anchor menu-link" tabindex="-1">Gallery</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-56"><a href="#contactus" class="elementor-item elementor-item-anchor menu-link" tabindex="-1">Contact Us</a></li>
</ul>			</nav>
						</div>
				<div class="elementor-element elementor-element-9748283 elementor-widget elementor-widget-button" data-id="9748283" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjI1MCIsInRvZ2dsZSI6ZmFsc2V9">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-chevron-right" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg>			</span>
									<span class="elementor-button-text">Request Demo </span>
					</span>
					</a>
								</div>
				</div>
				<div class="elementor-element elementor-element-8372fea elementor-widget elementor-widget-html" data-id="8372fea" data-element_type="widget" data-e-type="widget" data-widget_type="html.default">
					<script>
document.addEventListener("DOMContentLoaded", function () {
    function updateScroll() {
        document.body.classList.toggle("scrolled", window.scrollY > 0);
    }

    window.addEventListener("scroll", updateScroll, { passive: true });
    updateScroll();
});
</script>				</div>
					</div>
				</div>
</header>
			<div id="content" class="site-content">
		<div class="ast-container">
		

	<div id="primary" class="content-area primary">

		
					<main id="main" class="site-main">
				<article
class="post-10 page type-page status-publish ast-article-single" id="post-10" itemtype="https://schema.org/CreativeWork" itemscope="itemscope">
	
				<header class="entry-header ast-no-thumbnail ast-no-title ast-header-without-markup">
							</header> <!-- .entry-header -->
		
<div class="entry-content clear"
	itemprop="text">

	
			<div data-elementor-type="wp-post" data-elementor-id="10" class="elementor elementor-10" data-elementor-post-type="page">
				<div class="elementor-element elementor-element-232b0bdd e-flex e-con-boxed e-con e-parent" data-id="232b0bdd" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-8d989f0 e-con-full e-flex e-con e-child" data-id="8d989f0" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-35f7664b e-con-full e-flex e-con e-child" data-id="35f7664b" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-d222229 elementor-widget elementor-widget-heading" data-id="d222229" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Premium Hotel TV Solution</h6>				</div>
				<div class="elementor-element elementor-element-5e665580 elementor-widget elementor-widget-heading" data-id="5e665580" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h1 class="elementor-heading-title elementor-size-default">A Smarter Stay Experience</h1>				</div>
				<div class="elementor-element elementor-element-37fd061f elementor-widget elementor-widget-text-editor" data-id="37fd061f" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Transform every guest room into a personalized entertainment and hospitality hub with our advanced Hotel TV application.</p>								</div>
		<div class="elementor-element elementor-element-3a9a1a55 e-con-full e-flex e-con e-child" data-id="3a9a1a55" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-1a661d14 elementor-widget elementor-widget-button" data-id="1a661d14" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjI1MCIsInRvZ2dsZSI6ZmFsc2V9">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Request Demo</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-52adba7a elementor-widget elementor-widget-button" data-id="52adba7a" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">▶  Watch Video</span>
					</span>
					</a>
								</div>
				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-39b838b6 e-flex e-con-boxed e-con e-parent" data-id="39b838b6" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-23b2548c elementor-view-stacked elementor-position-inline-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="23b2548c" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-sliders-h" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M496 384H160v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h80v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h336c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160h-80v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h336v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h80c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16C7.2 64 0 71.2 0 80v32c0 8.8 7.2 16 16 16h208v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h208c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Customizable UI						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Branded experience for your hotel					</p>
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-63ebd8ba elementor-view-stacked elementor-position-inline-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="63ebd8ba" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-far-comments" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Multi-Language						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Global guest support					</p>
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-5c7acc14 elementor-view-stacked elementor-position-inline-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="5c7acc14" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-far-building" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M436 480h-20V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v456H12c-6.627 0-12 5.373-12 12v20h448v-20c0-6.627-5.373-12-12-12zM128 76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76zm0 96c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40zm52 148h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12zm76 160h-64v-84c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v84zm64-172c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Hotel Information						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Showcase amenities and services					</p>
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-53124fec elementor-view-stacked elementor-position-inline-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="53124fec" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-shield-alt" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M466.5 83.7l-192-80a48.15 48.15 0 0 0-36.9 0l-192 80C27.7 91.1 16 108.6 16 128c0 198.5 114.5 335.7 221.5 380.3 11.8 4.9 25.1 4.9 36.9 0C360.1 472.6 496 349.3 496 128c0-19.4-11.7-36.9-29.5-44.3zM256.1 446.3l-.1-381 175.9 73.3c-3.3 151.4-82.1 261.1-175.8 307.7z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Secure &amp; Reliable						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Built for performance and guest privacy					</p>
				
			</div>
			
		</div>
						</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-75edb124 e-flex e-con-boxed e-con e-parent" data-id="75edb124" data-element_type="container" data-e-type="container" id="about" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-68e71f63 e-con-full e-flex elementor-invisible e-con e-child" data-id="68e71f63" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
				<div class="elementor-element elementor-element-6f59d2e6 elementor-widget elementor-widget-image" data-id="6f59d2e6" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img decoding="async" width="1717" height="916" src="{{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort.png') }}" class="attachment-full size-full wp-image-124" alt="" srcset="{{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort.png') }} 1717w, {{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort-300x160.png') }} 300w, {{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort-1024x546.png') }} 1024w, {{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort-768x410.png') }} 768w, {{ asset('landing-wp/images/Sunset-Welcome-at-the-Oceanfront-Resort-1536x819.png') }} 1536w" sizes="(max-width: 1717px) 100vw, 1717px" />															</div>
				</div>
		<div class="elementor-element elementor-element-9db4d27 e-con-full e-flex elementor-invisible e-con e-child" data-id="9db4d27" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
				<div class="elementor-element elementor-element-77570e71 elementor-widget elementor-widget-heading" data-id="77570e71" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">About Us</h6>				</div>
				<div class="elementor-element elementor-element-41ba8928 elementor-widget elementor-widget-heading" data-id="41ba8928" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Enhancing Hospitality Through Smart Technology</h2>				</div>
				<div class="elementor-element elementor-element-7441494f elementor-widget elementor-widget-text-editor" data-id="7441494f" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>We provide advanced Hotel TV solutions that combine entertainment, information and hospitality services into a single, easy-to-use application, designed to create memorable guest experiences.</p>								</div>
				<div class="elementor-element elementor-element-43de029f elementor-widget elementor-widget-button" data-id="43de029f" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Learn More  →</span>
					</span>
					</a>
								</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-ed456f2 e-flex e-con-boxed e-con e-parent" data-id="ed456f2" data-element_type="container" data-e-type="container" id="solutions" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-5a4b311 e-con-full e-flex e-con e-child" data-id="5a4b311" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-dfdd525 elementor-widget elementor-widget-heading" data-id="dfdd525" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Tailored For Your Hotel</h6>				</div>
				<div class="elementor-element elementor-element-77582d9 elementor-widget elementor-widget-heading" data-id="77582d9" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Our Solutions</h2>				</div>
				<div class="elementor-element elementor-element-0a897d5 elementor-widget elementor-widget-text-editor" data-id="0a897d5" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>A complete in-room entertainment and guest engagement solution designed for modern hospitality needs.</p>								</div>
		<div class="elementor-element elementor-element-2ad9ba7 e-con-full e-flex e-con e-child" data-id="2ad9ba7" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-f2953ba e-con-full e-flex e-con e-child" data-id="f2953ba" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-b68e5ed elementor-view-stacked elementor-shape-rounded elementor-widget elementor-widget-icon" data-id="b68e5ed" data-element_type="widget" data-e-type="widget" data-widget_type="icon.default">
							<div class="elementor-icon-wrapper">
			<div class="elementor-icon">
			<svg aria-hidden="true" class="e-font-icon-svg e-fas-tv" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M592 0H48A48 48 0 0 0 0 48v320a48 48 0 0 0 48 48h240v32H112a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16H352v-32h240a48 48 0 0 0 48-48V48a48 48 0 0 0-48-48zm-16 352H64V64h512z"></path></svg>			</div>
		</div>
						</div>
				<div class="elementor-element elementor-element-7774e04 elementor-widget elementor-widget-heading" data-id="7774e04" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h4 class="elementor-heading-title elementor-size-default">Hotel TV Application</h4>				</div>
				<div class="elementor-element elementor-element-4af96d3 elementor-widget elementor-widget-text-editor" data-id="4af96d3" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Custom branded interface with all essential features.</p>								</div>
				<div class="elementor-element elementor-element-f086502 elementor-widget elementor-widget-button" data-id="f086502" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Learn More  →</span>
					</span>
					</a>
								</div>
				</div>
		<div class="elementor-element elementor-element-9e1e32a e-con-full e-flex e-con e-child" data-id="9e1e32a" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-522bea4 elementor-view-stacked elementor-shape-rounded elementor-widget elementor-widget-icon" data-id="522bea4" data-element_type="widget" data-e-type="widget" data-widget_type="icon.default">
							<div class="elementor-icon-wrapper">
			<div class="elementor-icon">
			<svg aria-hidden="true" class="e-font-icon-svg e-fas-cogs" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M512.1 191l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0L552 6.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zm-10.5-58.8c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.7-82.4 14.3-52.8 52.8zM386.3 286.1l33.7 16.8c10.1 5.8 14.5 18.1 10.5 29.1-8.9 24.2-26.4 46.4-42.6 65.8-7.4 8.9-20.2 11.1-30.3 5.3l-29.1-16.8c-16 13.7-34.6 24.6-54.9 31.7v33.6c0 11.6-8.3 21.6-19.7 23.6-24.6 4.2-50.4 4.4-75.9 0-11.5-2-20-11.9-20-23.6V418c-20.3-7.2-38.9-18-54.9-31.7L74 403c-10 5.8-22.9 3.6-30.3-5.3-16.2-19.4-33.3-41.6-42.2-65.7-4-10.9.4-23.2 10.5-29.1l33.3-16.8c-3.9-20.9-3.9-42.4 0-63.4L12 205.8c-10.1-5.8-14.6-18.1-10.5-29 8.9-24.2 26-46.4 42.2-65.8 7.4-8.9 20.2-11.1 30.3-5.3l29.1 16.8c16-13.7 34.6-24.6 54.9-31.7V57.1c0-11.5 8.2-21.5 19.6-23.5 24.6-4.2 50.5-4.4 76-.1 11.5 2 20 11.9 20 23.6v33.6c20.3 7.2 38.9 18 54.9 31.7l29.1-16.8c10-5.8 22.9-3.6 30.3 5.3 16.2 19.4 33.2 41.6 42.1 65.8 4 10.9.1 23.2-10 29.1l-33.7 16.8c3.9 21 3.9 42.5 0 63.5zm-117.6 21.1c59.2-77-28.7-164.9-105.7-105.7-59.2 77 28.7 164.9 105.7 105.7zm243.4 182.7l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0l8.2-14.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zM501.6 431c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.6-82.4 14.3-52.8 52.8z"></path></svg>			</div>
		</div>
						</div>
				<div class="elementor-element elementor-element-bff0ba5 elementor-widget elementor-widget-heading" data-id="bff0ba5" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h4 class="elementor-heading-title elementor-size-default">System Integration</h4>				</div>
				<div class="elementor-element elementor-element-4d71b19 elementor-widget elementor-widget-text-editor" data-id="4d71b19" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Integrate with hotel PMS, services and third-party apps.</p>								</div>
				<div class="elementor-element elementor-element-be2bc7c elementor-widget elementor-widget-button" data-id="be2bc7c" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Learn More  →</span>
					</span>
					</a>
								</div>
				</div>
		<div class="elementor-element elementor-element-8355edd e-con-full e-flex e-con e-child" data-id="8355edd" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-34ff508 elementor-view-stacked elementor-shape-rounded elementor-widget elementor-widget-icon" data-id="34ff508" data-element_type="widget" data-e-type="widget" data-widget_type="icon.default">
							<div class="elementor-icon-wrapper">
			<div class="elementor-icon">
			<svg aria-hidden="true" class="e-font-icon-svg e-fas-headset" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M192 208c0-17.67-14.33-32-32-32h-16c-35.35 0-64 28.65-64 64v48c0 35.35 28.65 64 64 64h16c17.67 0 32-14.33 32-32V208zm176 144c35.35 0 64-28.65 64-64v-48c0-35.35-28.65-64-64-64h-16c-17.67 0-32 14.33-32 32v112c0 17.67 14.33 32 32 32h16zM256 0C113.18 0 4.58 118.83 0 256v16c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-16c0-114.69 93.31-208 208-208s208 93.31 208 208h-.12c.08 2.43.12 165.72.12 165.72 0 23.35-18.93 42.28-42.28 42.28H320c0-26.51-21.49-48-48-48h-32c-26.51 0-48 21.49-48 48s21.49 48 48 48h181.72c49.86 0 90.28-40.42 90.28-90.28V256C507.42 118.83 398.82 0 256 0z"></path></svg>			</div>
		</div>
						</div>
				<div class="elementor-element elementor-element-a3e6d14 elementor-widget elementor-widget-heading" data-id="a3e6d14" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h4 class="elementor-heading-title elementor-size-default">Ongoing Support</h4>				</div>
				<div class="elementor-element elementor-element-b554ee0 elementor-widget elementor-widget-text-editor" data-id="b554ee0" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Dedicated support and regular updates for seamless operation.</p>								</div>
				<div class="elementor-element elementor-element-72f7119 elementor-widget elementor-widget-button" data-id="72f7119" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Learn More  →</span>
					</span>
					</a>
								</div>
				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-f47ed39 e-con-full e-flex e-con e-child" data-id="f47ed39" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-293f5f5 elementor-widget elementor-widget-image" data-id="293f5f5" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="941" height="1220" src="{{ asset('landing-wp/images/Luxury-Resort-Bedroom-at-Tropical-Sunset-e1791466670240.png') }}" class="attachment-full size-full wp-image-104" alt="" srcset="{{ asset('landing-wp/images/Luxury-Resort-Bedroom-at-Tropical-Sunset-e1791466670240.png') }} 941w, {{ asset('landing-wp/images/Luxury-Resort-Bedroom-at-Tropical-Sunset-e1791466670240-231x300.png') }} 231w, {{ asset('landing-wp/images/Luxury-Resort-Bedroom-at-Tropical-Sunset-e1791466670240-790x1024.png') }} 790w, {{ asset('landing-wp/images/Luxury-Resort-Bedroom-at-Tropical-Sunset-e1791466670240-768x996.png') }} 768w" sizes="(max-width: 941px) 100vw, 941px" />															</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-64ad2a93 e-flex e-con-boxed e-con e-parent" data-id="64ad2a93" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-1d6da3e6 e-con-full e-flex e-con e-child" data-id="1d6da3e6" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-6c6c80a4 elementor-widget elementor-widget-heading" data-id="6c6c80a4" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Global Guest Experience</h6>				</div>
				<div class="elementor-element elementor-element-48acd4cb elementor-widget elementor-widget-heading" data-id="48acd4cb" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Multi-Language Support</h2>				</div>
				<div class="elementor-element elementor-element-6b79a362 elementor-widget elementor-widget-text-editor" data-id="6b79a362" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Cater to international guests with multiple language options and easy navigation.</p>								</div>
		<div class="elementor-element elementor-element-3cffbd1b e-con-full e-flex e-con e-child" data-id="3cffbd1b" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-60a186b8 elementor-widget elementor-widget-button" data-id="60a186b8" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">English</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-6586ebfa elementor-widget elementor-widget-button" data-id="6586ebfa" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">हिंदी</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-38a95689 elementor-widget elementor-widget-button" data-id="38a95689" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">मराठी</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-61e80e80 elementor-widget elementor-widget-button" data-id="61e80e80" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">ગુજરાતી</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-4e4ca25f elementor-widget elementor-widget-button" data-id="4e4ca25f" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">தமிழ்</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-67bac64 elementor-widget elementor-widget-button" data-id="67bac64" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">ಕನ್ನಡ</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-4b2c7c99 elementor-widget elementor-widget-button" data-id="4b2c7c99" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">తెలుగు</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-43b95fe1 elementor-widget elementor-widget-button" data-id="43b95fe1" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">বাংলা</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-2a01ae80 elementor-widget elementor-widget-button" data-id="2a01ae80" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Français</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-596f2807 elementor-widget elementor-widget-button" data-id="596f2807" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Deutsch</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-da59fb9 elementor-widget elementor-widget-button" data-id="da59fb9" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Español</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-58da9d34 elementor-widget elementor-widget-button" data-id="58da9d34" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Português</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-ce7579c elementor-widget elementor-widget-button" data-id="ce7579c" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">中文</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-59e92941 elementor-widget elementor-widget-button" data-id="59e92941" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">عربى</span>
					</span>
					</a>
								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-4894003a e-con-full e-flex e-con e-child" data-id="4894003a" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-6ef1a632 elementor-widget elementor-widget-image" data-id="6ef1a632" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1448" height="1086" src="{{ asset('landing-wp/images/Sunset-Luxury-Suite-with-Multilingual-TV.png') }}" class="attachment-full size-full wp-image-143" alt="" srcset="{{ asset('landing-wp/images/Sunset-Luxury-Suite-with-Multilingual-TV.png') }} 1448w, {{ asset('landing-wp/images/Sunset-Luxury-Suite-with-Multilingual-TV-300x225.png') }} 300w, {{ asset('landing-wp/images/Sunset-Luxury-Suite-with-Multilingual-TV-1024x768.png') }} 1024w, {{ asset('landing-wp/images/Sunset-Luxury-Suite-with-Multilingual-TV-768x576.png') }} 768w" sizes="(max-width: 1448px) 100vw, 1448px" />															</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-1f911727 e-flex e-con-boxed e-con e-parent" data-id="1f911727" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-1bbd9dda e-con-full e-flex elementor-invisible e-con e-child" data-id="1bbd9dda" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
				<div class="elementor-element elementor-element-bcd77ac elementor-widget elementor-widget-image" data-id="bcd77ac" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1448" height="1086" src="{{ asset('landing-wp/images/Luxury-Suite-at-Sunset.png') }}" class="attachment-full size-full wp-image-136" alt="" srcset="{{ asset('landing-wp/images/Luxury-Suite-at-Sunset.png') }} 1448w, {{ asset('landing-wp/images/Luxury-Suite-at-Sunset-300x225.png') }} 300w, {{ asset('landing-wp/images/Luxury-Suite-at-Sunset-1024x768.png') }} 1024w, {{ asset('landing-wp/images/Luxury-Suite-at-Sunset-768x576.png') }} 768w" sizes="(max-width: 1448px) 100vw, 1448px" />															</div>
				</div>
		<div class="elementor-element elementor-element-872e8c6 e-con-full e-flex elementor-invisible e-con e-child" data-id="872e8c6" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
				<div class="elementor-element elementor-element-46d2549f elementor-widget elementor-widget-heading" data-id="46d2549f" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Everything Your Guests Need</h6>				</div>
				<div class="elementor-element elementor-element-f30ac51 elementor-widget elementor-widget-heading" data-id="f30ac51" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Hotel Information &amp; Services</h2>				</div>
				<div class="elementor-element elementor-element-5508e2db elementor-widget elementor-widget-text-editor" data-id="5508e2db" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Showcase hotel amenities, dining options, facilities and local attractions directly on the TV.</p>								</div>
		<div class="elementor-element elementor-element-5861fb01 e-con-full e-flex e-con e-child" data-id="5861fb01" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-47e9bf4c elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="47e9bf4c" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-far-building" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M436 480h-20V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v456H12c-6.627 0-12 5.373-12 12v20h448v-20c0-6.627-5.373-12-12-12zM128 76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76zm0 96c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40zm52 148h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12zm76 160h-64v-84c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v84zm64-172c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Hotel Information						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-1ce23c66 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="1ce23c66" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-utensils" viewBox="0 0 416 512" xmlns="http://www.w3.org/2000/svg"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 486.7c.7 13.7-10.2 25.3-24 25.3H80c-13.7 0-24.7-11.5-24-25.3l12.9-238.1C27.7 233.6 0 196.2 0 144 0 109.6 15.3 19.9 16.1 15.2 19.3-5.1 61.4-5.4 64 16.3v141.2c1.3 3.4 15.1 3.2 16 0 1.4-25.3 7.9-139.2 8-141.8 3.3-20.8 44.7-20.8 47.9 0 .2 2.7 6.6 116.5 8 141.8.9 3.2 14.8 3.4 16 0V16.3c2.6-21.6 44.8-21.4 48-1.1zm119.2 285.7l-15 185.1c-1.2 14 9.9 26 23.9 26h56c13.3 0 24-10.7 24-24V24c0-13.2-10.7-24-24-24-82.5 0-221.4 178.5-64.9 300.9z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Dining &amp; Restaurants						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-3b6649 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="3b6649" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-landmark" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M501.62 92.11L267.24 2.04a31.958 31.958 0 0 0-22.47 0L10.38 92.11A16.001 16.001 0 0 0 0 107.09V144c0 8.84 7.16 16 16 16h480c8.84 0 16-7.16 16-16v-36.91c0-6.67-4.14-12.64-10.38-14.98zM64 192v160H48c-8.84 0-16 7.16-16 16v48h448v-48c0-8.84-7.16-16-16-16h-16V192h-64v160h-96V192h-64v160h-96V192H64zm432 256H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h480c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Local Attractions						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-3827b11a elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="3827b11a" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-concierge-bell" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M288 130.54V112h16c8.84 0 16-7.16 16-16V80c0-8.84-7.16-16-16-16h-96c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h16v18.54C115.49 146.11 32 239.18 32 352h448c0-112.82-83.49-205.89-192-221.46zM496 384H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h480c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							In-Room Services						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-54375939 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="54375939" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-plane" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M480 192H365.71L260.61 8.06A16.014 16.014 0 0 0 246.71 0h-65.5c-10.63 0-18.3 10.17-15.38 20.39L214.86 192H112l-43.2-57.6c-3.02-4.03-7.77-6.4-12.8-6.4H16.01C5.6 128-2.04 137.78.49 147.88L32 256 .49 364.12C-2.04 374.22 5.6 384 16.01 384H56c5.04 0 9.78-2.37 12.8-6.4L112 320h102.86l-49.03 171.6c-2.92 10.22 4.75 20.4 15.38 20.4h65.5c5.74 0 11.04-3.08 13.89-8.06L365.71 320H480c35.35 0 96-28.65 96-64s-60.65-64-96-64z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Flight Information						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-4b54be88 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="4b54be88" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-far-map" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M0 117.66v346.32c0 11.32 11.43 19.06 21.94 14.86L160 416V32L20.12 87.95A32.006 32.006 0 0 0 0 117.66zM192 416l192 64V96L192 32v384zM554.06 33.16L416 96v384l139.88-55.95A31.996 31.996 0 0 0 576 394.34V48.02c0-11.32-11.43-19.06-21.94-14.86z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							City Guide						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-497458e5 e-flex e-con-boxed e-con e-parent" data-id="497458e5" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-2abc8578 e-con-full e-flex elementor-invisible e-con e-child" data-id="2abc8578" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
				<div class="elementor-element elementor-element-2d04b76a elementor-widget elementor-widget-heading" data-id="2d04b76a" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Non-Stop Entertainment</h6>				</div>
				<div class="elementor-element elementor-element-31fe179c elementor-widget elementor-widget-heading" data-id="31fe179c" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Live TV &amp; Entertainment</h2>				</div>
				<div class="elementor-element elementor-element-24d5f5c2 elementor-widget elementor-widget-text-editor" data-id="24d5f5c2" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Deliver seamless live TV, movies and guest entertainment with an intuitive interface.</p>								</div>
		<div class="elementor-element elementor-element-73ffffcf e-con-full e-flex e-con e-child" data-id="73ffffcf" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-7c1a435 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="7c1a435" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-tv" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M592 0H48A48 48 0 0 0 0 48v320a48 48 0 0 0 48 48h240v32H112a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16H352v-32h240a48 48 0 0 0 48-48V48a48 48 0 0 0-48-48zm-16 352H64V64h512z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Live TV Channels						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-51ad2658 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="51ad2658" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-film" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M488 64h-8v20c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12V64H96v20c0 6.6-5.4 12-12 12H44c-6.6 0-12-5.4-12-12V64h-8C10.7 64 0 74.7 0 88v336c0 13.3 10.7 24 24 24h8v-20c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v20h320v-20c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v20h8c13.3 0 24-10.7 24-24V88c0-13.3-10.7-24-24-24zM96 372c0 6.6-5.4 12-12 12H44c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-96c0 6.6-5.4 12-12 12H44c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-96c0 6.6-5.4 12-12 12H44c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm272 208c0 6.6-5.4 12-12 12H156c-6.6 0-12-5.4-12-12v-96c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v96zm0-168c0 6.6-5.4 12-12 12H156c-6.6 0-12-5.4-12-12v-96c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v96zm112 152c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-96c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-96c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							On-Demand Movies						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-dd1527f elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="dd1527f" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-globe" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Web Applications						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-1fa89d7a elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="1fa89d7a" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fab-chromecast" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M447.8,64H64c-23.6,0-42.7,19.1-42.7,42.7v63.9H64v-63.9h383.8v298.6H298.6V448H448c23.6,0,42.7-19.1,42.7-42.7V106.7 C490.7,83.1,471.4,64,447.8,64z M21.3,383.6L21.3,383.6l0,63.9h63.9C85.2,412.2,56.6,383.6,21.3,383.6L21.3,383.6z M21.3,298.6V341 c58.9,0,106.6,48.1,106.6,107h42.7C170.7,365.6,103.7,298.7,21.3,298.6z M213.4,448h42.7c-0.5-129.5-105.3-234.3-234.8-234.6l0,42.4 C127.3,255.6,213.3,342,213.4,448z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Screen Cast						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				<div class="elementor-element elementor-element-2225a305 elementor-view-stacked elementor-position-block-start elementor-widget__width-initial elementor-shape-circle elementor-mobile-position-block-start elementor-widget elementor-widget-icon-box" data-id="2225a305" data-element_type="widget" data-e-type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span  class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-heart" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span  >
							Personalised Recommendations						</span>
					</h3>
				
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-3ae8e4cd e-con-full e-flex elementor-invisible e-con e-child" data-id="3ae8e4cd" data-element_type="container" data-e-type="container" data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
				<div class="elementor-element elementor-element-6bba1781 elementor-widget elementor-widget-image" data-id="6bba1781" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1448" height="1086" src="{{ asset('landing-wp/images/Cinematic-Resort-Suite-at-Sunset.png') }}" class="attachment-full size-full wp-image-160" alt="" srcset="{{ asset('landing-wp/images/Cinematic-Resort-Suite-at-Sunset.png') }} 1448w, {{ asset('landing-wp/images/Cinematic-Resort-Suite-at-Sunset-300x225.png') }} 300w, {{ asset('landing-wp/images/Cinematic-Resort-Suite-at-Sunset-1024x768.png') }} 1024w, {{ asset('landing-wp/images/Cinematic-Resort-Suite-at-Sunset-768x576.png') }} 768w" sizes="(max-width: 1448px) 100vw, 1448px" />															</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-3ad16d8d e-flex e-con-boxed e-con e-parent" data-id="3ad16d8d" data-element_type="container" data-e-type="container" id="gallery" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-63b553ac elementor-widget elementor-widget-heading" data-id="63b553ac" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Real Implementations</h6>				</div>
				<div class="elementor-element elementor-element-2b6e52a0 elementor-widget elementor-widget-heading" data-id="2b6e52a0" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Gallery</h2>				</div>
				<div class="elementor-element elementor-element-5d197102 elementor-widget elementor-widget-text-editor" data-id="5d197102" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>See how our Hotel TV solution enhances guest experiences in premium properties worldwide.</p>								</div>
		<div class="elementor-element elementor-element-544e94d0 e-con-full e-flex e-con e-child" data-id="544e94d0" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-75671628 e-con-full e-flex e-con e-child" data-id="75671628" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-a3c8110 elementor-widget elementor-widget-image" data-id="a3c8110" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="940" height="790" src="{{ asset('landing-wp/images/Luxury-Suite-at-Tropical-Sunset-e1791468262686.png') }}" class="attachment-full size-full wp-image-164" alt="" srcset="{{ asset('landing-wp/images/Luxury-Suite-at-Tropical-Sunset-e1791468262686.png') }} 940w, {{ asset('landing-wp/images/Luxury-Suite-at-Tropical-Sunset-e1791468262686-300x252.png') }} 300w, {{ asset('landing-wp/images/Luxury-Suite-at-Tropical-Sunset-e1791468262686-768x645.png') }} 768w" sizes="(max-width: 940px) 100vw, 940px" />															</div>
				</div>
		<div class="elementor-element elementor-element-73fca1aa e-con-full e-flex e-con e-child" data-id="73fca1aa" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-2099b887 e-con-full e-flex e-con e-child" data-id="2099b887" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-358f5098 e-con-full e-flex e-con e-child" data-id="358f5098" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-3c2ecef8 elementor-widget elementor-widget-image" data-id="3c2ecef8" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1672" height="941" src="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4.png') }}" class="attachment-full size-full wp-image-153" alt="" srcset="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4.png') }} 1672w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4-300x169.png') }} 300w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4-1024x576.png') }} 1024w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4-768x432.png') }} 768w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4-1536x864.png') }} 1536w" sizes="(max-width: 1672px) 100vw, 1672px" />															</div>
				</div>
		<div class="elementor-element elementor-element-39ea9f04 e-con-full e-flex e-con e-child" data-id="39ea9f04" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-16f8d775 elementor-widget elementor-widget-image" data-id="16f8d775" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1672" height="941" src="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2.png') }}" class="attachment-full size-full wp-image-155" alt="" srcset="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2.png') }} 1672w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-300x169.png') }} 300w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-1024x576.png') }} 1024w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-768x432.png') }} 768w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-1536x864.png') }} 1536w" sizes="(max-width: 1672px) 100vw, 1672px" />															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-48e30edf e-con-full e-flex e-con e-child" data-id="48e30edf" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-369aee71 e-con-full e-flex e-con e-child" data-id="369aee71" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-4436e7e6 elementor-widget elementor-widget-image" data-id="4436e7e6" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1672" height="941" src="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3.png') }}" class="attachment-full size-full wp-image-154" alt="" srcset="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3.png') }} 1672w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-300x169.png') }} 300w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-1024x576.png') }} 1024w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-768x432.png') }} 768w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-1536x864.png') }} 1536w" sizes="(max-width: 1672px) 100vw, 1672px" />															</div>
				</div>
		<div class="elementor-element elementor-element-61e2dcec e-con-full e-flex e-con e-child" data-id="61e2dcec" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-5dac4c2e elementor-widget elementor-widget-image" data-id="5dac4c2e" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
															<img loading="lazy" decoding="async" width="1672" height="941" src="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1.png') }}" class="attachment-full size-full wp-image-156" alt="" srcset="{{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1.png') }} 1672w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-300x169.png') }} 300w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-1024x576.png') }} 1024w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-768x432.png') }} 768w, {{ asset('landing-wp/images/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-1536x864.png') }} 1536w" sizes="(max-width: 1672px) 100vw, 1672px" />															</div>
				</div>
				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-5ffc26db e-flex e-con-boxed e-con e-parent" data-id="5ffc26db" data-element_type="container" data-e-type="container" id="contactus" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-4f21b8bf elementor-widget elementor-widget-heading" data-id="4f21b8bf" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Ready To Transform Your Guest Experience?</h6>				</div>
				<div class="elementor-element elementor-element-54fb4dc3 elementor-widget elementor-widget-heading" data-id="54fb4dc3" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Request a Demo Today</h2>				</div>
				<div class="elementor-element elementor-element-449f1fad elementor-widget elementor-widget-text-editor" data-id="449f1fad" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Discover how our Hotel TV solution can add value to your property and delight your guests.</p>								</div>
		<div class="elementor-element elementor-element-696ceae6 e-con-full e-flex e-con e-child" data-id="696ceae6" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-412a88ae elementor-widget elementor-widget-button" data-id="412a88ae" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjI1MCIsInRvZ2dsZSI6ZmFsc2V9">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Request Demo</span>
					</span>
					</a>
								</div>
				<div class="elementor-element elementor-element-3a6c0960 elementor-widget elementor-widget-button" data-id="3a6c0960" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjI1MCIsInRvZ2dsZSI6ZmFsc2V9">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Contact Us</span>
					</span>
					</a>
								</div>
				</div>
					</div>
				</div>
				</div>
		
	
	
</div><!-- .entry-content .clear -->

	
	
</article><!-- #post-## -->

			</main><!-- #main -->
			
		
	</div><!-- #primary -->


	</div> <!-- ast-container -->
	</div><!-- #content -->
		<footer data-elementor-type="footer" data-elementor-id="33" class="elementor elementor-33 elementor-location-footer" data-elementor-post-type="elementor_library">
			<div class="elementor-element elementor-element-4dbbe676 e-flex e-con-boxed e-con e-parent" data-id="4dbbe676" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-78cde3fe e-con-full e-flex e-con e-child" data-id="78cde3fe" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-77f2d90f elementor-widget elementor-widget-heading" data-id="77f2d90f" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h3 class="elementor-heading-title elementor-size-default">TAJ</h3>				</div>
				<div class="elementor-element elementor-element-cad4f36 elementor-widget elementor-widget-text-editor" data-id="cad4f36" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>Premium Hotel TV Solution for a Smarter Stay Experience.</p>								</div>
				<div class="elementor-element elementor-element-39bc63a1 elementor-shape-rounded elementor-grid-0 e-grid-align-center elementor-widget elementor-widget-social-icons" data-id="39bc63a1" data-element_type="widget" data-e-type="widget" data-widget_type="social-icons.default">
							<div class="elementor-social-icons-wrapper elementor-grid" role="list">
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-linkedin-in elementor-repeater-item-xg53664" href="#" target="_blank">
						<span class="elementor-screen-only">Linkedin-in</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-linkedin-in" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-youtube elementor-repeater-item-hkz7kkf" href="#" target="_blank">
						<span class="elementor-screen-only">Youtube</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-youtube" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-1659y1g" href="#" target="_blank">
						<span class="elementor-screen-only">Instagram</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-instagram" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg>					</a>
				</span>
					</div>
						</div>
				</div>
		<div class="elementor-element elementor-element-6393a6a9 e-con-full e-flex e-con e-child" data-id="6393a6a9" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-6aee7119 elementor-widget elementor-widget-heading" data-id="6aee7119" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h5 class="elementor-heading-title elementor-size-default">Quick Links</h5>				</div>
				<div class="elementor-element elementor-element-5ac6a57a elementor-widget elementor-widget-text-editor" data-id="5ac6a57a" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p style="margin: 0 0 10px;"><a style="color: #b8bfcc; text-decoration: none;" href="/">Home</a></p><p style="margin: 0 0 10px;"><a style="color: #b8bfcc; text-decoration: none;" href="#solutions">Solutions</a></p><p style="margin: 0 0 10px;"><a style="color: #b8bfcc; text-decoration: none;" href="#gallery">Gallery</a></p><p style="margin: 0 0 10px;"><a style="color: #b8bfcc; text-decoration: none;" href="#about">About Us</a></p><p style="margin: 0 0 10px;"><a style="color: #b8bfcc; text-decoration: none;" href="#contactus">Contact</a></p>								</div>
				</div>
		<div class="elementor-element elementor-element-618db605 e-con-full e-flex e-con e-child" data-id="618db605" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-2f8aabe elementor-widget elementor-widget-heading" data-id="2f8aabe" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h5 class="elementor-heading-title elementor-size-default">Our Solutions</h5>				</div>
				<div class="elementor-element elementor-element-230d74c5 elementor-widget elementor-widget-text-editor" data-id="230d74c5" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">Hotel TV Application</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">System Integration</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">Multi-Language Support</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">Hotel Information</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">Live TV &#038; Entertainment</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">Guest Services</a></p>								</div>
				</div>
		<div class="elementor-element elementor-element-17ae753a e-con-full e-flex e-con e-child" data-id="17ae753a" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-72827994 elementor-widget elementor-widget-heading" data-id="72827994" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
					<h5 class="elementor-heading-title elementor-size-default">Contact Us</h5>				</div>
				<div class="elementor-element elementor-element-2cbeb86a elementor-widget elementor-widget-text-editor" data-id="2cbeb86a" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">📍 Mumbai, India</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">📞 +91 98765 43210</a></p><p style="margin:0 0 10px"><a href="#" style="color:#B8BFCC;text-decoration:none">✉ info@tajhoteltv.com</a></p>								</div>
				<div class="elementor-element elementor-element-6b0c35c2 elementor-widget elementor-widget-button" data-id="6b0c35c2" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
										<a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjI1MCIsInRvZ2dsZSI6ZmFsc2V9">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-angle-right" viewBox="0 0 256 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>			</span>
									<span class="elementor-button-text">Request Demo  </span>
					</span>
					</a>
								</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-384db8e2 e-flex e-con-boxed e-con e-parent" data-id="384db8e2" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-6405fd9b elementor-widget elementor-widget-text-editor" data-id="6405fd9b" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p>© 2026 TAJ. All rights reserved.</p>								</div>
				<div class="elementor-element elementor-element-58d57d5c elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-mobile elementor-widget elementor-widget-text-editor" data-id="58d57d5c" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
									<p><a style="color: #8a93a6; text-decoration: none;" href="#">Privacy Policy</a>  |  <a style="color: #8a93a6; text-decoration: none;" href="#">Terms of Service</a></p>								</div>
					</div>
				</div>
				</footer>
			</div><!-- #page -->
<div id="ast-scroll-top" tabindex="0" class="ast-scroll-top-icon ast-scroll-to-top-right" data-on-devices="both">
	<span class="ast-icon icon-arrow"><svg class="ast-arrow-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="26px" height="16.043px" viewbox="57 35.171 26 16.043" enable-background="new 57 35.171 26 16.043" xml:space="preserve">
                <path d="M57.5,38.193l12.5,12.5l12.5-12.5l-2.5-2.5l-10,10l-10-10L57.5,38.193z" />
                </svg></span>	<span class="screen-reader-text">Scroll to Top</span>
</div>
		
<!-- Bulletproof Demo Popup Modal -->
<div id="taj-modal-backdrop">
    <div class="dialog-widget-content">
        <div class="dialog-close-button" role="button" aria-label="Close" tabindex="0">
            <svg viewBox="0 0 1000 1000" width="16" height="16" fill="currentColor"><path d="M742 167L500 408 258 167C246 154 233 150 217 150 196 150 179 158 167 167 154 179 150 196 150 212 150 229 154 242 171 254L408 500 167 742C138 771 138 800 167 829 196 858 225 858 254 829L496 587 738 829C750 842 767 846 783 846 800 846 817 842 829 829 842 817 846 804 846 783 846 767 842 750 829 737L588 500 833 258C863 229 863 200 833 171 804 137 775 137 742 167Z"></path></svg>
        </div>
        <div data-elementor-type="popup" data-elementor-id="250" class="elementor elementor-250 elementor-location-popup" data-elementor-settings="{&quot;entrance_animation&quot;:&quot;zoomIn&quot;,&quot;exit_animation&quot;:&quot;fadeOut&quot;,&quot;entrance_animation_duration&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:0.7,&quot;sizes&quot;:[]},&quot;prevent_scroll&quot;:&quot;no&quot;,&quot;a11y_navigation&quot;:&quot;yes&quot;,&quot;triggers&quot;:{},&quot;timing&quot;:[]}" data-elementor-post-type="elementor_library">
			<div class="elementor-element elementor-element-3445b12c e-con-full e-flex e-con e-parent" data-id="3445b12c" data-element_type="container" data-e-type="container">
		<div class="elementor-element elementor-element-76bf9104 e-con-full animated-slow taj-pop-left e-flex elementor-invisible e-con e-child" data-id="76bf9104" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInLeft&quot;}">
				<div class="elementor-element elementor-element-5a018849 elementor-invisible elementor-widget elementor-widget-heading" data-id="5a018849" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.2}" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Premium Hotel TV Solution</h6>				</div>
				<div class="elementor-element elementor-element-e6f8059 elementor-invisible elementor-widget elementor-widget-heading" data-id="e6f8059" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.35}" data-widget_type="heading.default">
					<h2 class="elementor-heading-title elementor-size-default">Get a Free Demo for Your Hotel</h2>				</div>
				<div class="elementor-element elementor-element-2c27590b elementor-hidden-mobile elementor-widget-divider--view-line elementor-invisible elementor-widget elementor-widget-divider" data-id="2c27590b" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;,&quot;_animation_delay&quot;:0.5}" data-widget_type="divider.default">
							<div class="elementor-divider">
			<span class="elementor-divider-separator">
						</span>
		</div>
						</div>
				<div class="elementor-element elementor-element-57b9926 elementor-hidden-mobile elementor-invisible elementor-widget elementor-widget-text-editor" data-id="57b9926" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.6}" data-widget_type="text-editor.default">
									<p>Transform every guest room into a smart, personalised entertainment experience. Share your details and our team will arrange a live demo.</p>								</div>
				<div class="elementor-element elementor-element-46753b15 elementor-hidden-mobile elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-invisible elementor-widget elementor-widget-icon-list" data-id="46753b15" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.8}" data-widget_type="icon-list.default">
							<ul class="elementor-icon-list-items">
							<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Custom branded hotel TV interface</span>
									</li>
								<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>						</span>
										<span class="elementor-icon-list-text">14+ language support for global guests</span>
									</li>
								<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>						</span>
										<span class="elementor-icon-list-text">PMS &amp; third-party integration</span>
									</li>
								<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Dedicated support &amp; updates</span>
									</li>
						</ul>
						</div>
				</div>
		<div class="elementor-element elementor-element-1f083f97 e-con-full animated-slow e-flex elementor-invisible e-con e-child" data-id="1f083f97" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInRight&quot;}">
				<div class="elementor-element elementor-element-6a4ff7fe elementor-invisible elementor-widget elementor-widget-heading" data-id="6a4ff7fe" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.3}" data-widget_type="heading.default">
					<h6 class="elementor-heading-title elementor-size-default">Request a Demo</h6>				</div>
				<div class="elementor-element elementor-element-748b4980 elementor-invisible elementor-widget elementor-widget-heading" data-id="748b4980" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.4}" data-widget_type="heading.default">
					<h3 class="elementor-heading-title elementor-size-default">Let's Talk About Your Property</h3>				</div>
				<div class="elementor-element elementor-element-4719c92a elementor-invisible elementor-widget elementor-widget-text-editor" data-id="4719c92a" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.5}" data-widget_type="text-editor.default">
									<p>Fill in the form — we reply within 24 hours.</p>								</div>
				<div class="elementor-element elementor-element-6a070195 taj-pop-form elementor-button-align-stretch elementor-invisible elementor-widget elementor-widget-form" data-id="6a070195" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:0.7,&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}" data-widget_type="form.default">
							<form class="elementor-form" method="post" name="Hotel TV Demo Popup" aria-label="Hotel TV Demo Popup">
			<input type="hidden" name="post_id" value="250"/>
			<input type="hidden" name="form_id" value="6a070195"/>
			<input type="hidden" name="referer_title" value="" />

							<input type="hidden" name="queried_id" value="10"/>
			
			<div class="elementor-form-fields-wrapper elementor-labels-above">
								<div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-sm-100 elementor-field-required elementor-mark-required">
												<label for="form-field-name" class="elementor-field-label">
								Full Name							</label>
														<input size="1" type="text" name="form_fields[name]" id="form-field-name" class="elementor-field elementor-size-md  elementor-field-textual" placeholder="Your full name" required="required">
											</div>
								<div class="elementor-field-type-tel elementor-field-group elementor-column elementor-field-group-phone elementor-col-50 elementor-sm-100 elementor-field-required elementor-mark-required">
												<label for="form-field-phone" class="elementor-field-label">
								Phone							</label>
								<input size="1" type="tel" name="form_fields[phone]" id="form-field-phone" class="elementor-field elementor-size-md  elementor-field-textual" placeholder="+91 00000 00000" required="required" pattern="[0-9()#&amp;+*-=.]+" title="Only numbers and phone characters (#, -, *, etc) are accepted.">

						</div>
								<div class="elementor-field-type-email elementor-field-group elementor-column elementor-field-group-email elementor-col-100 elementor-sm-100 elementor-field-required elementor-mark-required">
												<label for="form-field-email" class="elementor-field-label">
								Email							</label>
														<input size="1" type="email" name="form_fields[email]" id="form-field-email" class="elementor-field elementor-size-md  elementor-field-textual" placeholder="you@hotel.com" required="required">
											</div>
								<div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-hotel elementor-col-50 elementor-sm-100 elementor-field-required elementor-mark-required">
												<label for="form-field-hotel" class="elementor-field-label">
								Hotel Name							</label>
														<input size="1" type="text" name="form_fields[hotel]" id="form-field-hotel" class="elementor-field elementor-size-md  elementor-field-textual" placeholder="Name of your hotel" required="required">
											</div>
								<div class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-rooms elementor-col-50 elementor-sm-100 elementor-field-required elementor-mark-required">
												<label for="form-field-rooms" class="elementor-field-label">
								Number of Rooms							</label>
								<div class="elementor-field elementor-select-wrapper remove-before ">
			<div class="select-caret-down-wrapper">
				<svg aria-hidden="true" class="e-font-icon-svg e-eicon-caret-down" viewBox="0 0 571.4 571.4" xmlns="http://www.w3.org/2000/svg"><path d="M571 393Q571 407 561 418L311 668Q300 679 286 679T261 668L11 418Q0 407 0 393T11 368 36 357H536Q550 357 561 368T571 393Z"></path></svg>			</div>
			<select name="form_fields[rooms]" id="form-field-rooms" class="elementor-field-textual elementor-size-md" required="required">
									<option value="1 - 50">1 - 50</option>
									<option value="51 - 100">51 - 100</option>
									<option value="101 - 250">101 - 250</option>
									<option value="250+">250+</option>
							</select>
		</div>
						</div>
								<div class="elementor-field-type-textarea elementor-field-group elementor-column elementor-field-group-message elementor-col-100 elementor-sm-100">
												<label for="form-field-message" class="elementor-field-label">
								Message							</label>
						<textarea class="elementor-field-textual elementor-field  elementor-size-md" name="form_fields[message]" id="form-field-message" rows="3" placeholder="Tell us about your requirement (optional)"></textarea>				</div>
								<div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
					<button class="elementor-button elementor-size-md" type="submit">
						<span class="elementor-button-content-wrapper">
															<span class="elementor-button-icon">
									<i class="Array" aria-hidden="true"></i>																	</span>
																						<span class="elementor-button-text">Request Free Demo</span>
													</span>
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

					<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. SCROLL ENTRANCE ANIMATIONS (IntersectionObserver)
    const animEls = document.querySelectorAll('.elementor-invisible, [data-settings*="animation"]');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    let anim = 'fadeInUp';
                    let delay = 0;
                    const s = el.getAttribute('data-settings');
                    if (s) {
                        try {
                            const parsed = JSON.parse(s);
                            anim = parsed._animation || parsed.animation || anim;
                            delay = (parseFloat(parsed._animation_delay || parsed.animation_delay || 0)) * 1000;
                        } catch(e) {}
                    }
                    setTimeout(() => {
                        el.classList.remove('elementor-invisible');
                        el.classList.add('animated', anim);
                    }, delay);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });

        animEls.forEach(el => obs.observe(el));
    } else {
        animEls.forEach(el => {
            el.classList.remove('elementor-invisible');
            el.classList.add('animated', 'fadeInUp');
        });
    }

    // Scroll-to-Top Button
    const scrollTopBtn = document.getElementById("ast-scroll-top");
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                scrollTopBtn.style.opacity = "1";
                scrollTopBtn.style.visibility = "visible";
            } else {
                scrollTopBtn.style.opacity = "0";
                scrollTopBtn.style.visibility = "hidden";
            }
        }, { passive: true });
    }

    // Mobile Hamburger Menu Toggle
    document.querySelectorAll('.elementor-menu-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('elementor-active');
            const navDropdown = this.parentElement.querySelector('.elementor-nav-menu--dropdown');
            if (navDropdown) {
                const isOpen = navDropdown.style.display === 'block';
                navDropdown.style.display = isOpen ? 'none' : 'block';
                this.setAttribute('aria-expanded', !isOpen);
            }
        });
    });

    // Fallback: Reveal anything still invisible after 2.5s
    setTimeout(() => {
        document.querySelectorAll('.elementor-invisible').forEach(el => {
            el.classList.remove('elementor-invisible');
            el.classList.add('animated', 'fadeInUp');
        });
    }, 2500);

    // 2. MODAL CONTROLLER
    const modal = document.getElementById("taj-modal-backdrop");
    const closeBtn = modal ? modal.querySelector(".dialog-close-button") : null;

    function openModal() {
        if (!modal) return;
        modal.classList.add("is-open");
        document.body.style.overflow = "hidden";
        modal.querySelectorAll('.elementor-invisible').forEach(el => {
            el.classList.remove('elementor-invisible');
            el.classList.add('animated', 'fadeInUp');
        });
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove("is-open");
        document.body.style.overflow = "";
    }

    function bindButtons() {
        document.querySelectorAll('a, button').forEach(el => {
            const txt = (el.textContent || '').trim().toLowerCase();
            const href = el.getAttribute('href') || '';
            if (href.includes('popup:open') || href.includes('250') || txt === 'request demo' || txt === 'request demo now' || txt === 'contact us') {
                if (!el.dataset.tajBound) {
                    el.dataset.tajBound = 'true';
                    el.style.cursor = 'pointer';
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        openModal();
                    });
                }
            }
        });
    }
    bindButtons();
    setTimeout(bindButtons, 500);

    if (closeBtn) {
        closeBtn.addEventListener("click", closeModal);
    }
    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
    }
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal && modal.classList.contains("is-open")) {
            closeModal();
        }
    });

    // 3. AJAX LEAD SUBMISSION
    const form = modal ? modal.querySelector('form.elementor-form') : null;
    if (form) {
        let alertBox = form.querySelector('.taj-form-alert');
        if (!alertBox) {
            alertBox = document.createElement('div');
            alertBox.className = 'taj-form-alert';
            form.insertBefore(alertBox, form.firstChild);
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Submitting...</span>';
            }

            const formData = new FormData(form);
            const data = {
                name: formData.get('form_fields[name]') || '',
                phone: formData.get('form_fields[phone]') || '',
                email: formData.get('form_fields[email]') || '',
                hotel_name: formData.get('form_fields[hotel]') || '',
                subject: 'Hotel Demo Request (' + (formData.get('form_fields[rooms]') || 'General') + ')',
                message: formData.get('form_fields[message]') || 'Demo requested from landing page popup.'
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch("{{ route('contact.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                alertBox.style.display = 'block';
                if (res.ok) {
                    alertBox.className = 'taj-form-alert taj-form-alert-success';
                    alertBox.textContent = 'Thank you! Your demo request has been received. Our team will contact you within 24 hours.';
                    form.reset();
                    setTimeout(() => {
                        closeModal();
                        alertBox.style.display = 'none';
                    }, 2500);
                } else {
                    alertBox.className = 'taj-form-alert taj-form-alert-error';
                    alertBox.textContent = 'Please fill all required fields properly and try again.';
                }
            } catch (err) {
                alertBox.style.display = 'block';
                alertBox.className = 'taj-form-alert taj-form-alert-success';
                alertBox.textContent = 'Thank you! Your request has been noted.';
                setTimeout(closeModal, 2000);
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
        });
    }
});
</script>

</body>
</html>


