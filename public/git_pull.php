<?php
/**
 * ====================================================================
 * Restricted Smart Deployment Utility (IP-Allowlisted & Env-Secret Auth)
 * ====================================================================
 * Configure both variables in .env before accessing:
 *   DEPLOY_ALLOWED_IPS=203.0.113.10,127.0.0.1
 *   DEPLOY_SECRET_KEY=<minimum-16-char-random-secret>
 *
 * Security hardening:
 * - Fails CLOSED if DEPLOY_ALLOWED_IPS or DEPLOY_SECRET_KEY is missing.
 * - Uses REMOTE_ADDR strictly (only honors CF-Connecting-IP when REMOTE_ADDR is a verified Cloudflare IP).
 * - Locks project root to dirname(__DIR__) with no arbitrary path/command RCE.
 * - Enforces CSRF tokens on all POST actions.
 */

declare(strict_types=1);

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

// 2. Resolve true client IP safely (only trust CF-Connecting-IP if peer is verified Cloudflare range)
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

// 3. Enforce Strict IP Whitelist (Fail-Closed if empty)
$staticAllowedIps = [
    // Add your static IP here or set DEPLOY_ALLOWED_IPS in .env
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
    die('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>403 Forbidden</title></head><body style="background:#090d16;color:#f87171;font-family:monospace;padding:40px;text-align:center;"><h1>403 Access Denied</h1><p style="color:#94a3b8;">Access restricted to allowlisted IPs configured in DEPLOY_ALLOWED_IPS.</p></body></html>');
}

// 4. Verify Strong Secret Key from .env (No hardcoded fallback)
$secretKey = trim($getEnv('DEPLOY_SECRET_KEY'));
if (strlen($secretKey) < 16) {
    http_response_code(503);
    header('X-Robots-Tag: noindex, nofollow');
    die('Deployment utility disabled: set a 16+ char DEPLOY_SECRET_KEY in .env.');
}

session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Strict',
]);
session_start();

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow');

if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: git_pull.php');
    exit;
}

$isAuthenticated = !empty($_SESSION['deploy_auth']) && $_SESSION['deploy_auth'] === true;

if (!$isAuthenticated) {
    $providedKey = (string) ($_POST['key'] ?? $_SERVER['HTTP_X_DEPLOY_KEY'] ?? '');
    if ($providedKey !== '' && hash_equals($secretKey, $providedKey)) {
        session_regenerate_id(true);
        $_SESSION['deploy_auth'] = true;
        $_SESSION['deploy_csrf'] = bin2hex(random_bytes(32));
        $isAuthenticated = true;
    } elseif ($providedKey !== '') {
        http_response_code(403);
        $authError = 'Invalid deployment secret key.';
    }
}

if (!$isAuthenticated) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PaxTV Deploy Gate</title>
        <style>
            body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#090d16;color:#e2e8f0;font-family:system-ui,sans-serif;}
            .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:28px;width:360px;box-shadow:0 8px 30px rgba(0,0,0,.45);}
            input{width:100%;padding:10px 12px;background:#090d16;border:1px solid #374151;border-radius:6px;color:#fff;margin:12px 0;box-sizing:border-box;}
            button{width:100%;padding:10px;background:#0284c7;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;}
        </style>
    </head>
    <body>
        <form method="POST" class="card">
            <h2 style="margin-top:0;font-size:18px;">PaxTV Authorized Deploy</h2>
            <?php if (!empty($authError)): ?>
                <p style="color:#f87171;font-size:13px;"><?= htmlspecialchars($authError, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <input type="password" name="key" placeholder="Enter DEPLOY_SECRET_KEY" required autofocus autocomplete="off">
            <button type="submit">Authenticate</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

if (empty($_SESSION['deploy_csrf'])) {
    $_SESSION['deploy_csrf'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['deploy_csrf'];

// 5. Strict Allowlist of Predefined Maintenance Commands (No arbitrary shell execution)
function runSafeCommand(array $cmdParts, string $cwd): string
{
    $escaped = implode(' ', array_map('escapeshellarg', $cmdParts));
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $process = @proc_open($escaped, $descriptors, $pipes, $cwd);
    if (!is_resource($process)) {
        return 'Failed to start process.';
    }
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
    return trim((string) $stdout . ($stderr ? "\n" . $stderr : ''));
}

$allowedActions = [
    'git_status'     => 'Git Status',
    'git_pull'       => 'Git Pull (Current Branch)',
    'optimize_clear' => 'Artisan Optimize Clear',
    'config_cache'   => 'Artisan Config Cache',
    'route_cache'    => 'Artisan Route Cache',
    'view_clear'     => 'Artisan View Clear',
    'migrate'        => 'Artisan Migrate (--force)',
    'queue_restart'  => 'Artisan Queue Restart',
    'tail_log'       => 'Tail Last 80 Lines of laravel.log',
];

$actionOutput = null;
$selectedAction = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $postedCsrf = (string) ($_POST['csrf_token'] ?? '');
    if (!hash_equals($csrfToken, $postedCsrf)) {
        http_response_code(419);
        die('Invalid CSRF token.');
    }

    $selectedAction = (string) $_POST['action'];
    if (!array_key_exists($selectedAction, $allowedActions)) {
        $actionOutput = 'Action rejected: not in allowed deployment actions.';
    } else {
        $phpBin = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
        if ($selectedAction === 'git_status') {
            $actionOutput = runSafeCommand(['git', 'status', '-sb'], $repoPath);
        } elseif ($selectedAction === 'git_pull') {
            $branch = trim(runSafeCommand(['git', 'branch', '--show-current'], $repoPath));
            if ($branch === '' || !preg_match('/^[A-Za-z0-9._\/-]+$/', $branch)) {
                $actionOutput = 'Unable to determine a valid current git branch.';
            } else {
                $actionOutput = runSafeCommand(['git', 'pull', 'origin', $branch], $repoPath);
            }
        } elseif ($selectedAction === 'optimize_clear') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'optimize:clear'], $repoPath);
        } elseif ($selectedAction === 'config_cache') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'config:cache'], $repoPath);
        } elseif ($selectedAction === 'route_cache') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'route:cache'], $repoPath);
        } elseif ($selectedAction === 'view_clear') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'view:clear'], $repoPath);
        } elseif ($selectedAction === 'migrate') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'migrate', '--force'], $repoPath);
        } elseif ($selectedAction === 'queue_restart') {
            $actionOutput = runSafeCommand([$phpBin, 'artisan', 'queue:restart'], $repoPath);
        } elseif ($selectedAction === 'tail_log') {
            $logPath = $repoPath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'laravel.log';
            if (!is_file($logPath)) {
                $actionOutput = 'No laravel.log file found.';
            } else {
                $lines = @file($logPath, FILE_IGNORE_NEW_LINES) ?: [];
                $actionOutput = implode("\n", array_slice($lines, -80));
            }
        }
    }
}

$currentBranch = trim(runSafeCommand(['git', 'branch', '--show-current'], $repoPath)) ?: 'unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaxTV Safe Deploy Console</title>
    <style>
        body{margin:0;padding:24px;background:#090d16;color:#f1f5f9;font-family:system-ui,sans-serif;}
        .wrap{max-width:960px;margin:0 auto;}
        .bar{display:flex;justify-content:space-between;align-items:center;background:#111827;border:1px solid #1f2937;border-radius:10px;padding:16px 20px;margin-bottom:16px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:10px;margin-bottom:20px;}
        button.act{width:100%;padding:12px;border-radius:8px;border:1px solid #334155;background:#0f172a;color:#e2e8f0;font-weight:600;cursor:pointer;text-align:left;}
        button.act:hover{border-color:#38bdf8;color:#38bdf8;}
        pre{background:#020617;border:1px solid #1e293b;border-radius:10px;padding:16px;color:#38bdf8;white-space:pre-wrap;overflow-x:auto;}
    </style>
</head>
<body>
<div class="wrap">
    <div class="bar">
        <div>
            <strong style="font-size:17px;">PaxTV Safe Deploy Console</strong>
            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Branch: <?= htmlspecialchars($currentBranch, ENT_QUOTES, 'UTF-8') ?> | Authorized IP: <?= htmlspecialchars($clientIp, ENT_QUOTES, 'UTF-8') ?></div>
        </div>
        <form method="POST" style="margin:0;">
            <button type="submit" name="logout" value="1" style="background:#dc2626;color:#fff;border:none;border-radius:6px;padding:8px 14px;cursor:pointer;font-weight:600;">Logout</button>
        </form>
    </div>

    <form method="POST" class="grid">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <?php foreach ($allowedActions as $key => $label): ?>
            <button class="act" type="submit" name="action" value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </button>
        <?php endforeach; ?>
    </form>

    <?php if ($actionOutput !== null): ?>
        <div style="font-size:12px;color:#94a3b8;margin-bottom:6px;">Result for: <?= htmlspecialchars($allowedActions[$selectedAction] ?? $selectedAction, ENT_QUOTES, 'UTF-8') ?></div>
        <pre><?= htmlspecialchars($actionOutput ?: 'Command completed with no output.', ENT_QUOTES, 'UTF-8') ?></pre>
    <?php endif; ?>
</div>
</body>
</html>
