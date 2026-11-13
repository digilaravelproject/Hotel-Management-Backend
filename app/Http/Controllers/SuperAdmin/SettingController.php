<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display the general application and branding settings page.
     */
    public function index()
    {
        $setting = AppSetting::getSettings();
        $activeGateway = \App\Models\PaymentGateway::where('is_enabled', true)->first();
        return view('super_admin.settings.index', compact('setting', 'activeGateway'));
    }

    /**
     * Update application branding, name, logo, and favicon.
     */
    public function update(Request $request)
    {
        $setting = AppSetting::getSettings();

        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'app_short_name' => 'nullable|string|max:50',
            'footer_text' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:100',
            'contact_phone' => 'nullable|string|max:30',
            'app_logo' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'app_favicon' => 'nullable|file|mimes:ico,png,svg|max:1024',
            'turnstile_enabled' => 'nullable|boolean',
            'turnstile_site_key' => 'nullable|string|max:150',
            'turnstile_secret_key' => 'nullable|string|max:255',
        ], [
            'app_name.required' => 'The Application Name is required.',
            'app_logo.mimes' => 'Application Logo must be a valid image format (PNG, JPG, JPEG, SVG, or WEBP).',
            'app_logo.max' => 'Application Logo cannot exceed 2 MB (2048 KB) in file size.',
            'app_favicon.mimes' => 'Application Favicon must be of format: ICO, PNG, or SVG.',
            'app_favicon.max' => 'Application Favicon cannot exceed 1 MB (1024 KB) in file size.',
        ]);

        $data = [
            'app_name' => $validated['app_name'],
            'app_short_name' => $validated['app_short_name'] ?? $validated['app_name'],
            'footer_text' => $validated['footer_text'] ?? ('© ' . date('Y') . ' ' . $validated['app_name'] . '. All rights reserved.'),
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'turnstile_enabled' => $request->boolean('turnstile_enabled'),
            'turnstile_site_key' => $request->filled('turnstile_site_key') ? trim($request->input('turnstile_site_key')) : null,
        ];

        if ($request->filled('turnstile_secret_key')) {
            $data['turnstile_secret_key'] = trim($request->input('turnstile_secret_key'));
        } elseif ($request->boolean('clear_turnstile_secret')) {
            $data['turnstile_secret_key'] = null;
        }

        // Ensure target directory exists in public/uploads/settings
        $destinationDir = public_path('uploads/settings');
        if (!file_exists($destinationDir)) {
            @mkdir($destinationDir, 0755, true);
        }

        // 1. Handle Application Logo Upload
        if ($request->hasFile('app_logo')) {
            $logoFile = $request->file('app_logo');

            if (!empty($setting->app_logo)) {
                ImageHelper::deleteFile($setting->app_logo);
            }

            // Re-encodes raster images to WebP via GD and strictly sanitizes SVG XML via DOM
            $data['app_logo'] = ImageHelper::compressAndConvertToWebp(
                $logoFile,
                'uploads/settings',
                400,
                'app_logo',
                1200
            );
        }

        // 2. Handle Application Favicon Upload
        if ($request->hasFile('app_favicon')) {
            $favFile = $request->file('app_favicon');
            $favExt = strtolower($favFile->getClientOriginalExtension());

            if (!empty($setting->app_favicon)) {
                ImageHelper::deleteFile($setting->app_favicon);
            }

            if ($favExt === 'svg') {
                $svgRaw = @file_get_contents($favFile->getRealPath());
                if ($svgRaw === false || trim($svgRaw) === '') {
                    return back()->withInput()->with('error', 'Uploaded favicon SVG is empty or invalid.');
                }
                $cleanSvg = ImageHelper::sanitizeSvg($svgRaw);
                $favName = 'app_favicon_' . time() . '_' . Str::random(6) . '.svg';
                file_put_contents($destinationDir . DIRECTORY_SEPARATOR . $favName, $cleanSvg);
                $data['app_favicon'] = 'uploads/settings/' . $favName;
            } elseif ($favExt === 'png') {
                $data['app_favicon'] = ImageHelper::compressAndConvertToWebp(
                    $favFile,
                    'uploads/settings',
                    120,
                    'app_favicon',
                    256
                );
            } else {
                $icoBytes = @file_get_contents($favFile->getRealPath());
                if ($icoBytes === false || !str_starts_with($icoBytes, "\x00\x00\x01\x00")) {
                    return back()->withInput()->with('error', 'Uploaded ICO favicon header is invalid.');
                }
                $favName = 'app_favicon_' . time() . '_' . Str::random(6) . '.ico';
                $favFile->move($destinationDir, $favName);
                $data['app_favicon'] = 'uploads/settings/' . $favName;
            }
        }

        $setting->update($data);
        AppSetting::flushSettingsCache();

        return redirect()->route('super-admin.settings.index')
                         ->with('success', 'Application Branding & Settings updated successfully across the entire platform!');
    }
}
