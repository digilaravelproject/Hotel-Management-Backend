<?php
/**
 * ====================================================================
 * PaxTV - Smart Deployment Console & Server Control (Auto-Path & Multi-Tool)
 * ====================================================================
 * Place this file inside: public/git_pull.php (or your project root)
 * Access via browser: https://your-domain.com/git_pull.php?key=mysecret123
 */

// --------------------------------------------------------------------
// 1. SECURITY KEY (Apna secret key yaha change karein)
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
// 2. SMART AUTO PATH DETECTION (Never fails regardless of folder name)
// --------------------------------------------------------------------
$candidates = [
    dirname(__DIR__), // Normal Laravel standard: public/../ -> Project Root
    __DIR__,          // If placed directly in root
    '/home/n6ec6pcl98ie/public_html/app.paxtvnetwork.com',
    '/home/n6ec6pcl98ie/public_html/paxtvhospitality.paxtvnetwork.com',
];

$detectedPath = null;
foreach ($candidates as $cand) {
    if (!empty($cand) && @file_exists($cand . '/artisan')) {
        $detectedPath = realpath($cand) ?: $cand;
        break;
    }
}

// Fallback to parent directory if artisan wasn't found immediately
if (!$detectedPath) {
    $detectedPath = realpath(dirname(__DIR__)) ?: dirname(__DIR__);
}

// Allow user manual override via POST/GET
$repoPath = trim((string)($_POST['custom_path'] ?? $_GET['custom_path'] ?? $detectedPath));
$hasArtisan = @file_exists($repoPath . '/artisan');
$hasGit = @file_exists($repoPath . '/.git');

// --------------------------------------------------------------------
// 3. EXECUTION HELPER
// --------------------------------------------------------------------
function runShell($cmd, $path) {
    // Detect OS
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    
    if ($isWindows) {
        $fullCmd = "cd /d " . escapeshellarg($path) . " && " . $cmd . " 2>&1";
    } else {
        $envPrefix = "export COMPOSER_HOME=/tmp && export HOME=/tmp && export PATH=/usr/local/bin:/usr/bin:/bin:\$PATH && ";
        $fullCmd = "cd " . escapeshellarg($path) . " && " . $envPrefix . $cmd . " 2>&1";
    }
    
    return shell_exec($fullCmd);
}

function executeCmd($title, $cmd, $path) {
    echo "<div style='margin-bottom: 16px; border-left: 4px solid #38bdf8; padding-left: 14px;'>";
    echo "<div style='display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;'>";
    echo "<h4 style='margin: 0; color: #0f172a; font-family: monospace; font-size: 15px;'>▶ {$title}</h4>";
    echo "<span style='background:#0f172a; color:#38bdf8; font-size: 11px; font-family: monospace; padding: 2px 8px; border-radius: 4px;'>" . date('H:i:s') . "</span>";
    echo "</div>";
    echo "<div style='color: #64748b; font-size: 12px; font-family: monospace; margin: 4px 0 8px;'>Command: " . htmlspecialchars($cmd) . "</div>";
    
    $start = microtime(true);
    $output = runShell($cmd, $path);
    $duration = round(microtime(true) - $start, 3);
    
    echo "<pre style='background: #090d16; color: #38bdf8; padding: 14px; border-radius: 8px; font-size: 12.5px; overflow-x: auto; margin: 0; white-space: pre-wrap; line-height: 1.5; border: 1px solid #1e293b;'>";
    echo htmlspecialchars(trim((string)$output) ?: '✓ Executed successfully (no terminal output returned).');
    echo "</pre>";
    echo "<div style='text-align:right; font-size: 10px; color:#94a3b8; margin-top:3px;'>Execution time: {$duration}s</div>";
    echo "</div>";
    
    if (ob_get_level() > 0) { ob_flush(); }
    flush();
}

$action = (string)($_POST['action'] ?? $_GET['action'] ?? '');
$customCmd = trim((string)($_POST['custom_command'] ?? ''));

// Quick stats
$phpVersion = PHP_VERSION;
$currentBranch = trim((string)runShell("git branch --show-current", $repoPath)) ?: 'unknown';
$diskFree = @disk_free_space($repoPath) ? round(disk_free_space($repoPath) / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A';

// Laravel log file inspection
$laravelLogFile = $repoPath . '/storage/logs/laravel.log';
$logExists = @file_exists($laravelLogFile);
$logSize = $logExists ? round(@filesize($laravelLogFile) / 1024 / 1024, 2) . ' MB' : '0 MB (no file)';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaxTV Smart Deploy Console</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #090d16; color: #f1f5f9; padding: 20px; margin: 0; line-height: 1.4; }
        .container { max-width: 1100px; margin: 0 auto; }
        .card { background: #111827; border-radius: 12px; padding: 22px 24px; border: 1px solid #1f2937; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1f2937; padding-bottom: 16px; margin-bottom: 16px; flex-wrap: wrap; gap: 14px; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; font-family: monospace; }
        .badge-success { background: #064e3b; color: #34d399; border: 1px solid #059669; }
        .badge-danger { background: #7f1d1d; color: #f87171; border: 1px solid #dc2626; }
        
        .stats-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
        .stat-item { background: #090d16; border: 1px solid #1f2937; padding: 7px 12px; border-radius: 6px; font-size: 12px; font-family: monospace; color: #94a3b8; }
        .stat-item b { color: #38bdf8; }
        
        .btn { padding: 9px 18px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; transition: 0.15s; }
        .btn-primary { background: #0284c7; color: white; }
        .btn-primary:hover { background: #0369a1; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        
        .path-box { background: #090d16; border: 1px solid #1f2937; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; font-size: 12px; font-family: monospace; }
        
        .input-group { display: flex; gap: 8px; margin-top: 10px; }
        .input-cmd { flex: 1; padding: 12px 14px; background: #090d16; border: 1px solid #374151; border-radius: 6px; color: #38bdf8; font-family: "Fira Code", monospace; font-size: 13.5px; }
        .input-cmd:focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 0 2px rgba(56,189,248,0.2); }
        
        .search-box { width: 100%; padding: 10px 14px; background: #090d16; border: 1px solid #1f2937; border-radius: 6px; color: #f1f5f9; font-size: 13px; margin: 16px 0 12px; }
        .search-box:focus { outline: none; border-color: #38bdf8; }
        
        .shortcut-group { margin-top: 14px; border-top: 1px dashed #1f2937; padding-top: 12px; }
        .group-title { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .chips { display: flex; flex-wrap: wrap; gap: 7px; }
        .chip { background: #090d16; border: 1px solid #1f2937; color: #cbd5e1; padding: 6px 11px; border-radius: 6px; font-size: 12px; font-family: monospace; cursor: pointer; transition: 0.15s; user-select: none; }
        .chip:hover { background: #1e293b; color: #38bdf8; border-color: #38bdf8; transform: translateY(-1px); }
        
        .chip-log { border-color: #1e3a8a; color: #93c5fd; }
        .chip-log:hover { background: #1e3a8a; color: #ffffff; border-color: #60a5fa; }
        
        .chip-warn { border-color: #78350f; color: #fde68a; }
        .chip-warn:hover { background: #92400e; color: #ffffff; border-color: #f59e0b; }
        
        .chip-danger { border-color: #7f1d1d; color: #fca5a5; }
        .chip-danger:hover { background: #991b1b; color: white; border-color: #ef4444; }
        
        .output-card { background: #ffffff; color: #0f172a; border-radius: 12px; padding: 22px 26px; box-shadow: 0 10px 35px rgba(0,0,0,0.6); margin-top: 24px; }
        
        @media (max-width: 640px) {
            body { padding: 12px; }
            .card { padding: 16px; }
            .input-group { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="card">
        <!-- Header -->
        <div class="header">
            <div>
                <h2 style="margin: 0; color: #f8fafc; font-size: 20px; display: flex; align-items: center; gap: 8px;">
                    🚀 PaxTV Deployment & Server Control
                </h2>
                <small style="color: #64748b; font-family: monospace;">Single-click server maintenance & Git automation</small>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="?key=<?= urlencode($providedKey) ?>&action=auto_deploy&custom_path=<?= urlencode($repoPath) ?>" 
                   class="btn btn-success" 
                   onclick="return confirm('Execute Full Production Auto-Deploy? (Git Pull -> Migrate -> Clear & Build Cache)');">
                    ⚡ Auto Deploy (Full Pipeline)
                </a>
            </div>
        </div>

        <!-- Path Bar with Detection Indicators -->
        <div class="path-box">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span style="color:#64748b;">Target Root:</span>
                <span style="color:#38bdf8; font-weight:bold;"><?= htmlspecialchars($repoPath) ?></span>
                <?php if ($hasArtisan): ?>
                    <span class="badge badge-success">✓ artisan detected</span>
                <?php else: ?>
                    <span class="badge badge-danger">✗ artisan not found</span>
                <?php endif; ?>
                <?php if ($hasGit): ?>
                    <span class="badge badge-success">✓ .git ok</span>
                <?php else: ?>
                    <span class="badge badge-danger">✗ .git missing</span>
                <?php endif; ?>
            </div>
            
            <form method="GET" style="display:inline-flex; gap:6px; align-items:center;">
                <input type="hidden" name="key" value="<?= htmlspecialchars($providedKey) ?>">
                <input type="text" name="custom_path" value="<?= htmlspecialchars($repoPath) ?>" placeholder="Override path..." style="background:#111827; border:1px solid #374151; color:#94a3b8; font-size:11px; padding:4px 8px; border-radius:4px; font-family:monospace; width:220px;">
                <button type="submit" class="btn btn-primary" style="padding:4px 8px; font-size:11px;">Change</button>
            </form>
        </div>

        <!-- Live Server Status Bar -->
        <div class="stats-bar">
            <div class="stat-item">Git Branch: <b><?= htmlspecialchars($currentBranch) ?></b></div>
            <div class="stat-item">PHP CLI: <b>v<?= htmlspecialchars($phpVersion) ?></b></div>
            <div class="stat-item">Free Disk: <b><?= htmlspecialchars($diskFree) ?></b></div>
            <div class="stat-item">Laravel Log Size: <b><?= htmlspecialchars($logSize) ?></b></div>
            <div class="stat-item">Server Time: <b><?= date('Y-m-d H:i:s') ?></b></div>
        </div>

        <!-- Command Form -->
        <form method="POST" action="?key=<?= htmlspecialchars($providedKey) ?>&custom_path=<?= urlencode($repoPath) ?>">
            <input type="hidden" name="key" value="<?= htmlspecialchars($providedKey) ?>">
            <input type="hidden" name="action" value="custom_cmd">
            <input type="hidden" name="custom_path" value="<?= htmlspecialchars($repoPath) ?>">

            <div class="input-group">
                <input type="text" name="custom_command" id="cmdInput" class="input-cmd" placeholder="Type or click a shortcut below..." value="<?= htmlspecialchars($customCmd) ?>" required autocomplete="off">
                <button type="submit" class="btn btn-primary">▶ Run Command</button>
            </div>
        </form>

        <!-- Shortcut Search -->
        <input type="text" id="shortcutSearch" class="search-box" placeholder="🔍 Type to filter shortcuts (e.g. log, git, cache, migrate, queue)..." onkeyup="filterShortcuts()">

        <!-- Categorized Shortcuts -->
        <div id="shortcutsContainer">
            
            <!-- 1. Logs View & Clear -->
            <div class="shortcut-group">
                <div class="group-title">📜 Laravel Logs (View & Clear)</div>
                <div class="chips">
                    <span class="chip chip-log" onclick="setCmd('tail -n 50 storage/logs/laravel.log')">tail -n 50 laravel.log (Last 50 lines)</span>
                    <span class="chip chip-log" onclick="setCmd('tail -n 200 storage/logs/laravel.log')">tail -n 200 laravel.log (Last 200 lines)</span>
                    <span class="chip chip-log" onclick="setCmd('tail -n 500 storage/logs/laravel.log')">tail -n 500 laravel.log</span>
                    <span class="chip chip-log" onclick="setCmd('ls -lh storage/logs/')">ls -lh storage/logs/ (All logs list)</span>
                    <span class="chip chip-warn" onclick="confirmCmd('truncate -s 0 storage/logs/laravel.log && echo \'✓ laravel.log cleared successfully!\'', 'Clear all contents of storage/logs/laravel.log?')">🧹 Clear laravel.log (truncate 0)</span>
                    <span class="chip chip-warn" onclick="confirmCmd('> storage/logs/laravel.log && echo \'✓ Log wiped!\'', 'Empty laravel.log file?')">Empty laravel.log (&gt;)</span>
                    <span class="chip chip-danger" onclick="confirmCmd('rm -f storage/logs/laravel-*.log && echo \'✓ Old daily logs deleted!\'', 'Delete all old daily rotated logs?')">Delete old laravel-*.log files</span>
                </div>
            </div>

            <!-- 2. Git Operations -->
            <div class="shortcut-group">
                <div class="group-title">🌿 Git Operations</div>
                <div class="chips">
                    <span class="chip" onclick="setCmd('git status')">git status</span>
                    <span class="chip" onclick="setCmd('git pull origin main')">git pull origin main</span>
                    <span class="chip" onclick="setCmd('git log -n 5 --oneline')">git log (last 5)</span>
                    <span class="chip" onclick="setCmd('git log -n 15 --oneline')">git log (last 15)</span>
                    <span class="chip" onclick="setCmd('git diff --stat')">git diff (changed files)</span>
                    <span class="chip" onclick="setCmd('git stash')">git stash</span>
                    <span class="chip" onclick="setCmd('git stash pop')">git stash pop</span>
                    <span class="chip" onclick="setCmd('git fetch origin main')">git fetch origin main</span>
                    <span class="chip chip-danger" onclick="confirmCmd('git reset --hard HEAD', 'Discard all uncommitted local modifications?')">git reset --hard HEAD</span>
                    <span class="chip chip-danger" onclick="confirmCmd('git fetch origin main && git reset --hard origin/main', 'OVERWRITE local code with origin/main completely?')">Force Sync: git reset --hard origin/main</span>
                </div>
            </div>

            <!-- 3. Laravel Cache & Optimization -->
            <div class="shortcut-group">
                <div class="group-title">⚡ Laravel Cache & Optimizations</div>
                <div class="chips">
                    <span class="chip" onclick="setCmd('php artisan optimize:clear')">optimize:clear (full clean)</span>
                    <span class="chip" onclick="setCmd('php artisan config:cache && php artisan route:cache && php artisan view:cache')">rebuild production cache</span>
                    <span class="chip" onclick="setCmd('php artisan config:clear')">config:clear</span>
                    <span class="chip" onclick="setCmd('php artisan route:clear')">route:clear</span>
                    <span class="chip" onclick="setCmd('php artisan view:clear')">view:clear</span>
                    <span class="chip" onclick="setCmd('php artisan cache:clear')">cache:clear</span>
                    <span class="chip" onclick="setCmd('php artisan route:list --compact')">route:list (compact)</span>
                    <span class="chip" onclick="setCmd('php artisan storage:link')">storage:link</span>
                </div>
            </div>

            <!-- 4. Database & Migrations -->
            <div class="shortcut-group">
                <div class="group-title">🗄️ Database & Migrations</div>
                <div class="chips">
                    <span class="chip" onclick="setCmd('php artisan migrate:status')">migrate:status</span>
                    <span class="chip" onclick="confirmCmd('php artisan migrate --force', 'Run pending migrations on production database?')">migrate --force</span>
                    <span class="chip" onclick="setCmd('php artisan db:show')">db:show (Database Info)</span>
                    <span class="chip" onclick="setCmd('php artisan db:table users')">db:table users</span>
                    <span class="chip" onclick="setCmd('php artisan db:seed --class=RolesAndPermissionsSeeder')">seed: RolesAndPermissions</span>
                    <span class="chip" onclick="setCmd('php artisan db:seed --class=SuperAdminSeeder')">seed: SuperAdmin</span>
                    <span class="chip chip-danger" onclick="confirmCmd('php artisan migrate:rollback --step=1', 'Rollback the last migration batch?')">migrate:rollback (step 1)</span>
                </div>
            </div>

            <!-- 5. Queues, Background & Maintenance -->
            <div class="shortcut-group">
                <div class="group-title">🔄 Queues, Workers & Maintenance</div>
                <div class="chips">
                    <span class="chip" onclick="setCmd('php artisan queue:restart')">queue:restart</span>
                    <span class="chip" onclick="setCmd('php artisan queue:failed')">queue:failed (list failed jobs)</span>
                    <span class="chip" onclick="setCmd('php artisan queue:retry all')">queue:retry all</span>
                    <span class="chip" onclick="setCmd('php artisan queue:flush')">queue:flush (delete failed jobs)</span>
                    <span class="chip" onclick="setCmd('composer dump-autoload -o')">composer dump-autoload -o</span>
                    <span class="chip chip-warn" onclick="confirmCmd('php artisan down --secret=deploying123', 'Put site in maintenance mode?')">site down (maintenance)</span>
                    <span class="chip" onclick="setCmd('php artisan up')">site up (bring live)</span>
                </div>
            </div>

            <!-- 6. Server Health & Permissions -->
            <div class="shortcut-group">
                <div class="group-title">🛠️ Server Health & File Permissions</div>
                <div class="chips">
                    <span class="chip" onclick="setCmd('chmod -R 775 storage bootstrap/cache')">Fix storage & cache permissions (775)</span>
                    <span class="chip" onclick="setCmd('php -v')">php -v (CLI version)</span>
                    <span class="chip" onclick="setCmd('which php')">which php path</span>
                    <span class="chip" onclick="setCmd('git --version')">git --version</span>
                    <span class="chip" onclick="setCmd('composer -V')">composer -V</span>
                    <span class="chip" onclick="setCmd('php artisan about')">php artisan about</span>
                    <span class="chip" onclick="setCmd('df -h')">df -h (Disk space)</span>
                    <span class="chip" onclick="setCmd('free -m')">free -m (RAM status)</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Output Section -->
    <?php if ($action === 'auto_deploy' || ($action === 'custom_cmd' && !empty($customCmd))): ?>
    <div class="output-card" id="outputCard">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; flex-wrap:wrap; gap:8px;">
            <h3 style="margin:0; color:#0f172a; font-size:17px; display:flex; align-items:center; gap:8px;">
                <?= $action === 'auto_deploy' ? '📋 Full Automated Deploy Log' : '💻 Command Execution Result' ?>
            </h3>
            <div style="display:flex; gap:8px; align-items:center;">
                <button onclick="copyOutput()" style="background:#f1f5f9; border:1px solid #cbd5e1; padding:4px 10px; border-radius:5px; font-size:12px; cursor:pointer; font-weight:600;">📋 Copy Log</button>
                <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;"><?= date('H:i:s') ?></span>
            </div>
        </div>

        <div id="logContent">
        <?php
        if ($action === 'custom_cmd' && !empty($customCmd)) {
            executeCmd("Manual Command", $customCmd, $repoPath);
        } elseif ($action === 'auto_deploy') {
            executeCmd("1. Stashing local changes", "git stash", $repoPath);
            executeCmd("2. Pulling latest code from origin/main", "git pull origin main", $repoPath);
            executeCmd("3. Dumping optimized autoloader", "composer dump-autoload -o", $repoPath);
            executeCmd("4. Executing database migrations", "php artisan migrate --force", $repoPath);
            executeCmd("5. Clearing all caches", "php artisan optimize:clear", $repoPath);
            executeCmd("6. Ensuring storage symlink", "php artisan storage:link", $repoPath);
            executeCmd("7. Compiling production cache (config, route, view)", "php artisan config:cache && php artisan route:cache && php artisan view:cache", $repoPath);
            executeCmd("8. Restarting background queue workers", "php artisan queue:restart", $repoPath);
            
            echo "<div style='margin-top: 20px; padding: 14px; background: #ecfdf5; border-radius: 8px; border: 1px solid #a7f3d0; color: #065f46; font-weight: 700; text-align: center; font-size: 15px;'>🎉 FULL PRODUCTION DEPLOYMENT COMPLETED SUCCESSFULLY!</div>";
        }
        ?>
        </div>
    </div>
    <script>
        // Auto-scroll to output on load
        window.addEventListener('load', function() {
            const card = document.getElementById('outputCard');
            if (card) { card.scrollIntoView({ behavior: 'smooth' }); }
        });
    </script>
    <?php endif; ?>

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
        chip.style.display = text.includes(filter) ? 'inline-block' : 'none';
    });
}

function copyOutput() {
    const log = document.getElementById('logContent').innerText;
    navigator.clipboard.writeText(log).then(() => {
        alert('Output copied to clipboard!');
    }).catch(err => {
        alert('Could not copy output: ' + err);
    });
}
</script>

</body>
</html>
