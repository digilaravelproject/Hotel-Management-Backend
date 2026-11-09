<?php
/**
 * ====================================================================
 * Universal Smart Git Deploy & Laravel Web Terminal (100% Zero Hardcoding)
 * ====================================================================
 * Drop this file into ANY Laravel/PHP project's public/ folder or root.
 * Access via: https://your-domain.com/git_pull.php?key=mysecret123
 */

// --------------------------------------------------------------------
// 1. SECURITY KEY
// --------------------------------------------------------------------
$secretKey = 'mysecret123';

// Timing-safe secret verification
$providedKey = (string)($_POST['key'] ?? $_GET['key'] ?? '');
if (!hash_equals($secretKey, $providedKey)) {
    http_response_code(403);
    die('<!DOCTYPE html>
    <html lang="en">
    <head><meta charset="UTF-8"><title>403 Access Denied</title>
    <style>body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#090d16;color:#f87171;font-family:monospace;font-size:16px;text-align:center;}</style>
    </head>
    <body>
        <div>
            <h1 style="font-size:32px;margin:0 0 10px;">⛔ 403 Forbidden</h1>
            <p style="color:#94a3b8;">Invalid or missing security key.<br>Pass <code>?key=YOUR_SECRET</code> in the URL.</p>
        </div>
    </body></html>');
}

// Timeout extension
@set_time_limit(300);
@ini_set('max_execution_time', '300');

// --------------------------------------------------------------------
// 2. 100% DYNAMIC AUTO-PATH DETECTION (ZERO HARDCODED PATHS)
// --------------------------------------------------------------------
// Kisi bhi server ya project me yeh khud project root dhoondh lega
function detectProjectRoot($startDir) {
    $dir = $startDir;
    // Step 1: Upward traverse karke artisan dhoondo (up to 4 levels)
    for ($i = 0; $i < 4; $i++) {
        if (@file_exists($dir . '/artisan')) {
            return realpath($dir) ?: $dir;
        }
        $parent = dirname($dir);
        if ($parent === $dir) break;
        $dir = $parent;
    }
    
    // Step 2: Agar artisan nahi mila toh composer.json ya .git dhoondo
    $dir = $startDir;
    for ($i = 0; $i < 4; $i++) {
        if (@file_exists($dir . '/composer.json') || @file_exists($dir . '/.git')) {
            return realpath($dir) ?: $dir;
        }
        $parent = dirname($dir);
        if ($parent === $dir) break;
        $dir = $parent;
    }
    
    // Default fallback: public directory ka parent
    return realpath(dirname($startDir)) ?: $startDir;
}

$detectedRoot = detectProjectRoot(__DIR__);
$repoPath = trim((string)($_POST['custom_path'] ?? $_GET['custom_path'] ?? $detectedRoot));
$hasArtisan = @file_exists($repoPath . '/artisan');
$hasGit = @file_exists($repoPath . '/.git');

// --------------------------------------------------------------------
// 3. SMART SHELL EXECUTION WITH INTERACTIVE INPUT & PROMPT HANDLING
// --------------------------------------------------------------------
function runShell($cmd, $path, $autoYes = true, $customInput = '') {
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    
    // Non-interactive environment variables to prevent blocking
    $envPrefix = "";
    if (!$isWindows) {
        $envPrefix = "export COMPOSER_HOME=/tmp && "
                   . "export HOME=/tmp && "
                   . "export COMPOSER_NO_INTERACTION=1 && "
                   . "export GIT_TERMINAL_PROMPT=0 && "
                   . "export CI=true && "
                   . "export DEBIAN_FRONTEND=noninteractive && "
                   . "export PATH=/usr/local/bin:/usr/bin:/bin:\$PATH && ";
    }
    
    // Handling commands that prompt for confirmation (yes/no or interactive input)
    $finalCmd = $cmd;
    if (!empty($customInput)) {
        // User ne specific input pipe kiya
        if ($isWindows) {
            $finalCmd = "echo " . escapeshellarg($customInput) . " | " . $cmd;
        } else {
            $finalCmd = "printf " . escapeshellarg($customInput . "\n") . " | " . $cmd;
        }
    } elseif ($autoYes) {
        // Commands that ask for confirmation (e.g. migrate --force, db:wipe, etc.)
        // Artisan commands ko safely auto-confirm karne ke liye
        if (strpos($cmd, 'php artisan') !== false && strpos($cmd, '--no-interaction') === false && strpos($cmd, '-n') === false) {
            $finalCmd = $cmd . " --no-interaction";
        }
    }
    
    if ($isWindows) {
        $full = "cd /d " . escapeshellarg($path) . " && " . $finalCmd . " 2>&1";
    } else {
        $full = "cd " . escapeshellarg($path) . " && " . $envPrefix . $finalCmd . " 2>&1";
    }
    
    return shell_exec($full);
}

function executeCmd($title, $cmd, $path, $autoYes = true, $customInput = '') {
    echo "<div style='margin-bottom: 16px; border-left: 4px solid #38bdf8; padding-left: 14px;'>";
    echo "<div style='display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;'>";
    echo "<h4 style='margin: 0; color: #f1f5f9; font-family: monospace; font-size: 14px;'>▶ {$title}</h4>";
    echo "<span style='background:#1e293b; color:#38bdf8; font-size: 11px; font-family: monospace; padding: 2px 8px; border-radius: 4px;'>" . date('H:i:s') . "</span>";
    echo "</div>";
    echo "<div style='color: #94a3b8; font-size: 12px; font-family: monospace; margin: 4px 0 8px;'>Command: " . htmlspecialchars($cmd) . "</div>";
    
    $start = microtime(true);
    $output = runShell($cmd, $path, $autoYes, $customInput);
    $duration = round(microtime(true) - $start, 3);
    
    echo "<pre style='background: #020617; color: #38bdf8; padding: 14px; border-radius: 8px; font-size: 12.5px; overflow-x: auto; margin: 0; white-space: pre-wrap; line-height: 1.5; border: 1px solid #1e293b;'>";
    echo htmlspecialchars(trim((string)$output) ?: '✓ Executed successfully (no terminal output returned).');
    echo "</pre>";
    echo "<div style='text-align:right; font-size: 10px; color:#64748b; margin-top:3px;'>Execution time: {$duration}s</div>";
    echo "</div>";
    
    if (ob_get_level() > 0) { ob_flush(); }
    flush();
}

$action = (string)($_POST['action'] ?? $_GET['action'] ?? '');
$customCmd = trim((string)($_POST['custom_command'] ?? ''));
$autoYes = isset($_POST['auto_yes']) ? (bool)$_POST['auto_yes'] : true;
$customInput = trim((string)($_POST['custom_input'] ?? ''));

// Quick stats
$phpVersion = PHP_VERSION;
$currentBranch = trim((string)runShell("git branch --show-current", $repoPath, false)) ?: 'unknown';
$diskFree = @disk_free_space($repoPath) ? round(disk_free_space($repoPath) / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A';

// Laravel log inspection
$laravelLogFile = $repoPath . '/storage/logs/laravel.log';
$logExists = @file_exists($laravelLogFile);
$logSize = $logExists ? round(@filesize($laravelLogFile) / 1024 / 1024, 2) . ' MB' : '0 MB (no file)';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaxTV Smart Deploy & Terminal</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #090d16; color: #f1f5f9; padding: 16px; margin: 0; line-height: 1.4; }
        .wrapper { max-width: 1450px; margin: 0 auto; }
        
        /* Top Bar */
        .top-navbar { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand h2 { margin: 0; font-size: 19px; color: #f8fafc; font-weight: 700; letter-spacing: -0.02em; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; font-family: monospace; }
        .badge-success { background: #064e3b; color: #34d399; border: 1px solid #059669; }
        .badge-danger { background: #7f1d1d; color: #f87171; border: 1px solid #dc2626; }
        
        /* Layout Grid */
        .layout-grid { display: grid; grid-template-columns: 420px 1fr; gap: 16px; align-items: start; }
        
        /* Left Column: Shortcuts */
        .sidebar-card { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 18px; box-shadow: 0 4px 25px rgba(0,0,0,0.4); max-height: calc(100vh - 120px); overflow-y: auto; position: sticky; top: 16px; }
        .sidebar-card::-webkit-scrollbar { width: 6px; }
        .sidebar-card::-webkit-scrollbar-thumb { background: #374151; border-radius: 3px; }
        
        /* Right Column: Console & Output */
        .main-card { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 20px 24px; box-shadow: 0 4px 25px rgba(0,0,0,0.4); }
        
        .stats-bar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
        .stat-item { background: #090d16; border: 1px solid #1f2937; padding: 6px 11px; border-radius: 6px; font-size: 11.5px; font-family: monospace; color: #94a3b8; }
        .stat-item b { color: #38bdf8; }
        
        .path-box { background: #090d16; border: 1px solid #1f2937; border-radius: 8px; padding: 9px 12px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: 11.5px; font-family: monospace; }
        
        /* Buttons */
        .btn { padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; transition: 0.15s; }
        .btn-primary { background: #0284c7; color: white; }
        .btn-primary:hover { background: #0369a1; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        
        /* Inputs */
        .input-cmd { width: 100%; padding: 12px 14px; background: #090d16; border: 1px solid #374151; border-radius: 6px; color: #38bdf8; font-family: "Fira Code", monospace; font-size: 13.5px; }
        .input-cmd:focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 0 2px rgba(56,189,248,0.2); }
        
        .search-box { width: 100%; padding: 9px 12px; background: #090d16; border: 1px solid #1f2937; border-radius: 6px; color: #f1f5f9; font-size: 12.5px; margin-bottom: 14px; }
        .search-box:focus { outline: none; border-color: #38bdf8; }
        
        /* Shortcut Chips */
        .shortcut-group { margin-bottom: 16px; border-bottom: 1px dashed #1f2937; padding-bottom: 14px; }
        .shortcut-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .group-title { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .chips { display: flex; flex-direction: column; gap: 5px; }
        .chip { background: #090d16; border: 1px solid #1f2937; color: #cbd5e1; padding: 7px 10px; border-radius: 6px; font-size: 11.5px; font-family: monospace; cursor: pointer; transition: 0.15s; user-select: none; display: flex; justify-content: space-between; align-items: center; }
        .chip:hover { background: #1e293b; color: #38bdf8; border-color: #38bdf8; }
        .chip-desc { font-size: 10.5px; color: #64748b; }
        
        .chip-log { border-left: 3px solid #3b82f6; }
        .chip-git { border-left: 3px solid #10b981; }
        .chip-artisan { border-left: 3px solid #f59e0b; }
        .chip-db { border-left: 3px solid #8b5cf6; }
        .chip-danger { border-left: 3px solid #ef4444; color: #fca5a5; }
        .chip-danger:hover { background: #7f1d1d; color: white; border-color: #ef4444; }
        
        /* Terminal / Output Console */
        .terminal-box { background: #020617; border: 1px solid #1e293b; border-radius: 10px; padding: 18px 20px; margin-top: 18px; box-shadow: inset 0 2px 10px rgba(0,0,0,0.6); }
        .terminal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1e293b; padding-bottom: 10px; margin-bottom: 14px; flex-wrap: wrap; gap: 8px; }
        
        @media (max-width: 980px) {
            .layout-grid { grid-template-columns: 1fr; }
            .sidebar-card { position: static; max-height: none; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- Top Navigation & Global Controls -->
    <div class="top-navbar">
        <div class="brand">
            <h2>⚡ PaxTV Smart Terminal & Deployment</h2>
            <span class="badge badge-success">v3.0 Dynamic</span>
        </div>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <a href="?key=<?= urlencode($providedKey) ?>&action=auto_deploy&custom_path=<?= urlencode($repoPath) ?>" 
               class="btn btn-success" 
               onclick="return confirm('Execute Full Production Auto-Deploy? (Git Pull -> Migrate -> Cache Clear & Build)');">
                🚀 One-Click Auto Deploy
            </a>
        </div>
    </div>

    <!-- 2-Column Responsive Layout -->
    <div class="layout-grid">
        
        <!-- ================= LEFT COLUMN: SHORTCUTS ================= -->
        <div class="sidebar-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span style="font-size:12px; font-weight:700; text-transform:uppercase; color:#94a3b8; letter-spacing:0.05em;">⚡ Command Center</span>
                <span style="font-size:11px; color:#64748b;">Click to load</span>
            </div>

            <!-- Filter Search -->
            <input type="text" id="shortcutSearch" class="search-box" placeholder="🔍 Search shortcuts (log, git, cache, db)..." onkeyup="filterShortcuts()">

            <div id="shortcutsContainer">
                
                <!-- 1. Logs View & Clear -->
                <div class="shortcut-group">
                    <div class="group-title">📜 Laravel Logs</div>
                    <div class="chips">
                        <div class="chip chip-log" onclick="setCmd('tail -n 50 storage/logs/laravel.log')">
                            <span>tail -n 50 laravel.log</span>
                            <span class="chip-desc">50 lines</span>
                        </div>
                        <div class="chip chip-log" onclick="setCmd('tail -n 200 storage/logs/laravel.log')">
                            <span>tail -n 200 laravel.log</span>
                            <span class="chip-desc">200 lines</span>
                        </div>
                        <div class="chip chip-log" onclick="setCmd('ls -lh storage/logs/')">
                            <span>ls -lh storage/logs/</span>
                            <span class="chip-desc">All logs</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('truncate -s 0 storage/logs/laravel.log && echo \'✓ Log cleared successfully!\'', 'Wipe out laravel.log completely?')">
                            <span>🧹 Clear laravel.log</span>
                            <span class="chip-desc">truncate 0</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('rm -f storage/logs/laravel-*.log && echo \'✓ Rotated logs deleted!\'', 'Delete all old daily logs?')">
                            <span>Delete laravel-*.log</span>
                            <span class="chip-desc">rm old</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Git Operations -->
                <div class="shortcut-group">
                    <div class="group-title">🌿 Git Operations</div>
                    <div class="chips">
                        <div class="chip chip-git" onclick="setCmd('git status')">
                            <span>git status</span>
                            <span class="chip-desc">status</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git pull origin main')">
                            <span>git pull origin main</span>
                            <span class="chip-desc">pull</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git log -n 5 --oneline')">
                            <span>git log -n 5 --oneline</span>
                            <span class="chip-desc">last 5</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git diff --stat')">
                            <span>git diff --stat</span>
                            <span class="chip-desc">changed files</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git stash')">
                            <span>git stash</span>
                            <span class="chip-desc">stash</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git stash pop')">
                            <span>git stash pop</span>
                            <span class="chip-desc">pop stash</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('git reset --hard HEAD', 'Discard all uncommitted local modifications?')">
                            <span>git reset --hard HEAD</span>
                            <span class="chip-desc">discard changes</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('git fetch origin main && git reset --hard origin/main', 'OVERWRITE local code with origin/main completely?')">
                            <span>Force Sync: origin/main</span>
                            <span class="chip-desc">hard overwrite</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Laravel Cache & Optimization -->
                <div class="shortcut-group">
                    <div class="group-title">⚡ Cache & Artisan</div>
                    <div class="chips">
                        <div class="chip chip-artisan" onclick="setCmd('php artisan optimize:clear')">
                            <span>optimize:clear</span>
                            <span class="chip-desc">clean all</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan config:cache && php artisan route:cache && php artisan view:cache')">
                            <span>rebuild prod cache</span>
                            <span class="chip-desc">cfg+rt+view</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan config:clear')">
                            <span>config:clear</span>
                            <span class="chip-desc">env reload</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan view:clear')">
                            <span>view:clear</span>
                            <span class="chip-desc">blade compile</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan route:list --compact')">
                            <span>route:list --compact</span>
                            <span class="chip-desc">routes</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan storage:link')">
                            <span>storage:link</span>
                            <span class="chip-desc">symlink</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Database & Migrations -->
                <div class="shortcut-group">
                    <div class="group-title">🗄️ Database & Seeders</div>
                    <div class="chips">
                        <div class="chip chip-db" onclick="setCmd('php artisan migrate:status')">
                            <span>migrate:status</span>
                            <span class="chip-desc">pending check</span>
                        </div>
                        <div class="chip chip-db" onclick="confirmCmd('php artisan migrate --force', 'Run pending migrations on production database?')">
                            <span>migrate --force</span>
                            <span class="chip-desc">run schema</span>
                        </div>
                        <div class="chip chip-db" onclick="setCmd('php artisan db:show')">
                            <span>db:show</span>
                            <span class="chip-desc">schema overview</span>
                        </div>
                        <div class="chip chip-db" onclick="setCmd('php artisan db:seed --class=RolesAndPermissionsSeeder')">
                            <span>seed: RolesAndPermissions</span>
                            <span class="chip-desc">roles</span>
                        </div>
                        <div class="chip chip-db" onclick="setCmd('php artisan db:seed --class=SuperAdminSeeder')">
                            <span>seed: SuperAdmin</span>
                            <span class="chip-desc">superadmin</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('php artisan migrate:rollback --step=1', 'Rollback last migration batch?')">
                            <span>migrate:rollback (step 1)</span>
                            <span class="chip-desc">undo last</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Queues & System Health -->
                <div class="shortcut-group">
                    <div class="group-title">🛠️ Workers & Server Info</div>
                    <div class="chips">
                        <div class="chip" onclick="setCmd('php artisan queue:restart')">
                            <span>queue:restart</span>
                            <span class="chip-desc">workers</span>
                        </div>
                        <div class="chip" onclick="setCmd('php artisan queue:failed')">
                            <span>queue:failed</span>
                            <span class="chip-desc">failed jobs</span>
                        </div>
                        <div class="chip" onclick="setCmd('composer dump-autoload -o')">
                            <span>composer dump-autoload</span>
                            <span class="chip-desc">optimize</span>
                        </div>
                        <div class="chip" onclick="setCmd('chmod -R 775 storage bootstrap/cache')">
                            <span>chmod -R 775 storage</span>
                            <span class="chip-desc">fix permissions</span>
                        </div>
                        <div class="chip" onclick="setCmd('php artisan about')">
                            <span>php artisan about</span>
                            <span class="chip-desc">environment</span>
                        </div>
                        <div class="chip" onclick="setCmd('df -h')">
                            <span>df -h</span>
                            <span class="chip-desc">disk space</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= RIGHT COLUMN: CONSOLE & TERMINAL ================= -->
        <div class="main-card">
            
            <!-- Dynamic Path Bar -->
            <div class="path-box">
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <span style="color:#64748b;">Project Root:</span>
                    <span style="color:#38bdf8; font-weight:bold;"><?= htmlspecialchars($repoPath) ?></span>
                    <?php if ($hasArtisan): ?>
                        <span class="badge badge-success">✓ artisan</span>
                    <?php else: ?>
                        <span class="badge badge-danger">✗ no artisan</span>
                    <?php endif; ?>
                    <?php if ($hasGit): ?>
                        <span class="badge badge-success">✓ git repo</span>
                    <?php else: ?>
                        <span class="badge badge-danger">✗ no .git</span>
                    <?php endif; ?>
                </div>
                
                <form method="GET" style="display:inline-flex; gap:6px; align-items:center;">
                    <input type="hidden" name="key" value="<?= htmlspecialchars($providedKey) ?>">
                    <input type="text" name="custom_path" value="<?= htmlspecialchars($repoPath) ?>" placeholder="Override path..." style="background:#111827; border:1px solid #374151; color:#94a3b8; font-size:11px; padding:4px 8px; border-radius:4px; font-family:monospace; width:220px;">
                    <button type="submit" class="btn btn-primary" style="padding:4px 8px; font-size:11px;">Switch</button>
                </form>
            </div>

            <!-- Server Status Stats -->
            <div class="stats-bar">
                <div class="stat-item">Git Branch: <b><?= htmlspecialchars($currentBranch) ?></b></div>
                <div class="stat-item">PHP CLI: <b>v<?= htmlspecialchars($phpVersion) ?></b></div>
                <div class="stat-item">Free Disk: <b><?= htmlspecialchars($diskFree) ?></b></div>
                <div class="stat-item">Laravel Log Size: <b><?= htmlspecialchars($logSize) ?></b></div>
                <div class="stat-item">Server Time: <b><?= date('Y-m-d H:i:s') ?></b></div>
            </div>

            <!-- Command Form with Interactive Prompts Control -->
            <form method="POST" action="?key=<?= htmlspecialchars($providedKey) ?>&custom_path=<?= urlencode($repoPath) ?>">
                <input type="hidden" name="key" value="<?= htmlspecialchars($providedKey) ?>">
                <input type="hidden" name="action" value="custom_cmd">
                <input type="hidden" name="custom_path" value="<?= htmlspecialchars($repoPath) ?>">

                <div style="margin-bottom: 10px;">
                    <input type="text" name="custom_command" id="cmdInput" class="input-cmd" placeholder="Type terminal command or click any shortcut on the left..." value="<?= htmlspecialchars($customCmd) ?>" required autocomplete="off">
                </div>

                <!-- Advanced Prompt Controls (Answers interactive commands) -->
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; background:#090d16; border:1px solid #1f2937; padding:8px 12px; border-radius:6px; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:6px; font-size:12px; color:#cbd5e1; cursor:pointer;">
                            <input type="checkbox" name="auto_yes" value="1" <?= $autoYes ? 'checked' : '' ?> style="accent-color:#0284c7;">
                            <span>Auto-confirm prompts (<code>--no-interaction</code> / auto-yes)</span>
                        </label>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="font-size:11px; color:#94a3b8;">Pipe input to prompt:</span>
                            <input type="text" name="custom_input" value="<?= htmlspecialchars($customInput) ?>" placeholder="e.g. yes or custom answer" style="background:#111827; border:1px solid #374151; color:#38bdf8; font-size:11px; padding:3px 8px; border-radius:4px; font-family:monospace; width:150px;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding:8px 18px;">▶ Run Command</button>
                </div>
            </form>

            <!-- Output Console / Terminal Section -->
            <div class="terminal-box" id="terminalBox">
                <div class="terminal-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="display:inline-block; width:11px; height:11px; background:#ef4444; border-radius:50%;"></span>
                        <span style="display:inline-block; width:11px; height:11px; background:#f59e0b; border-radius:50%;"></span>
                        <span style="display:inline-block; width:11px; height:11px; background:#10b981; border-radius:50%;"></span>
                        <span style="font-family:monospace; font-size:13px; font-weight:600; color:#cbd5e1; margin-left:6px;">
                            <?= $action === 'auto_deploy' ? '🚀 Deployment Console Output' : ($action === 'custom_cmd' ? '💻 Terminal Session Output' : '💻 Terminal Ready') ?>
                        </span>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <?php if (!empty($action)): ?>
                            <button onclick="copyTerminal()" style="background:#1e293b; color:#38bdf8; border:1px solid #334155; padding:3px 10px; border-radius:4px; font-size:11.5px; cursor:pointer; font-weight:600;">📋 Copy Output</button>
                        <?php endif; ?>
                        <span style="background:#1e293b; color:#94a3b8; font-size:11px; padding:2px 8px; border-radius:4px; font-family:monospace;"><?= date('H:i:s') ?></span>
                    </div>
                </div>

                <div id="terminalBody">
                <?php
                if ($action === 'custom_cmd' && !empty($customCmd)) {
                    executeCmd("Manual Command", $customCmd, $repoPath, $autoYes, $customInput);
                } elseif ($action === 'auto_deploy') {
                    executeCmd("1. Stashing local modifications", "git stash", $repoPath, $autoYes);
                    executeCmd("2. Pulling latest commits from origin/main", "git pull origin main", $repoPath, $autoYes);
                    executeCmd("3. Optimizing autoloader", "composer dump-autoload -o", $repoPath, $autoYes);
                    executeCmd("4. Executing pending database migrations", "php artisan migrate --force", $repoPath, $autoYes);
                    executeCmd("5. Resetting application optimization cache", "php artisan optimize:clear", $repoPath, $autoYes);
                    executeCmd("6. Ensuring storage link exists", "php artisan storage:link", $repoPath, $autoYes);
                    executeCmd("7. Compiling config, route, view cache", "php artisan config:cache && php artisan route:cache && php artisan view:cache", $repoPath, $autoYes);
                    executeCmd("8. Restarting background queues", "php artisan queue:restart", $repoPath, $autoYes);
                    
                    echo "<div style='margin-top: 15px; padding: 12px; background: #064e3b; border-radius: 6px; border: 1px solid #059669; color: #34d399; font-weight: 700; text-align: center; font-size: 14px;'>🎉 FULL PRODUCTION AUTO-DEPLOYMENT COMPLETED!</div>";
                } else {
                    echo "<div style='color:#64748b; font-family:monospace; font-size:12.5px; padding:20px 0; text-align:center;'>Terminal is idle. Click any shortcut on the left or type a command above to execute.</div>";
                }
                ?>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function setCmd(cmd) {
    const input = document.getElementById('cmdInput');
    input.value = cmd;
    input.focus();
}

function confirmCmd(cmd, msg) {
    if (confirm(msg)) {
        setCmd(cmd);
        document.querySelector('form[method="POST"]').submit();
    }
}

function filterShortcuts() {
    const filter = document.getElementById('shortcutSearch').value.toLowerCase();
    const chips = document.querySelectorAll('.chip');
    chips.forEach(chip => {
        const text = chip.textContent.toLowerCase();
        chip.style.display = text.includes(filter) ? 'flex' : 'none';
    });
}

function copyTerminal() {
    const text = document.getElementById('terminalBody').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Terminal output copied to clipboard!');
    }).catch(err => {
        alert('Could not copy output: ' + err);
    });
}

// Auto-scroll to terminal when command runs
<?php if (!empty($action)): ?>
window.addEventListener('load', function() {
    const box = document.getElementById('terminalBox');
    if (box) { box.scrollIntoView({ behavior: 'smooth' }); }
});
<?php endif; ?>
</script>

</body>
</html>
