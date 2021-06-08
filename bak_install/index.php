<?php

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


$file_config = APPPATH . 'config/app-config-' . str_replace('.', '-', $_SERVER['HTTP_HOST']) . '.php';
if (file_exists($file_config)) {
	$install_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
	$install_url .= '://' . $_SERVER['HTTP_HOST'];
	$install_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	header('Location: ' . $install_url);
	exit;
} else {
	if (!is_dir('../install-' . str_replace('.', '-', $_SERVER['HTTP_HOST']))) {
		$resultCopy = xcopy('../bak_install', '../install-' . str_replace('.', '-', $_SERVER['HTTP_HOST']));
		if ($resultCopy) {
			$install_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
			$install_url .= '://' . $_SERVER['HTTP_HOST'];
			$install_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			$install_url .= 'install-' . str_replace('.', '-', $_SERVER['HTTP_HOST']);
			header('Location: ' . $install_url);
		}
	} else {
		$servername = "localhost";
		$username = "servicec_thanh";
		$password = "Vbrand@t2d";
		$dbname = "servicec_crm";
		try {
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed Please contact Administrator");
			}
		} catch (Exception $ex) {
			die("Connection failed Please contact Administrator");
		}

		$url = $_SERVER['HTTP_HOST'];
		$domain_map = array();

		$sql = "SELECT * FROM service";
		$result = $conn->query($sql);
		$nameDbApi = '';
		$userDb = '';
		$key = '';
		$userPass = '';
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$nameApi = '';
				$name_compare = '';
				if ($row['domain'] != '') {
					$nameApi = str_replace('.', '-', $row['domain']);
					$name_compare = $row['domain'];
				} else {
					$nameApi = "crm-" . $row['id'];
					$name_compare = $nameApi;
				}

				if (strpos($url, $name_compare) !== false) {
					$nameDbApi = $nameApi;
					$userDb = $row['databaseName'];
					$userPass = $row['databasePassword'];
					$key = $row['key'];
					break;
				}
			}
		} else {
			print_r("Please registry Service before using");
			exit;
		}

		$conn->close();

		if ($nameDbApi == '') {
			print_r("Please registry Service before using");
			exit;
		}

		require_once('install.class.php');
		$install = new Install();
		$install->go();
	}
	exit;
}