<?php
/*
 * hiadeveloper@gmail.com
*/
ob_start();
set_time_limit(0);
session_start();
define( 'BASEPATH' , dirname(__FILE__));

error_reporting(E_ALL &~ E_NOTICE);

require BASEPATH . '/config.php';
require BASEPATH . '/includes/functions.php';
require BASEPATH . '/includes/class.router.php';


$_GET = clean($_GET);
$_POST = clean($_POST);



/**
 * --------------------------------------------------------------------
 * Dinamik ve Değiştirilebilir yönlendirmeler ;
 * --------------------------------------------------------------------
*/
$RT = new AltoRouter();
$RT->setBasePath( $_config['base_url'] );
$RT->map('/' , 'index' , 'index' );
$RT->map('/arama/' , 'search' , 'search' );
$RT->map('/populer-turkce-sarkilar.html' , 'populer-turkce-sarkilar' , 'populer-turkce-sarkilar' );
$RT->map('/populer-yabanci-sarkilar.html' , 'populer-yabanci-sarkilar' , 'populer-yabanci-sarkilar' );
$RT->map('/indir/[*:id]/[*:url].html' , 'download' , 'download' );

if( !$_SESSION['csrf'] )
{
	$_SESSION['csrf'] = md5( 'btu_' . uniqid() . microtime() . get_ip() ) ;
}

session_write_close();