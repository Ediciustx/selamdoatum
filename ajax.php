<?php
require 'loader.php';

$headers = getallheaders();
if( $headers['X-Csrf-Token'] != $_SESSION['csrf'] )
{
	// exit('-1');
}
switch( $_GET['action'] )
{
	case 'hash_calculator':
		$ref = $_SERVER['HTTP_REFERER'];
		if( strstr( $ref , $_SERVER['HTTP_HOST'] ) )
		{
			$hash = md5(get_ip() . $_SERVER['HTTP_USER_AGENT']. $_GET['id'] . $_SESSION['csrf']);
			echo $hash;
		}
	break;
	
	case 'download':
		ob_end_flush();
		
		$id	  = $_GET['id'];
		$type = $_GET['type'];
		// $quality = $_GET['quality'] > 0 ? '2' : 4;
		
		$hash = md5(get_ip() . $_SERVER['HTTP_USER_AGENT']. $id . $_SESSION['csrf']);
		if( $hash != $_GET['hash'] )
		{
			// exit('-2');
		}
		
		$path = BASEPATH . '/log/'.$id . '_'.$type.'.txt';
		if( file_exists( $path ) )
		{
			$destination = file_get_contents( $path );
			$i = 3;
			
			echo '|'.$i;
			for( $s = 0; $s < 12; $s++ )
			{
				if( $i < 95 )
				{
					$i+= rand(3,17);
				}
				if( $i > 100 )
				{
					$i = 95;
				}
				
				ob_flush(); flush();
				echo '|'.$i;
				sleep( rand(1,2) );
			}
		}
		else
		{
			if( $type == 'mp4' )
			{
				$command = 'LC_ALL=en_US.UTF-8 /usr/local/bin/youtube-dl --ffmpeg-location /usr/local/bin/ffmpeg --max-filesize 100m --ignore-config -f "bestvideo[filesize<100M][ext=mp4]+bestaudio[ext=m4a]/mp4" --merge-output-format mp4 "http://www.youtube.com/watch?v='.$id.'" -o "'.BASEPATH.'/downloads/%(title)s___%(id)s___.%(ext)s"';
			}
			else
			{
				$command = 'LC_ALL=en_US.UTF-8 /usr/local/bin/youtube-dl --ffmpeg-location /usr/local/bin/ffmpeg --max-filesize 15m --ignore-config -f bestaudio/best --extract-audio --audio-format mp3 --metadata-from-title "%(artist)s - %(title)s" --audio-quality 4 --embed-thumbnail "http://www.youtube.com/watch?v='.$id.'" --output "'.BASEPATH.'/downloads/%(title)s___%(id)s___.%(ext)s" --add-metadata 2>&1 ';
			}

			$query = popen($command, "r");
			$i     = 3;
			echo '|'.$i;
			$log = '';
			while($read = fgets($query, 2048)) 
			{ 
				if( $i < 95 )
				{
					$i+= rand(3,17);
				}
				if( $i > 100 )
				{
					$i = 95;
				}
				
				ob_flush(); flush();
				echo '|'.$i;
				sleep( rand(1,2) );
				
				$log.= $read . "\n";
			}
			pclose($query); 
			
			$destination = null;
			if( $type == 'mp4' )
			{
				preg_match('#/downloads/(.*?)\.mp4\"#i' , $log , $mp4);
				if( strstr( $mp4[0] , '.mp4' ) )
				{
					$destination = str_replace(array('/downloads/','"') , '' , $mp4[0]);
				}
			}
			else
			{
				preg_match('#/downloads/(.*?)\.mp3#i' , $log , $mp3);
				
				if( strstr( $mp3[0] , '.mp3' ) )
				{
					$destination = str_replace('/downloads/' , '' , $mp3[0]);
				}
			}
			
			if( $destination )
			{
				$save_log = true;
			}
		}
		
		if( $destination )
		{
			if( $save_log )
			{
				file_put_contents( BASEPATH.'/log/'.$id.'_'.$type.'.txt' , $destination);
				file_put_contents( BASEPATH.'/btu_convert.txt' , '['.strtoupper($type).'] DATE:'.date('d.m.Y H:i').', IP:'.get_ip().' ID:'.$id.' URL=https://www.youtube.com/watch?v='.$id ."\n" , FILE_APPEND);
			}
			session_start();
			$_SESSION['safe_download'] = md5( $id . $type . $hash . $destination );
			echo '|'.base_url('download.php?type='.$type.'&file='.urlencode($destination).'&hash='.$hash.'&token='.$_SESSION['csrf'].'&ip='.get_ip().'&checksum='.$_SESSION['safe_download']);
			
			session_write_close();
		}
		else
		{
			echo '|-3';
		}
	break;
}
?>