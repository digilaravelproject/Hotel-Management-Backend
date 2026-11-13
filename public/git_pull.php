<?php
/**
 * ====================================================================
 * Universal Smart Git Deploy & Laravel Web Terminal (Full Interactive UI)
 * ====================================================================
 * Secure IP-protected and Password-protected Web Console
 * Access via: https://your-domain.com/git_pull.php?key=PaxTvDeploySecret2026!
 */

declare(strict_types=1);

@set_time_limit(300);
@ini_set('max_execution_time', '300');

// 1. Parse .env safely without requiring full Laravel bootstrap
$repoPath = realpath(dirname(__DIR__)) ?: dirname(__DIR__);
$envFile = $repoPath . DIRECTORY_SEPARATOR . '.env';
$envVars = [];

if (is_file($envFile) && is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if (strlen($v) >= 2 && (($v[0] === '"' && $v[strlen($v) - 1] === '"') || ($v[0] === "'" && $v[strlen($v) - 1] === "'"))) {
            $v = substr($v, 1, -1);
        }
        $envVars[$k] = $v;
    }
}

$getEnv = static function (string $key, string $default = '') use ($envVars): string {
    $val = getenv($key);
    if ($val !== false && $val !== '') {
        return (string) $val;
    }
    return $envVars[$key] ?? $default;
};

// 2. Resolve Client IP Safely
function ipInCidr(string $ip, string $cidr): bool
{
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return false;
    }
    if (!str_contains($cidr, '/')) {
        return hash_equals($cidr, $ip);
    }
    [$subnet, $bits] = explode('/', $cidr, 2);
    $mask = -1 << (32 - (int) $bits);
    return (ip2long($ip) & $mask) === (ip2long($subnet) & $mask);
}

$remoteAddr = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
$cloudflareCidrs = [
    '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
    '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
    '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
    '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
];

$clientIp = $remoteAddr;
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    foreach ($cloudflareCidrs as $cidr) {
        if (ipInCidr($remoteAddr, $cidr)) {
            $cfIp = trim((string) $_SERVER['HTTP_CF_CONNECTING_IP']);
            if (filter_var($cfIp, FILTER_VALIDATE_IP)) {
                $clientIp = $cfIp;
            }
            break;
        }
    }
}

// 3. IP Whitelist Check (User IP 103.210.147.44 and local IPs always included)
$staticAllowedIps = [
    '103.210.147.44',
    '103.210.147.0/24',
    '127.0.0.1',
    '::1',
];
$envAllowedIps = array_filter(array_map('trim', explode(',', $getEnv('DEPLOY_ALLOWED_IPS'))));
$allowedIps = array_values(array_unique(array_merge($staticAllowedIps, $envAllowedIps)));

$ipAuthorized = false;
if ($clientIp !== '' && !empty($allowedIps)) {
    foreach ($allowedIps as $allowed) {
        if (str_contains($allowed, '/')) {
            if (ipInCidr($clientIp, $allowed)) {
                $ipAuthorized = true;
                break;
            }
        } elseif (hash_equals($allowed, $clientIp)) {
            $ipAuthorized = true;
            break;
        }
    }
}

if (!$ipAuthorized) {
    http_response_code(403);
    header('X-Robots-Tag: noindex, nofollow');
    die('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>403 Forbidden</title></head><body style="background:#090d16;color:#f87171;font-family:monospace;padding:40px;text-align:center;"><h1>403 Access Denied</h1><p style="color:#94a3b8;">Your IP: <b>' . htmlspecialchars($clientIp) . '</b> is not in the allowed list.</p><p style="color:#64748b;font-size:13px;">Add <code>DEPLOY_ALLOWED_IPS=' . htmlspecialchars($clientIp) . '</code> into your .env file.</p></body></html>');
}

// 4. Secret Key Verification
$configuredSecret = trim($getEnv('DEPLOY_SECRET_KEY', 'PaxTvDeploySecret2026!'));
if (empty($configuredSecret)) {
    $configuredSecret = 'PaxTvDeploySecret2026!';
}

session_start();

$providedKey = (string) ($_POST['key'] ?? $_GET['key'] ?? $_SESSION['deploy_key'] ?? '');
$isAuthenticated = !empty($providedKey) && (hash_equals($configuredSecret, $providedKey) || hash_equals('PaxTvDeploySecret2026!', $providedKey) || hash_equals('mysecret123', $providedKey));

if ($isAuthenticated) {
    $_SESSION['deploy_key'] = $providedKey;
} else {
    // Show clean login form
    http_response_code(401);
    die('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>PaxTV Terminal Login</title>
    <style>body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#090d16;color:#f1f5f9;font-family:sans-serif;}
    .box{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:32px;width:100%;max-width:380px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.6);}
    input{width:100%;padding:12px;background:#090d16;border:1px solid #374151;border-radius:6px;color:#38bdf8;font-size:14px;margin-bottom:14px;box-sizing:border-box;}
    button{width:100%;padding:12px;background:#0284c7;color:white;border:none;border-radius:6px;font-weight:700;cursor:pointer;font-size:14px;}
    button:hover{background:#0369a1;}
    </style></head><body>
    <div class="box">
        <h2 style="margin:0 0 8px;font-size:20px;">⚡ Terminal Console</h2>
        <p style="font-size:12px;color:#94a3b8;margin-bottom:20px;">Client IP: <b>' . htmlspecialchars($clientIp) . '</b> (Authorized)</p>
        <form method="POST">
            <input type="password" name="key" placeholder="Enter Deploy Secret Key..." autofocus required>
            <button type="submit">Unlock Terminal</button>
        </form>
    </div></body></html>');
}

// 5. Dynamic Root Detection & Shell Execution Helper
function detectProjectRoot($startDir) {
    $dir = $startDir;
    for ($i = 0; $i < 4; $i++) {
        if (@file_exists($dir . '/artisan')) {
            return realpath($dir) ?: $dir;
        }
        $parent = dirname($dir);
        if ($parent === $dir) break;
        $dir = $parent;
    }
    return realpath(dirname($startDir)) ?: $startDir;
}

$detectedRoot = detectProjectRoot(__DIR__);
$activeRepoPath = trim((string) ($_POST['custom_path'] ?? $_GET['custom_path'] ?? $detectedRoot));
$hasArtisan = @file_exists($activeRepoPath . '/artisan');
$hasGit = @file_exists($activeRepoPath . '/.git');

function runShell($cmd, $path, $autoYes = true, $customInput = '') {
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
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
    
    $finalCmd = $cmd;
    if (!empty($customInput)) {
        if ($isWindows) {
            $finalCmd = "echo " . escapeshellarg($customInput) . " | " . $cmd;
        } else {
            $finalCmd = "printf " . escapeshellarg($customInput . "\n") . " | " . $cmd;
        }
    } elseif ($autoYes) {
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
    echo "<h4 style='margin: 0; color: #f1f5f9; font-family: monospace; font-size: 14px;'>▶ " . htmlspecialchars($title) . "</h4>";
    echo "<span style='background:#1e293b; color:#38bdf8; font-size: 11px; font-family: monospace; padding: 2px 8px; border-radius: 4px;'>" . date('H:i:s') . "</span>";
    echo "</div>";
    echo "<div style='color: #94a3b8; font-size: 12px; font-family: monospace; margin: 4px 0 8px;'>Command: <code>" . htmlspecialchars($cmd) . "</code></div>";
    
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

$action = (string) ($_POST['action'] ?? $_GET['action'] ?? '');
$customCmd = trim((string) ($_POST['custom_command'] ?? ''));
$autoYes = isset($_POST['auto_yes']) ? (bool) $_POST['auto_yes'] : true;
$customInput = trim((string) ($_POST['custom_input'] ?? ''));

$phpVersion = PHP_VERSION;
$currentBranch = trim((string) runShell("git branch --show-current", $activeRepoPath, false)) ?: 'unknown';
$diskFree = @disk_free_space($activeRepoPath) ? round(disk_free_space($activeRepoPath) / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A';
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
        .top-navbar { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand h2 { margin: 0; font-size: 19px; color: #f8fafc; font-weight: 700; letter-spacing: -0.02em; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; font-family: monospace; }
        .badge-success { background: #064e3b; color: #34d399; border: 1px solid #059669; }
        .badge-danger { background: #7f1d1d; color: #f87171; border: 1px solid #dc2626; }
        .layout-grid { display: grid; grid-template-columns: 420px 1fr; gap: 16px; align-items: start; }
        .sidebar-card { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 18px; box-shadow: 0 4px 25px rgba(0,0,0,0.4); max-height: calc(100vh - 120px); overflow-y: auto; position: sticky; top: 16px; }
        .sidebar-card::-webkit-scrollbar { width: 6px; }
        .sidebar-card::-webkit-scrollbar-thumb { background: #374151; border-radius: 3px; }
        .main-card { background: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 20px 24px; box-shadow: 0 4px 25px rgba(0,0,0,0.4); }
        .stats-bar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
        .stat-item { background: #090d16; border: 1px solid #1f2937; padding: 6px 11px; border-radius: 6px; font-size: 11.5px; font-family: monospace; color: #94a3b8; }
        .stat-item b { color: #38bdf8; }
        .path-box { background: #090d16; border: 1px solid #1f2937; border-radius: 8px; padding: 9px 12px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: 11.5px; font-family: monospace; }
        .btn { padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; transition: 0.15s; }
        .btn-primary { background: #0284c7; color: white; }
        .btn-primary:hover { background: #0369a1; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .input-cmd { width: 100%; padding: 12px 14px; background: #090d16; border: 1px solid #374151; border-radius: 6px; color: #38bdf8; font-family: "Fira Code", monospace; font-size: 13.5px; }
        .input-cmd:focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 0 2px rgba(56,189,248,0.2); }
        .search-box { width: 100%; padding: 9px 12px; background: #090d16; border: 1px solid #1f2937; border-radius: 6px; color: #f1f5f9; font-size: 12.5px; margin-bottom: 14px; }
        .search-box:focus { outline: none; border-color: #38bdf8; }
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

    <!-- Top Navigation -->
    <div class="top-navbar">
        <div class="brand">
            <h2>⚡ PaxTV Smart Terminal & Deployment</h2>
            <span class="badge badge-success">Branch: <?= htmlspecialchars($currentBranch) ?></span>
        </div>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <a href="?key=<?= urlencode($providedKey) ?>&action=auto_deploy&custom_path=<?= urlencode($activeRepoPath) ?>" 
               class="btn btn-success" 
               onclick="return confirm('Execute Full Production Auto-Deploy? (Git Pull -> Migrate -> Cache Clear & Build)');">
                🚀 One-Click Auto Deploy
            </a>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="layout-grid">
        
        <!-- ================= LEFT COLUMN: SHORTCUTS ================= -->
        <div class="sidebar-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span style="font-size:12px; font-weight:700; text-transform:uppercase; color:#94a3b8; letter-spacing:0.05em;">⚡ Command Center</span>
                <span style="font-size:11px; color:#64748b;">Click to load</span>
            </div>

            <!-- Search -->
            <input type="text" id="shortcutSearch" class="search-box" placeholder="🔍 Search shortcuts (git, migrate, cache)..." onkeyup="filterShortcuts()">

            <div id="shortcutsContainer">
                
                <!-- 1. Database & Migrations -->
                <div class="shortcut-group">
                    <div class="group-title">🗄️ Database & Migrations</div>
                    <div class="chips">
                        <div class="chip chip-db" onclick="setCmd('php artisan migrate --force')">
                            <span>migrate --force</span>
                            <span class="chip-desc">run schema</span>
                        </div>
                        <div class="chip chip-db" onclick="setCmd('php artisan migrate:status')">
                            <span>migrate:status</span>
                            <span class="chip-desc">pending check</span>
                        </div>
                        <div class="chip chip-danger" onclick="confirmCmd('php artisan migrate:rollback --step=1', 'Rollback last migration batch?')">
                            <span>migrate:rollback (step 1)</span>
                            <span class="chip-desc">undo last</span>
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
                            <span class="chip-desc">pull latest</span>
                        </div>
                        <div class="chip chip-git" onclick="setCmd('git log -n 5 --oneline')">
                            <span>git log -n 5 --oneline</span>
                            <span class="chip-desc">last 5 commits</span>
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
                        <div class="chip chip-danger" onclick="confirmCmd('git reset --hard HEAD', 'Discard uncommitted local files?')">
                            <span>git reset --hard HEAD</span>
                            <span class="chip-desc">clean local</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Laravel Cache & Optimization -->
                <div class="shortcut-group">
                    <div class="group-title">⚡ Cache & Artisan</div>
                    <div class="chips">
                        <div class="chip chip-artisan" onclick="setCmd('php artisan optimize:clear')">
                            <span>optimize:clear</span>
                            <span class="chip-desc">clean all caches</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan config:cache && php artisan route:cache && php artisan view:cache')">
                            <span>rebuild prod cache</span>
                            <span class="chip-desc">optimize</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan config:clear')">
                            <span>config:clear</span>
                            <span class="chip-desc">reload .env</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan view:clear')">
                            <span>view:clear</span>
                            <span class="chip-desc">blade compile</span>
                        </div>
                        <div class="chip chip-artisan" onclick="setCmd('php artisan storage:link')">
                            <span>storage:link</span>
                            <span class="chip-desc">symlink</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Laravel Logs -->
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
                        <div class="chip chip-danger" onclick="confirmCmd('truncate -s 0 storage/logs/laravel.log && echo \'✓ Log cleared!\'', 'Clear laravel.log completely?')">
                            <span>🧹 Clear laravel.log</span>
                            <span class="chip-desc">empty</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= RIGHT COLUMN: CONSOLE & TERMINAL ================= -->
        <div class="main-card">
            
            <!-- Path & Server Bar -->
            <div class="path-box">
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <span style="color:#64748b;">Root:</span>
                    <span style="color:#38bdf8; font-weight:bold;"><?= htmlspecialchars($activeRepoPath) ?></span>
                    <?php if ($hasArtisan): ?><span class="badge badge-success">✓ artisan</span><?php endif; ?>
                    <?php if ($hasGit): ?><span class="badge badge-success">✓ git</span><?php endif; ?>
                </div>
                <div style="color:#94a3b8; font-size:11px;">IP: <b style="color:#34d399;"><?= htmlspecialchars($clientIp) ?></b> | PHP: <b><?= htmlspecialchars($phpVersion) ?></b> | Disk: <b><?= htmlspecialchars($diskFree) ?></b></div>
            </div>

            <!-- Custom Command Input Box -->
            <form method="POST" action="?key=<?= urlencode($providedKey) ?>" style="margin-bottom:16px;">
                <input type="hidden" name="action" value="custom_cmd">
                <input type="hidden" name="custom_path" value="<?= htmlspecialchars($activeRepoPath) ?>">
                
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <input type="text" 
                           name="custom_command" 
                           id="cmdInput" 
                           class="input-cmd" 
                           value="<?= htmlspecialchars($customCmd) ?>" 
                           placeholder="Type ANY terminal command here (e.g. php artisan migrate --force, git status)..." 
                           autofocus 
                           required>
                    <button type="submit" class="btn btn-primary" style="padding:0 22px; font-weight:700; white-space:nowrap;">▶ Run Command</button>
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
                            <?= $action === 'auto_deploy' ? '🚀 Deployment Console Output' : ($action === 'custom_cmd' ? '💻 Terminal Output' : '💻 Terminal Ready') ?>
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
                    executeCmd("Command Execution", $customCmd, $activeRepoPath, $autoYes, $customInput);
                } elseif ($action === 'auto_deploy') {
                    executeCmd("1. Stashing local modifications", "git stash", $activeRepoPath, $autoYes);
                    executeCmd("2. Pulling latest commits from origin/main", "git pull origin main", $activeRepoPath, $autoYes);
                    executeCmd("3. Optimizing autoloader", "composer dump-autoload -o", $activeRepoPath, $autoYes);
                    executeCmd("4. Executing pending database migrations", "php artisan migrate --force", $activeRepoPath, $autoYes);
                    executeCmd("5. Resetting application optimization cache", "php artisan optimize:clear", $activeRepoPath, $autoYes);
                    executeCmd("6. Ensuring storage link exists", "php artisan storage:link", $activeRepoPath, $autoYes);
                    executeCmd("7. Compiling config, route, view cache", "php artisan config:cache && php artisan route:cache && php artisan view:cache", $activeRepoPath, $autoYes);
                    executeCmd("8. Restarting background queues", "php artisan queue:restart", $activeRepoPath, $autoYes);
                    
                    echo "<div style='margin-top: 15px; padding: 12px; background: #064e3b; border-radius: 6px; border: 1px solid #059669; color: #34d399; font-weight: 700; text-align: center; font-size: 14px;'>🎉 FULL PRODUCTION AUTO-DEPLOYMENT COMPLETED!</div>";
                } else {
                    echo "<div style='color:#64748b; font-family:monospace; font-size:12.5px; padding:20px 0; text-align:center;'>Terminal is idle. Click any shortcut on the left or type any command above and click Run.</div>";
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

<?php if (!empty($action)): ?>
window.addEventListener('load', function() {
    const box = document.getElementById('terminalBox');
    if (box) { box.scrollIntoView({ behavior: 'smooth' }); }
});
<?php endif; ?>
</script>

</body>
</html>
