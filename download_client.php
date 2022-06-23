<?php

// XenForo only supports PHP 7 or newer
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.0.0', '<')) {
	die("PHP 7.0.0 or newer is required. $phpVersion does not meet this requirement. Please ask your host to upgrade PHP.");
}

// Return domain address (In case the user does not have permissions, etc.)
const MY_DOMAIN = 'https://felphooks.com/';

// Name of the file to download
// NOTE: Remember to block directory access on your Apache/Nginx (or whatever webserver you are using)
const FILE_NAME = '<Your Cheat Client Path Here>';

// Number of characters the random string must have
const FILE_NAME_LEN = 16;

// 4000 * 1024, Approx. 4MB/second
const DOWNLOAD_RATE = 4000;

function generateRandomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function downloadFile($filename) {
	try {
		if (!file_exists($filename)) {
			throw new Exception('File ' . $filename . ' does not exist');
		}

		if (!is_file($filename)) {
			throw new Exception('File ' . $filename . ' is not valid');
		}

		header('Cache-Control: private');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($filename));
		header('Content-Disposition: filename=' . generateRandomString(FILE_NAME_LEN) . '.exe');

		flush();

		$f = fopen($filename, 'r');

		while (!feof($f)) {
			print fread($f, round(DOWNLOAD_RATE * 1024));
			flush();

			sleep(1);
		}
	} catch (\Throwable $e) {
		echo $e->getMessage();
	} finally {
		if ($f) {
			fclose($f);
		}

		exit();
	}
}

function redirect($url) {
	ob_start();
	header('Location: ' . $url);
	ob_end_flush();
	die();
}

$dir = __DIR__;
require($dir . '/src/XF.php');

XF::start($dir);
$app = \XF::setupApp('XF\Pub\App');
$app->start();

$visitor = \XF::visitor();
$user_id = $visitor['user_id'];

// Visitor is not logged in
if (!$user_id) {
	redirect(MY_DOMAIN);
}

// User is banned, redirect them to main page and let him deal with the support
$isBanned = $visitor['is_banned'];
if ($isBanned) {
	redirect(MY_DOMAIN);
}

$secondaryGroupIds = $visitor['secondary_group_ids'];

$isCustomer = in_array(5, $secondaryGroupIds);
$isBetaTester = in_array(6, $secondaryGroupIds);
$isDeveloper = in_array(7, $secondaryGroupIds);

$isModerator = $visitor['is_moderator'];
$isStaff = $visitor['is_staff'];
$isAdmin = $visitor['is_admin'];

$canDownload = ($isCustomer || $isBetaTester || $isDeveloper || $isModerator || $isStaff || $isAdmin);
if ($canDownload) {
	downloadFile(FILE_NAME);
} else {
	redirect(MY_DOMAIN);
}