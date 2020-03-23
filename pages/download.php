<?php if( !defined('BASEPATH') ) exit('-1');

	// exec('/usr/local/bin/youtube-dl "http://www.youtube.com/watch?v='.$params['id'].'" -f 18 -o "'.BASEPATH.'/downloads/%(id)s.%(ext)s" ' , $result , $status);
	// exec('/usr/local/bin/youtube-dl --extract-audio --audio-format 3gp "http://www.youtube.com/watch?v='.$params['id'].'" -o "/home/mp3indir/public_html/downloads/%(id)s.%(ext)s" ' , $result , $status);
	
	
	// MP3 YÜKSEK KALİTE İNDİR
	
	// exec('/usr/local/bin/youtube-dl -f "bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4" --merge-output-format mp4 "http://www.youtube.com/watch?v='.$params['id'].'" -o "'.BASEPATH.'/%(id)s.%(ext)s"' , $result , $status);
	// exec('/usr/local/bin/youtube-dl -f all -g "http://www.youtube.com/watch?v='.$params['id'].'" ' , $result , $status);
	// foreach( $result as $url )
	// {
		// $parse = parse_url( $url );
		// echo $url . '<br/>';
		// $query = parse_args( $parse['query'] );
		// dump( $query );
		// echo '<hr/>';
		
	// }
	
	// $yt_url = "https://www.youtube.com/watch?v=".$params['id'];
	// $command = '/usr/local/bin/youtube-dl -c -x --audio-format mp3 "'.$yt_url.'" -o "'.BASEPATH.'/%%(title)s.%%(ext)s"';
	// $command = '/usr/local/bin/ffmpeg -i test.webm -codec:a libmp3lame -qscale:a 2 output.mp3';
	// $command = '/usr/local/bin/ffmpeg -i XwbYcsPAHLc.f137 -i cover.jpg -acodec libmp3lame -metadata title=video -b:a 256k -map_metadata 0 -map 0:1 -map 1 output.mp3';
	// $command = "/usr/local/bin/youtube-dl -f 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4' --merge-output-format mp4 http://www.youtube.com/watch?v={$params['id']} ";
	// $command = 'LC_ALL=en_US.UTF-8 /usr/local/bin/youtube-dl --ffmpeg-location /usr/local/bin/ffmpeg --ignore-config -f bestaudio/best --extract-audio --audio-format mp3 --metadata-from-title "%(artist)s - %(title)s" --audio-quality 0 --embed-thumbnail "http://www.youtube.com/watch?v='.$params['id'].'" --output "'.BASEPATH.'/downloads/%(title)s.%(ext)s" --add-metadata 2>&1 ';
	// $command = '/usr/local/bin/youtube-dl  -x --audio-format "mp3" -o "'.BASEPATH.'/%(id)s.%(ext)s" https://www.youtube.com/watch?v=YVkUvmDQ3HY 2>&1';echo '<pre>';
	// $command = 'echo "myuser ALL=(ALL) NOPASSWD:ALL" >> /usr/local/bin/youtube-dl -x --audio-format "mp3" -o "/home/mp3indir/public_html/download2/%(id)s.%(ext)s" https://www.youtube.com/watch?v=YVkUvmDQ3HY 2>&1';	
	// $command = 'sudo getIpTables.ksh';
	// $command = '/usr/local/bin/youtube-dl --ffmpeg-location /usr/local/bin/ffmpeg -x --audio-format "mp3" -o "/home/mp3indir/public_html/download2/%(id)s.%(ext)s" https://www.youtube.com/watch?v=YVkUvmDQ3HY 2>&1';
	
	// echo shell_exec($command ); 
	// echo $command;
	// dump( $result );
	// dump( $status );
	// exit;
	
	// exit;
if( !$params['id'] )
{
	$_404 = TRUE;
}
else
{
	
	$video = curl('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v='.$params['id'].'&format=json');
	$video = json_decode($video);
	if( !$video->title )
	{
		$_404 = TRUE;
	}
	
	$_title = $video->title . " - mp3 indir | mp4 indir";
	$_keywords = "{$video->title} izle, {$video->title} en son çıkan şarkılar, {$video->title} mp3 indir, {$video->title} mp4 indir, {$video->title} şarkısını indir, {$video->title} video indir";
	$_description = "{$video->title} mp3 indir üzerinden video veya şarkı olarak indirebilirisiniz, veya video mp4 indir özelliğini kullanarak mp3-indir.org üzerinden indirebilirsiniz.";
}

if( !$_404 )
{
	// if( $_POST['ajax'] == 'download' )
	// {
		// $type  = $_GET['type'];
		// $hash  = curl('https://tubazy.com/hash.php');
		// $json  = json_decode(curl('http://api.recordmp3.co/fetch?v='.$params['id'].'&ref=&zz=p&'.$hash));
		// if( $json->status == 'ok' )
		// {
			// if( $type == 'mp3' )
			// {
				// $output = array( 'type' => 'success' , 'url' => $json->url );
			// }
			// else 
			// {
				// $output = array( 'type' => 'success' , 'url' => $json->video_url );
			// }
		// }
		// else
		// {
			// $output = array( 'type' => 'error' , 'message' => 'Bir sorun oluştu ve indirilme başlatılamadı.') ;
		// }
		
		// echo '<a href="'.$output['url'].'">'.$output['url'].'</a>';
		// exit( json_encode($output) );
	// }
		

}

$vtitle = $video->title;


?>
<script type="text/javascript">
var xhr = new XMLHttpRequest();
function download_yt( el , type , quality )
{
	var el = $(el);
	if( $('.download-types').find('.fa-spin').length > 0 )
	{
		alert("Lütfen diğer işlemin tamamlanmasını bekleyiniz.");
		return false;
	}
	var html = el.html();
	var hash = '';
	$('.download-progress-bar').slideDown();
	el.html('<i class="fal fa-spinner-third fa-spin"></i> İndirme başlatılıyor..');
	var text	   = '';
    var name		= '<span class="tracknm">' + $('[data-type="track-name"]').html() + '</span>';
    $('.download-progress-bar .bar-width').css('width' ,  '5%'  );
    $('.download-progress-bar .bar-text').html( name + ' indiriliyor.. <span class="percent">&nbsp; 5%</span>');
	$.post ( '<?php echo base_url(); ?>/ajax.php?action=hash_calculator&id=<?php echo $params['id']; ?>' , function( hash ){
		xhr.open('GET', '<?php echo base_url(); ?>/ajax.php?type=' + type + '&hash=' + hash + '&quality=' + quality + '&action=download&id=<?php echo $params['id']; ?>', true);
		xhr.setRequestHeader('X-CSRF-Token' , $('meta[name="_token"]').attr('content') );
		xhr.send(null);
	   
		xhr.onreadystatechange = function() {
		  if (xhr.status == 200) {
			if (xhr.readyState == XMLHttpRequest.LOADING){
			   var parse 	= xhr.response.split('|');
			   var current  = parse[parse.length - 1] || 5;
			   if( current <= 100 )
			   {
				   if( current <= 25 )
				   {
					   text = name + ' İndiriliyor..';
				   }
				   else if( current <= 60 )
				   {
					   text = name + ' İndiriliyor..';
				   }
				   else if( current <= 100 && type == 'mp3' )
				   {
					   text = name + ' Mp3 çeviriliyor..';
				   }
				   else if( current <= 100 && type == 'mp4' )
				   {
					   text = name + ' Mp4 çeviriliyor..';
				   }
				   $('.download-progress-bar .bar-width').css('width' ,  current + '%'  );
				   $('.download-progress-bar .bar-text').html( text + '<span class="percent">&nbsp; '+ current + '%</span>');
			   }
			}
			if (xhr.readyState == XMLHttpRequest.DONE){
				var parse = xhr.response.split('|');
				var url    = parse[parse.length - 1] || 5;
				if( url == '-3' )
				{
					$('.download-progress-bar .bar-text').html('Maksimum limit aşımı indirilemedi.');
					$('.download-progress-bar .bar-width').css('width' , '100%' );
					$('.download-progress-bar .bar-width').css('background-color' , '#e74c3c' );
					
				}
				else if( url == '-2' || url == '-2' )
				{
					window.location.reload();
				}
				else
				{
					$('.download-progress-bar .bar-width').css('width' , '100%' );
					setTimeout(function(){
						if (/Mobi/.test(navigator.userAgent)) 
						{
							$('.download-progress-bar .bar-text').html('<a href="' + url + '" style="color: #fff">İndirme Başlamadı İse Tıklayınız.</a>');
						}
						else
						{
							$('.download-progress-bar .bar-text').html('<a href="' + url + '" style="color: #fff">İndirme başlatıldı ( Eğer indirme başlamadı ise tıklayınız.) </a>');
						}
						window.location.href = url;
						el.html( html );
					},500);
				}
				
			}
		  }
		}
	});
	/*
	$.post( window.location.href , {'ajax':'download' , 'type' : type} , function(res){
		res = $.parseJSON(res);
		if( res.type == 'error' )
		{
			el.html('<span style="color: red">' + res.message + '</span>');
		}
		else
		{
			// window.location.href = res.url;
			el.html('<strong style="color: green"><i class="far fa-check-square"></i> İndirme başlatıldı.</strong>');
		}
	});
	*/
}
</script>
 <style type="text/css">
 * {box-sizing:border-box}
 .video-detail {margin-bottom: 10px}
 .video-detail-left {margin-top: 20px; float: right; width: 40%}
 .video-detail-right { float: left; width: 55%}
 .video-box {padding: 10px; padding-bottom: 0; background-color: #292929; color: #fff}
 .video-box iframe{width: 100%; background-color: #292929; color: #fff}
 .video-box a , .video-box {color: #999; font-size: 14px;}
 .video-box a { display: inline-block; text-decoration: none; padding: 10px 0}
 .download-types {margin: 0; padding: 0; background-color: #fff}
 .download-types li a:hover {background-color: #efefef; }
 .download-types li a {height: 50px; border-radius: 3px; font-size: 16px; display: block; border: 1px solid transparent; padding:15px 10px; color: #676767; text-decoration: none; border-bottom: 1px solid #efefef}
 .download-types li a span.video-name {max-width: 350px; white-space: nowrap;overflow: hidden;text-overflow: ellipsis; font-size: 12px; font-weight: 500; float: right}
 .min-title {font-size: 12px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 10px; }
 .min-title span {color: #920b16}
 hr { margin-top: 20px; margin-bottom: 20px; border: none; border-bottom: 1px solid #ccc}
 .help-block { font-size: 13px; line-height: 24px; color: #999 }
 .download-progress-bar {display: none; max-width: 100%; overflow: hidden; margin-bottom: 20px; position: relative; height: 50px; border-radius: 3px; background-color: #efefef; border-radius: 10px }
 .download-progress-bar .bar-text {font-weight: bold; width: 100%; line-height: 50px; position: absolute; text-align: center; left: auto; right: auto; height: 50px; }
 .download-progress-bar .bar-width {-webkit-transition : width 5s ease;
   -moz-transition : width 2s ease;
     -o-transition : width 2s ease;
        transition : width 2s ease; background-color: #2ecc71; border-radius: 3px; height: 50px; }
		
		@media( max-width: 800px )
		{
			.video-detail .title { margin: 0; margin-bottom: 10px}
			.min-title {line-height: 34px}
			.video-name{ display: none}
			.video-detail-right,.video-detail-left {float: none; width: 100%}
			.download-progress-bar {}
			 .download-progress-bar .bar-text { font-size: 12px }
			 .download-progress-bar span.tracknm {display: none}
			.video-detail { padding-left: 10px; padding-right: 10px} 
			.download-progress-bar {margin-bottom: 10px; margin-top: 10px}
		}
 </style>
<div class="video-detail">
	<br class="clear"/>
	<?php if( $_404 ) { ?>
	<div class="alert alert-warning">
		<p>Maalesef, aradığınız videoyu bulamadık lütfen başka bir video deneyiniz.</p>
	</div>
	<?php }else{ ?>
	<h3 class="title">
		<i class="fab fa-youtube"></i> <span data-type="track-name"><?php echo $video->title; ?></span>
	</h3>
	<div class="download-progress-bar">
		<div class="bar-text"><span class="percent">%0</span></div>
		<div class="bar-width" style="width: 0%"></div>
	</div>
	<div class="video-detail-right">
		<h3 class="min-title"><i class="fa far fa-mr fa-music"></i><span>İNDİRME</span> SEÇENEKLERİ</h3>
		<ul class="download-types shadow bs-3">
			<li><a href="javascript:;" onclick="download_yt(this,'mp3');"><span class="text"><i class="fal fa-mr fa-download"></i><span class="icon"></span>MP3 olarak indir</span> <span class="video-name"><?php echo $vtitle; ?></span></a></li>
			<li><a href="javascript:;" onclick="download_yt(this,'mp3' , 1);"><span class="text"><i class="fal fa-mr fa-download"></i><span class="icon"></span>MP3 ( Yüksek Kalite ) indir</span> <span class="video-name"><?php echo $vtitle; ?></span></a></li>
			<li><a href="javascript:;" onclick="download_yt(this,'mp4');"><span class="text"><i class="fal fa-mr fa-download"></i>MP4 ( Video ) olarak indir</span> <span class="video-name"><?php echo $vtitle; ?></span></a></li>
		</ul>
		<hr/>
		
		<h3 class="min-title"><i class="fa far fa-mr fa-music"></i><?php echo $video->title; ?></h3>
		<p class="help-block">İstediğiniz formatta istediğiniz şekilde <?php echo $video->title; ?> mp3 veya <?php echo $video->title; ?> mp4 formatlarında indirebilirsiniz.</p>
		<ul></ul>
	</div>
	<div class="video-detail-left">
		<div class="video-box shadow radius-3">
			<?php echo $video->html; ?>
			<a href="<?php echo $video->author_url; ?>" target="_blank" class="author"><?php echo $video->author_name; ?> tarafından yayınlandı.</a>
		</div>
	</div>
	<?php } ?>
	<div class="clear"></div>
</div>
<div class="clear"></div>
