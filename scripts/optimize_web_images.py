"""
Pax TV Launcher - Website Image Optimizer Script
Converts raw PNG/JPG images in public/uploads/ into optimized WebP formats with crisp 82% quality.
Run: python scripts/optimize_web_images.py
"""
import os
from PIL import Image

def optimize_images():
    base_dir = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'public', 'uploads', '2026', '11')
    if not os.path.exists(base_dir):
        print(f"Directory not found: {base_dir}")
        return

    images = [
        'mobile-hero-banner.png',
        'Luxury-Hotel-Room-with-PAXTV-Interface.png',
        'About-us.png',
        'our-solution.png',
        'Sunset-Resort-Language-Selection-UI-1-1-1.png',
        'hotel-information-service.png',
        'Live-TV-ent.png',
        'Twilight-Luxury-Resort-by-the-Sea-1-1-1.png',
        'PAX-TV-LAUNCHER-2-2.png'
    ]

    total_orig = 0
    total_webp = 0

    print("=" * 60)
    print("PAX TV WEBSITE IMAGE OPTIMIZER")
    print("=" * 60)

    for img_name in images:
        src_path = os.path.join(base_dir, img_name)
        if not os.path.exists(src_path):
            print(f"Skipping {img_name} (not found)")
            continue

        webp_name = os.path.splitext(img_name)[0] + '.webp'
        dst_path = os.path.join(base_dir, webp_name)

        orig_sz = os.path.getsize(src_path)
        total_orig += orig_sz

        with Image.open(src_path) as im:
            # Save as high quality WebP
            im.save(dst_path, 'WEBP', quality=82, method=6)

        webp_sz = os.path.getsize(dst_path)
        total_webp += webp_sz
        reduction = ((orig_sz - webp_sz) / orig_sz) * 100
        print(f"{img_name:45} | {orig_sz/1024:6.0f} KB -> {webp_sz/1024:4.0f} KB (-{reduction:4.1f}%)")

    print("=" * 60)
    print(f"Original Total: {total_orig / 1024 / 1024:6.2f} MB")
    print(f"WebP Total:     {total_webp / 1024 / 1024:6.2f} MB")
    print(f"Net Saved:      {((total_orig - total_webp) / total_orig) * 100:6.1f}% bandwidth reduction")
    print("=" * 60)

if __name__ == '__main__':
    optimize_images()
