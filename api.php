<?php
/**
 * qr. — API endpoint for server-side QR code generation
 * GET params: data, level, size, fg, bg, dl
 */

header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff');

include __DIR__ . '/qrlib.php';

$data  = isset($_GET['data'])  ? trim($_GET['data'])  : '';
$level = isset($_GET['level']) && in_array($_GET['level'], ['L','M','Q','H'])
       ? $_GET['level'] : 'M';
$size  = isset($_GET['size'])  ? min(max((int)$_GET['size'], 1), 10) : 5;
$fg    = isset($_GET['fg'])    ? preg_replace('/[^0-9a-fA-F]/', '', $_GET['fg']) : '000000';
$bg    = isset($_GET['bg'])    ? preg_replace('/[^0-9a-fA-F]/', '', $_GET['bg']) : 'ffffff';
$dl    = isset($_GET['dl'])    && $_GET['dl'] === '1';

if (strlen($fg) !== 6) $fg = '000000';
if (strlen($bg) !== 6) $bg = 'ffffff';

if ($data === '') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'data param is required']);
    exit;
}

// Ensure temp dir exists
$tempDir = __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
if (!is_dir($tempDir) && !@mkdir($tempDir, 0755, true)) {
    http_response_code(500);
    echo 'Cannot create temp directory';
    exit;
}

// Generate PNG via library
$filename = $tempDir . 'api_' . md5($data . $level . $size) . '.png';
QRcode::png($data, $filename, $level, $size, 2);

if (!file_exists($filename)) {
    http_response_code(500);
    echo 'QR generation failed';
    exit;
}

// Re-color using GD palette manipulation
$img = imagecreatefrompng($filename);
if (!$img) {
    http_response_code(500);
    echo 'Image load failed';
    exit;
}

$fgR = hexdec(substr($fg, 0, 2));
$fgG = hexdec(substr($fg, 2, 2));
$fgB = hexdec(substr($fg, 4, 2));
$bgR = hexdec(substr($bg, 0, 2));
$bgG = hexdec(substr($bg, 2, 2));
$bgB = hexdec(substr($bg, 4, 2));

// The QR image is a 2-color palette image — swap palette entries directly
$blackIdx = imagecolorexact($img, 0, 0, 0);
$whiteIdx = imagecolorexact($img, 255, 255, 255);
if ($blackIdx !== -1) imagecolorset($img, $blackIdx, $fgR, $fgG, $fgB);
if ($whiteIdx !== -1) imagecolorset($img, $whiteIdx, $bgR, $bgG, $bgB);

if ($dl) {
    header('Content-Disposition: attachment; filename="qr-code.png"');
}
header('Content-Type: image/png');
header('Cache-Control: public, max-age=3600');
imagepng($img);
imagedestroy($img);
