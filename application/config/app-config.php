<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/


$servername = "localhost";
$username = "servicec_thanh";
$password = "Vbrand@t2d";
$dbname = "servicec_crm";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


// Append the host(domain name, ip) to the URL.
$url = $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
// $url.= $_SERVER['REQUEST_URI'];

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
		if ($row['domain'] != '') {
			$nameApi = str_replace('.', '_', $row['domain']);
		} else {
			$nameApi = "crm-" . $row['id'];
		}
		if (strpos($url, $nameApi) !== false) {
			$nameDbApi = $nameApi;
			$userDb = $row['databaseName'];
			$userPass = $row['databasePassword'];
			$key = $row['key'];
			break;
		}
	}
} else {
	echo "0 results";
}

$conn->close();


var_dump($userDb);die();

if ($nameDbApi == '') {
	print_r("Please registry Service before using");
	die();
}


define('APP_BASE_URL', $url);

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
define('APP_ENC_KEY', base64_encode($nameDbApi));

/**
 * Database Credentials
 * The hostname of your database server
 */
define('APP_DB_HOSTNAME', 'localhost');
/**
 * The username used to connect to the database
 */
define('APP_DB_USERNAME', $userDb);
/**
 * The password used to connect to the database
 */
define('APP_DB_PASSWORD', $userPass);
/**
 * The name of the database you want to connect to
 */

define('APP_DB_NAME', $userDb);


/**
 * @since  2.3.0
 * Database charset
 */
define('APP_DB_CHARSET', 'utf8');
/**
 * @since  2.3.0
 * Database collation
 */
define('APP_DB_COLLATION', 'utf8_general_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
define('SESS_DRIVER', 'database');
define('SESS_SAVE_PATH', 'sessions');

/**
 * Enables CSRF Protection
 */
define('APP_CSRF_PROTECTION', true);