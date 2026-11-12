<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Events\TvConfigUpdatedEvent;
use App\Services\TvVersionCacheService;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates (newest builds first).
     */
    public function index(Request $request)
    {
        $query = TvTemplate::query();

        if ($request->filled('theme_id')) {
            $query->where('theme_id', $request->theme_id);
        }

        // Always show the newest/latest uploaded builds first so Theme 2 or any update isn't buried
        $templates = $query->orderBy('id', 'desc')->paginate(15);

        // Fetch distinct registered themes
        $existingThemes = TvTemplate::select('theme_id', 'theme_name', 'preview_image')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('theme_id')
            ->sortBy('theme_id')
            ->values();

        // Calculate the next suggested theme ID
        $maxThemeId = TvTemplate::max('theme_id');
        $nextThemeId = $maxThemeId ? ((int) $maxThemeId + 1) : 1;
        $defaultThemeId = (int) app_setting('default_theme_id', 1);

        // High-level statistics for Super Admin dashboard cards
        $totalThemesCount = $existingThemes->count();
        $activeBuildsCount = TvTemplate::where('is_active', true)->count();
        $totalBuildsCount = TvTemplate::count();
        $inactiveBuildsCount = TvTemplate::where('is_active', false)->count();

        $defaultThemeObj = $existingThemes->firstWhere('theme_id', $defaultThemeId);
        $defaultThemeName = $defaultThemeObj ? ($defaultThemeObj->theme_name ?? 'Theme #' . $defaultThemeId) : ('Theme #' . $defaultThemeId);

        return view('super_admin.templates.index', compact(
            'templates', 
            'existingThemes', 
            'nextThemeId', 
            'defaultThemeId',
            'totalThemesCount',
            'activeBuildsCount',
            'totalBuildsCount',
            'inactiveBuildsCount',
            'defaultThemeName'
        ));
    }

    /**
     * Store a newly uploaded zip template for a specific Theme ID.
     * After storing, the zip is auto-extracted to public/themes/ so it
     * can be accessed directly via URL (no login required).
     */
    public function store(Request $request)
    {
        // Increase limits for large file processing on shared hosting
        @set_time_limit(300);
        @ini_set('memory_limit', '256M');

        $request->validate([
            'theme_id'       => 'required|integer|min:1',
            'theme_name'     => 'nullable|string|max:100',
            'custom_version' => 'nullable|string|max:20',
            'preview_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'template_file'  => 'required|file|mimes:zip|max:51200', // 50MB
        ]);

        $targetThemeId = (int) $request->input('theme_id');

        // Look for the latest build for this specific theme
        $latestForTheme = TvTemplate::where('theme_id', $targetThemeId)
            ->orderBy('id', 'desc')
            ->first();

        // Determine Version number
        if ($request->filled('custom_version')) {
            $nextVersion = trim($request->input('custom_version'));
        } elseif ($latestForTheme) {
            $nextVersion = number_format(floatval($latestForTheme->version) + 0.5, 1);
        } else {
            $nextVersion = '1.0';
        }

        // Determine Theme Name
        if ($request->filled('theme_name')) {
            $themeName = trim($request->input('theme_name'));
        } elseif ($latestForTheme && !empty($latestForTheme->theme_name)) {
            $themeName = $latestForTheme->theme_name;
        } else {
            $themeName = 'Theme ' . $targetThemeId;
        }

        // Handle Preview Image
        $previewImagePath = null;
        if ($request->hasFile('preview_image')) {
            $previewFile     = $request->file('preview_image');
            $previewFileName = 'theme_' . $targetThemeId . '_preview_' . time() . '.' . $previewFile->getClientOriginalExtension();
            $previewImagePath = Storage::disk('public')->putFileAs('templates/previews', $previewFile, $previewFileName);
        } elseif ($latestForTheme && !empty($latestForTheme->preview_image)) {
            // Carry forward existing preview image if no new one was provided
            $previewImagePath = $latestForTheme->preview_image;
        }

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');

            // ── 1. Store the original zip ──────────────────────────────────────
            $fileName = 'theme_' . $targetThemeId . '_v' . str_replace('.', '_', $nextVersion) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('templates', $file, $fileName);

            // ── 2. Extract zip into public/themes/theme_{id}/v{safe_version}/ ─
            $safeVersion   = str_replace('.', '_', $nextVersion);
            $extractFolder = 'themes/theme_' . $targetThemeId . '/v' . $safeVersion . '_' . time();
            $extractFullPath = public_path($extractFolder);

            $extractedPath = null;
            if (class_exists('ZipArchive')) {
                $zip = new \ZipArchive();
                $zipFullPath = Storage::disk('public')->path($filePath);

                if ($zip->open($zipFullPath) === true) {
                    try {
                        // Remove old extracted folder for this theme (cleanup)
                        if ($latestForTheme && !empty($latestForTheme->extracted_path)) {
                            $oldExtractedFullPath = public_path($latestForTheme->extracted_path);
                            if (is_dir($oldExtractedFullPath)) {
                                $this->deleteDirectory($oldExtractedFullPath);
                            }
                        }

                        $this->extractSafeThemeZip($zip, $extractFullPath);
                        $extractedPath = $extractFolder;
                    } catch (\Throwable $e) {
                        $zip->close();
                        Storage::disk('public')->delete($filePath);
                        \Illuminate\Support\Facades\Log::warning('Rejected unsafe theme ZIP upload', [
                            'error' => $e->getMessage(),
                            'admin_id' => auth('super_admin')->id(),
                        ]);
                        return back()->with('error', 'Theme archive rejected: ' . $e->getMessage());
                    }
                    $zip->close();
                }
            }

            // ── 3. Deactivate previous active builds for this theme_id ─────────
            TvTemplate::where('theme_id', $targetThemeId)->update(['is_active' => false]);

            // Clear TV version caches
            TvVersionCacheService::clearAllHotelsCache();

            // ── 4. Save new template build ─────────────────────────────────────
            $template = TvTemplate::create([
                'theme_id'       => $targetThemeId,
                'theme_name'     => $themeName,
                'version'        => $nextVersion,
                'file_path'      => $filePath,
                'extracted_path' => $extractedPath,
                'preview_image'  => $previewImagePath,
                'is_active'      => true,
            ]);

            // Dispatch event for real-time TV FCM & Firestore sync
            event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $targetThemeId, 'version' => $nextVersion]));

            $previewUrl   = $extractedPath ? url($extractedPath . '/index.html') : null;
            $successMsg   = "Theme {$targetThemeId} ({$themeName}) v{$nextVersion} uploaded and deployed successfully!";
            if ($previewUrl) {
                $successMsg .= " Preview: {$previewUrl}";
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => $successMsg,
                    'preview_url' => $previewUrl,
                    'data'        => $template,
                ]);
            }

            return back()->with('success', $successMsg);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload template file.'
            ], 400);
        }

        return back()->with('error', 'Failed to upload template file.');
    }

    /**
     * Recursively delete a directory and its contents.
     */
    private function extractSafeThemeZip(\ZipArchive $zip, string $extractFullPath): void
    {
        $themesBase = public_path('themes');
        if (!is_dir($themesBase)) {
            @mkdir($themesBase, 0755, true);
        }
        $themesRoot = realpath($themesBase);
        if ($themesRoot === false) {
            throw new \RuntimeException('Themes directory could not be resolved.');
        }

        if (!is_dir($extractFullPath)) {
            @mkdir($extractFullPath, 0755, true);
        }
        $extractRoot = realpath($extractFullPath);
        $themesPrefix = rtrim($themesRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if ($extractRoot === false || !str_starts_with($extractRoot, $themesPrefix)) {
            throw new \RuntimeException('Invalid theme extraction destination.');
        }

        $maxFiles = 2000;
        $maxTotalBytes = 100 * 1024 * 1024; // 100 MB decompressed limit
        $allowedExtensions = [
            'html', 'htm', 'css', 'js', 'mjs', 'json', 'map', 'txt', 'md',
            'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico',
            'woff', 'woff2', 'ttf', 'otf', 'eot',
            'mp4', 'webm', 'mp3', 'wav', 'ogg', 'xml', 'lottie', 'wasm',
        ];
        $blockedBasenames = ['.htaccess', '.htpasswd', '.user.ini', '.env', 'web.config'];

        if ($zip->numFiles > $maxFiles) {
            throw new \RuntimeException('Theme archive contains too many entries.');
        }

        $totalBytes = 0;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if ($stat === false || !isset($stat['name'])) {
                throw new \RuntimeException('Corrupted ZIP entry encountered.');
            }

            $rawName = $stat['name'];
            if (str_contains($rawName, "\0")) {
                throw new \RuntimeException('Invalid null byte in archive path.');
            }

            $normalized = str_replace('\\', '/', $rawName);
            if (str_starts_with($normalized, '/') || preg_match('/^[A-Za-z]:/', $normalized)) {
                throw new \RuntimeException('Absolute path in archive is not allowed.');
            }

            $isDir = str_ends_with($normalized, '/');
            $trimmed = trim($normalized, '/');
            if ($trimmed === '') {
                continue;
            }

            $segments = explode('/', $trimmed);
            foreach ($segments as $segment) {
                if ($segment === '' || $segment === '.' || $segment === '..') {
                    throw new \RuntimeException('Path traversal segment detected in archive.');
                }
            }

            // Skip macOS resource fork noise safely
            if ($segments[0] === '__MACOSX') {
                continue;
            }

            // Reject Unix symlinks inside ZIP archives
            $opsys = 0;
            $externalAttr = 0;
            if ($zip->getExternalAttributesIndex($i, $opsys, $externalAttr)) {
                if ($opsys === \ZipArchive::OPSYS_UNIX && ((($externalAttr >> 16) & 0xF000) === 0xA000)) {
                    throw new \RuntimeException('Symbolic links inside theme archives are not allowed.');
                }
            }

            $targetRelative = implode(DIRECTORY_SEPARATOR, $segments);
            $targetPath = $extractRoot . DIRECTORY_SEPARATOR . $targetRelative;

            if ($isDir) {
                if (!is_dir($targetPath)) {
                    @mkdir($targetPath, 0755, true);
                }
                continue;
            }

            $basename = strtolower(end($segments));
            if (in_array($basename, $blockedBasenames, true)) {
                throw new \RuntimeException("Disallowed configuration file in archive: {$basename}");
            }

            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            if ($ext === '' || !in_array($ext, $allowedExtensions, true)) {
                throw new \RuntimeException("Disallowed file extension in theme archive: .{$ext}");
            }

            $parentDir = dirname($targetPath);
            if (!is_dir($parentDir)) {
                @mkdir($parentDir, 0755, true);
            }
            $parentReal = realpath($parentDir);
            $extractPrefix = rtrim($extractRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($parentReal === false || ($parentReal !== $extractRoot && !str_starts_with($parentReal, $extractPrefix))) {
                throw new \RuntimeException('Resolved archive path escapes theme directory.');
            }

            $stream = $zip->getStream($rawName);
            if ($stream === false) {
                throw new \RuntimeException("Unable to read archive entry: {$trimmed}");
            }

            $out = fopen($targetPath, 'wb');
            if ($out === false) {
                fclose($stream);
                throw new \RuntimeException("Unable to write archive entry: {$trimmed}");
            }

            try {
                while (!feof($stream)) {
                    $chunk = fread($stream, 8192);
                    if ($chunk === false) {
                        throw new \RuntimeException("Failed reading archive stream: {$trimmed}");
                    }
                    $totalBytes += strlen($chunk);
                    if ($totalBytes > $maxTotalBytes) {
                        throw new \RuntimeException('Theme archive exceeds maximum uncompressed size limit.');
                    }
                    fwrite($out, $chunk);
                }
            } finally {
                fclose($out);
                fclose($stream);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        $themesRoot = realpath(public_path('themes'));
        $targetReal = realpath($dir);
        if ($themesRoot === false || $targetReal === false) {
            return;
        }

        $themesPrefix = rtrim($themesRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!str_starts_with($targetReal, $themesPrefix) || is_link($dir)) {
            \Illuminate\Support\Facades\Log::warning('Blocked unsafe deleteDirectory outside themes directory', ['dir' => $dir]);
            return;
        }

        $items = array_diff(scandir($targetReal), ['.', '..']);
        foreach ($items as $item) {
            $path = $targetReal . DIRECTORY_SEPARATOR . $item;
            if (is_link($path)) {
                @unlink($path);
            } elseif (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($targetReal);
    }

    /**
     * Toggle the active status of a specific template build within its theme.
     */
    public function toggleActive(int $id)
    {
        $template = TvTemplate::findOrFail($id);
        
        if (!$template->is_active) {
            // Deactivate all others for this specific theme_id only
            TvTemplate::where('theme_id', $template->theme_id)->update(['is_active' => false]);
            $template->is_active = true;
            $template->save();
        } else {
            $template->is_active = false;
            $template->save();
        }

        TvVersionCacheService::clearAllHotelsCache();
        event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $template->theme_id]));

        return back()->with('success', "Theme #{$template->theme_id} active status updated successfully!");
    }

    /**
     * Redirect to the live preview URL of an extracted theme.
     * Route: GET /super-admin/templates/{id}/preview
     */
    public function preview(int $id)
    {
        $template = TvTemplate::findOrFail($id);

        if (empty($template->extracted_path)) {
            return back()->with('error', 'This template has no extracted preview. Please re-upload the zip to generate a preview.');
        }

        $indexFile = public_path($template->extracted_path . '/index.html');

        if (!file_exists($indexFile)) {
            // Try index.htm fallback
            $indexFile = public_path($template->extracted_path . '/index.htm');
        }

        if (!file_exists($indexFile)) {
            return back()->with('error', 'index.html not found inside the extracted theme folder. Make sure your zip contains an index.html at the root level.');
        }

        // Redirect to the public URL of the extracted theme
        $previewUrl = url($template->extracted_path . '/index.html');

        return redirect($previewUrl);
    }

    /**
     * Delete an individual template build archive and free disk storage.
     * Route: DELETE /super-admin/templates/{id}
     */
    public function destroy(int $id)
    {
        $template = TvTemplate::findOrFail($id);
        $themeId  = (int) $template->theme_id;
        $version  = $template->version;
        $isActive = (bool) $template->is_active;

        // 1. Delete zip file from storage
        if (!empty($template->file_path) && Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }

        // 2. Delete extracted web directory
        if (!empty($template->extracted_path)) {
            $fullExtractPath = public_path($template->extracted_path);
            if (is_dir($fullExtractPath)) {
                $this->deleteDirectory($fullExtractPath);
            }
        }

        // 3. Delete preview image if not used by any other template
        if (!empty($template->preview_image)) {
            $otherUses = TvTemplate::where('preview_image', $template->preview_image)
                ->where('id', '!=', $template->id)
                ->exists();
            if (!$otherUses && Storage::disk('public')->exists($template->preview_image)) {
                Storage::disk('public')->delete($template->preview_image);
            }
        }

        // 4. Delete the database record
        $template->delete();

        // 5. If this build was the active one, auto-activate the newest remaining build for this theme if available
        if ($isActive) {
            $newestRemaining = TvTemplate::where('theme_id', $themeId)->orderBy('id', 'desc')->first();
            if ($newestRemaining) {
                $newestRemaining->is_active = true;
                $newestRemaining->save();
            }
        }

        TvVersionCacheService::clearAllHotelsCache();
        event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $themeId]));

        return back()->with('success', "Build v{$version} for Theme #{$themeId} deleted and server disk storage freed successfully!");
    }

    /**
     * Delete an entire theme and all its build archives from disk.
     * Route: DELETE /super-admin/templates/theme/{theme_id}
     */
    public function destroyTheme(int $themeId)
    {
        $defaultThemeId = (int) app_setting('default_theme_id', 1);
        if ($themeId === $defaultThemeId) {
            return back()->with('error', "Cannot delete Theme #{$themeId} because it is currently set as the Global Default Theme. Please set another theme as default first.");
        }

        $hotelsUsingTheme = \App\Models\HotelAdmin::where('selected_theme_id', $themeId)->count();
        if ($hotelsUsingTheme > 0) {
            return back()->with('error', "Cannot delete Theme #{$themeId} because {$hotelsUsingTheme} hotel suite(s) are currently assigned to it. Please reassign them first.");
        }

        $templates = TvTemplate::where('theme_id', $themeId)->get();
        if ($templates->isEmpty()) {
            return back()->with('error', "No build templates found for Theme #{$themeId}.");
        }

        $deletedCount = 0;
        foreach ($templates as $tmpl) {
            if (!empty($tmpl->file_path) && Storage::disk('public')->exists($tmpl->file_path)) {
                Storage::disk('public')->delete($tmpl->file_path);
            }
            if (!empty($tmpl->extracted_path)) {
                $fullExtract = public_path($tmpl->extracted_path);
                if (is_dir($fullExtract)) {
                    $this->deleteDirectory($fullExtract);
                }
            }
            if (!empty($tmpl->preview_image)) {
                $otherUses = TvTemplate::where('preview_image', $tmpl->preview_image)
                    ->where('theme_id', '!=', $themeId)
                    ->exists();
                if (!$otherUses && Storage::disk('public')->exists($tmpl->preview_image)) {
                    Storage::disk('public')->delete($tmpl->preview_image);
                }
            }
            $tmpl->delete();
            $deletedCount++;
        }

        // Clean up entire parent folder in public/themes/theme_{themeId}
        $themeParentDir = public_path('themes/theme_' . $themeId);
        if (is_dir($themeParentDir)) {
            $this->deleteDirectory($themeParentDir);
        }

        TvVersionCacheService::clearAllHotelsCache();
        event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $themeId]));

        return back()->with('success', "Theme #{$themeId} ({$deletedCount} builds) permanently deleted and server storage freed successfully!");
    }

    /**
     * Purge all old inactive builds to immediately reclaim server disk storage.
     * Route: POST /super-admin/templates/purge-inactive
     */
    public function purgeInactiveBuilds(Request $request)
    {
        $query = TvTemplate::where('is_active', false);
        if ($request->filled('theme_id')) {
            $query->where('theme_id', (int) $request->theme_id);
        }

        $inactiveTemplates = $query->get();

        if ($inactiveTemplates->isEmpty()) {
            return back()->with('info', 'No old inactive builds found to clean.');
        }

        $freedCount = 0;
        foreach ($inactiveTemplates as $tmpl) {
            if (!empty($tmpl->file_path) && Storage::disk('public')->exists($tmpl->file_path)) {
                Storage::disk('public')->delete($tmpl->file_path);
            }
            if (!empty($tmpl->extracted_path)) {
                $fullExtract = public_path($tmpl->extracted_path);
                if (is_dir($fullExtract)) {
                    $this->deleteDirectory($fullExtract);
                }
            }
            $tmpl->delete();
            $freedCount++;
        }

        TvVersionCacheService::clearAllHotelsCache();

        return back()->with('success', "Successfully purged {$freedCount} old inactive build(s)! Server disk storage has been reclaimed.");
    }

    /**
     * Set a theme as the global default for all new hotels.
     * Route: POST /super-admin/templates/{theme_id}/set-default
     */
    public function setDefaultTheme(int $themeId)
    {
        $themeExists = TvTemplate::where('theme_id', $themeId)->exists();
        if (!$themeExists) {
            return back()->with('error', "Theme #{$themeId} not found.");
        }

        $settings = \App\Models\AppSetting::getSettings();
        $settings->default_theme_id = $themeId;
        $settings->save();
        \App\Models\AppSetting::flushSettingsCache();

        return back()->with('success', "Theme #{$themeId} is now set as the Global Default Theme for all new hotels!");
    }
}
