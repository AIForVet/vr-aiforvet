<?php
// GitHub webhook: on a push to main, update this checkout with `git pull --ff-only`.
//
// Setup on the server (once):  openssl rand -hex 32 > ~/.vr-aiforvet-deploy-secret && chmod 600 ~/.vr-aiforvet-deploy-secret
// GitHub: Settings → Webhooks → Payload URL https://vr.aiforvet.eu/deploy.php, content type application/json,
// the same secret, "Just the push event". The log is ~/vr-aiforvet-deploy.log (outside public_html).

declare(strict_types=1);

$home = dirname(__DIR__);
$secretfile = $home . '/.vr-aiforvet-deploy-secret';
$logfile = $home . '/vr-aiforvet-deploy.log';

function finish(int $code, string $message): void {
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    echo $message, "\n";
    exit;
}

function logline(string $file, string $message): void {
    file_put_contents($file, gmdate('Y-m-d H:i:s') . 'Z ' . $message . "\n", FILE_APPEND | LOCK_EX);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    finish(405, 'POST only');
}
$secret = is_readable($secretfile) ? trim((string) file_get_contents($secretfile)) : '';
if ($secret === '') {
    finish(500, 'Deploy secret is not configured');
}

$body = (string) file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
if (!hash_equals('sha256=' . hash_hmac('sha256', $body, $secret), $signature)) {
    logline($logfile, 'rejected: bad signature from ' . ($_SERVER['REMOTE_ADDR'] ?? '?'));
    finish(403, 'Bad signature');
}

$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
if ($event === 'ping') {
    logline($logfile, 'ping');
    finish(200, 'pong');
}
$payload = json_decode($body, true);
if ($event !== 'push' || ($payload['ref'] ?? '') !== 'refs/heads/main') {
    finish(202, 'Ignored: not a push to main');
}

exec('git -C ' . escapeshellarg(__DIR__) . ' pull --ff-only --quiet 2>&1', $output, $status);
$after = substr((string) ($payload['after'] ?? ''), 0, 7);
logline($logfile, ($status === 0 ? 'deployed ' : "FAILED ($status) ") . $after . ($output ? ': ' . implode(' | ', $output) : ''));
finish($status === 0 ? 200 : 500, $status === 0 ? "Deployed $after" : 'git pull failed, see the log');
