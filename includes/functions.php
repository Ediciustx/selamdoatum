<?php

function base_url($url = '' , $data = array() )
{
	global $_config , $RT;
	$base = rtrim($_config['base_url'],'/');
	
	if( $url && $RT->namedRoutes[$url] )
    {
        return $RT->generate( $url , $data );
    }
	
	return ( $url ? $base . ltrim( '/' . $url ) : $base );
}

function home_url( $url = '' , $data = array() )
{
	return base_url( $url , $data);
}

function clean($data = array())
{
	if( is_array($data) )
	{
		$data = array_map('clean' , $data);
	}
	else
	{
		$data = addslashes(stripslashes(htmlspecialchars(htmlspecialchars_decode(($data))))); 
	}
	
	return $data;
}
function get_ip()
{
	return $_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
}
function write_log($text = NULL , $name = 'log' )
{
	if( defined('WRITE_LOG') && WRITE_LOG == TRUE )
	{
		$path = INCPATH . '/logs/'.$name.'.php';
		if( !file_exists( $path ) )
		{
			file_put_contents( $path , "<?php if(!defined('BASEPATH') ) exit('-1'); ?>\n" );
		}
		
		$current_url = current_url();
		$filename 	 = pathinfo($current_url , PATHINFO_FILENAME);
		if( $_GET['action'] == 'update_wait_actions' && $filename == 'admin-ajax' )
		{
			return '';
		}
		
		// if( Cookie::get('__user_last_url') == md5( $current_url ) )
		// {
			// echo 'EVET AYNI URL';
		// }
		// else
		// {
			// Cookie::set('__user_last_url' , md5( $current_url ) , time() + 5 );
		// }
		if( is_null( $text )  )
		{
			$agent  = parse_user_agent();
			
			global $_admin , $userdata;
			$append = '';
			if( $_admin || $userdata )
			{
				$user = $_admin ? $_admin : $userdata;
				$append.= "USER_ID:{$user->id} NAME:".($user->name ? $user->name : $user->username);
			}
			
			if( $_POST )
			{
				$postdata = $_POST;
				if( $postdata )
				{
					foreach( $postdata as $key => $val )
					{
						if( strstr( $key , 'password') || strstr( $key , 'pass' ) || strstr( $key , 'code') )
						{
							$length = strlen($val);
							$postdata[$key] = str_repeat('*' , $length > 15 ? $length : 15);
						}
					}
				}
				$append.= " POSTDATA:".http_build_query( $postdata);
			}
			
			$output = "[".date('d.m.Y H:i:s')."] ".($_POST ? 'POST' : 'GET ')." ".get_ip()." URL:".$current_url." {$append} {$agent['platform']} {$agent['browser']} ({$agent['version']})";
		}
		else
		{
			$output = "[".date('d.m.Y H:i:s')."] " .$text;
		}
	
		if( file_exists( $path ) && $output )
		{
			$filetime = filemtime( $path );
			if( true && $_GET['_t'] == '1' )
			{
				$filetime = strtotime('-30 day');
			}
			$timeout  = round(( time() - $filetime ) / 86400);
			if( $timeout >= 30 )
			{
				if( rename( $path , INCPATH . '/logs/'.$name.'-30DAY.php' )  )
				{
					@unlink( $path );
				}
			}
			
			if( !file_exists( $path ) )
			{
				file_put_contents( $path , "<?php if(!defined('BASEPATH') ) exit('-1'); ?>\n" );
			}
			
			file_put_contents( $path , $output."\n" , FILE_APPEND );
		}
	}
}

function text_limit( $text = NULL , $length = 255 , $suffix = '..' , $charset = 'UTF-8' )
{
	$text = html_entity_decode(decode_html( $text ));
	$text = strip_tags( $text );
	$text = trim( $text );
	if( strlen($text) > $length )
	{
		$text = mb_substr( $text , 0 , $length , $charset) . $suffix;
	}
	
	return $text;
}

function escape( $data = array() )
{
	if( is_array($data) )
	{
		$data = array_map('escape' , $data);
	}
	else
	{
		$data = addslashes(stripslashes($data)); 
	}
	return $data;
}

function decode_html( $html )
{
	$html = strip_tags($html , '<script>');
	return htmlspecialchars_decode( $html );
}

function generate_url($name = NULL)
{
    $tr = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
    $en = array("G","U","S","I","O","C","g","u","s","i","o","c");
	$name = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$name);
	$name = preg_replace($tr,$en,$name);
	$name = preg_replace("/ +/"," ",$name);
	$name = preg_replace("/ /","-",$name);
	$name = preg_replace("/\s/","",$name);
	$name = strtolower($name);
	$name = preg_replace("/^-/","",$name);
	$name = preg_replace("/-$/","",$name);
	$name = trim($name);
	$name = preg_replace("/[^a-zA-Z0-9 _-]/", "", $name);
	return $name;
}
function dump( $var )
{
	echo '<pre>'.print_r( $var , TRUE).'</pre>';
}

function parse_args( $args = '' , $defaults = '' , $clean = TRUE )
{
	if( is_object( $args ) )
	{
		$r = get_object_vars( $args );
	}
	else if( is_array( $args ) )
	{
		$r =& $args;
	}
	else
	{
		parse_str ( $args , $r );
	}
	
	// if( $clean == TRUE )
	// {
		// $r 		  = clean($r);
		// $defaults = clean($defaults);
	// }
	
	if( is_array( $defaults ) )
	{
		return array_merge( $defaults , $r );
	}
	
	return $r;
}



function get_cache( $filename = NULL , $timeout = 0 )
{
	if( !$_GET['nocache'] )
	{
		ob_start();
		$name = is_null( $filename ) ? md5( current_url() ) : $filename;
		$path = ( BASEPATH . '/cache/' . $name . '-cache.php' );
		$time = $timeout > 0 ? $timeout : $ctime;
		if( is_file( $path ) && time() - $time < filemtime($path))
		{
			include $path;
			$content = ob_get_clean();
			return $content;
		}
	}
	
	return FALSE;
}


function set_cache( $filename = NULL , $content = NULL , $is_write = 0)
{
	if( !defined('DEVMODE') || $is_write == 1 )
	{
		$name = is_null( $filename ) ? md5( current_url() ) : $filename ;
		$path = ( BASEPATH . '/cache/' . $name . '-cache.php' );
		$content = is_null( $content ) ? ob_get_contents() : $content;
		file_put_contents( $path , $content );
		ob_end_flush();
	}
}


function curl($url , $options = array())
{
	$defaults = array('timeout' => 10 , 'connect_timeout' => 10 , 'max_timeout' => 15);
	$r = parse_args( $options , $defaults );
	$options = array( 
		CURLOPT_RETURNTRANSFER 	=> true,
		CURLOPT_HEADER 			=> false,
		CURLOPT_ENCODING 		=> "",
		CURLOPT_AUTOREFERER 	=> true,
		CURLOPT_CONNECTTIMEOUT 	=> $r['connect_timeout'],
		CURLOPT_TIMEOUT 		=> $r['max_timeout'],
		CURLOPT_MAXREDIRS 		=> 10,
		CURLOPT_SSL_VERIFYPEER 	=> false,
		CURLOPT_FOLLOWLOCATION 	=> true,
		CURLOPT_USERAGENT		=> 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
	);
	
	if( isset( $r['post'] ) )
	{
		$options[CURLOPT_POST]  	 = TRUE;
		$options[CURLOPT_POSTFIELDS] = $r['post'];
	}
	$ch = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	

	curl_close( $ch );
	return $content;
}