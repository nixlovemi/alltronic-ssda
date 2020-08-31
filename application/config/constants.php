<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  || define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') || define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   || define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  || define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           || define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     || define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       || define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  || define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   || define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              || define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            || define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       || define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/* CONSTANTS */
if (!defined('SITE_NAME')) { define('SITE_NAME', 'SSDA'); }
if (!defined('BASE_URL')) { define('BASE_URL', url()); }
if (!defined('SITE_LOGO')) { define('SITE_LOGO', BASE_URL . '_template/img/logo/logo.png'); }

function url(){
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF']; 

    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
    $pathInfo = pathinfo($currentPath); 

    // output: localhost
    $hostName = $_SERVER['HTTP_HOST']; 

    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

    // return: http://localhost/myproject/
    return $protocol.'://'.$hostName."/alltronic-ssda/";
}
function soBasePath() {
	return FCPATH;
}
function echoHeader() {
	$rand = date('YmdHis');
	$html = '
		<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link href="'.SITE_LOGO.'?r='.$rand.'" rel="icon" />
		<link href="'.BASE_URL.'_template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="'.BASE_URL.'_template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="'.BASE_URL.'_template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="'.BASE_URL.'_template/css/ruang-admin.min.css" rel="stylesheet" />
		<link href="'.BASE_URL.'_template/css/custom.css" rel="stylesheet" />
	';
	echo $html;
}
function echoFooter() {
	$html = '
		<script src="'.BASE_URL.'_template/vendor/jquery/jquery.min.js"></script>
        <script src="'.BASE_URL.'_template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="'.BASE_URL.'_template/vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="'.BASE_URL.'_template/js/ruang-admin.min.js"></script>
        <script src="'.BASE_URL.'_template/vendor/chart.js/Chart.min.js"></script>
		<script src="'.BASE_URL.'_template/vendor/datatables/jquery.dataTables.min.js"></script>
		<script src="'.BASE_URL.'_template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <!--<script src="'.BASE_URL.'_template/js/demo/chart-area-demo.js"></script>-->
		<script src="'.BASE_URL.'_template/js/custom.js"></script>
	';

    $html .= requestSessionLists($_REQUEST);

	echo $html;
}
function requestSessionLists($request) {
    $vTableId     = $request['hddnTableId'] ?? NULL;
    $vFilterValue = $request['hddnTableFilter'] ?? NULL;

    $jsonTableIds = $_SESSION['JSON_TABLE_IDS'] ?? '{}';
    $arrTableIds  = json_decode($jsonTableIds, true);
    if($vTableId !== NULL && $vFilterValue !== NULL) {
        $arrTableIds[$vTableId] = $vFilterValue;
    }

    $jsonTableIds               = json_encode($arrTableIds);
    $_SESSION['JSON_TABLE_IDS'] = $jsonTableIds;

    $CI =& get_instance();
    $CI->session->mark_as_temp('JSON_TABLE_IDS', 600);

    return "<input type='hidden' id='HDDN_JSON_TABLE_IDS' value='$jsonTableIds' />";
}

require_once(soBasePath() . 'application/libraries/TableEntity.php');
require_once(soBasePath() . 'application/libraries/ReturnLib.php');
/* ========= */
