<?php
$cleandUrl = str_replace('.', '-', $_SERVER['HTTP_HOST']);
function xcopy($source, $dest, $permissions = 0755){
	// Check for symlinks
	if (is_link($source)) {
		return symlink(readlink($source), $dest);
	}

	// Simple copy for a file
	if (is_file($source)) {
		return copy($source, $dest);
	}

	// Make destination directory
	if (!is_dir($dest)) {
		mkdir($dest, $permissions);
	}

	// Loop through the folder
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}

		// Deep copy directories
		xcopy("$source/$entry", "$dest/$entry", $permissions);
	}

	// Clean up
	$dir->close();
	return true;
}


$file_config = APPPATH . 'config/app-config-' . $cleandUrl . '.php';
if (file_exists($file_config)) {
	$install_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
	$install_url .= '://' . $_SERVER['HTTP_HOST'];
	header('Location: ' . $install_url);
	exit;
} else {
	if (!is_dir('../install-' . $cleandUrl)) {
		$resultCopy = xcopy('../bak_install', '../install-' . $cleandUrl);
	}
	$uri = str_replace('/', '', $_SERVER['REQUEST_URI']);

	if (strpos($uri, $cleandUrl) == false) {
		$install_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
		$install_url .= '://' . $_SERVER['HTTP_HOST'];
		$install_url .= '/install-' . $cleandUrl;
		header('Location: ' . $install_url);
	}
	require_once('install.class.php');
	$install = new Install();
	$install->go();
	exit;
}