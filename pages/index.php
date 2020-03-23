<?php if( !defined('BASEPATH') ) exit('-1');

$_title = 'Popüler Türkçe Şarkılar';

if( $cache = get_cache('popular_videos' , 3600 ) )
{
	$videos = json_decode( $cache , TRUE );
}
else
{	
	ob_start();
	$popular = curl('https://www.youtube.com/playlist?list=PL4EUOoHe2ClOmv0bSFUXRQK_QL_zCI-T5');
	$videos  = array();
	preg_match_all( '#<tr class="pl-video(.*?)" data-title="(.*?)"(.*?)>(.*?)</tr>#is' , $popular , $match );
	// dump( $match );
	for( $i = 0; $i < count( $match[0] ); $i++ )
	{
		$data = $match[0][$i];
		if( stristr( $data , 'gizli' ) ) continue;
		preg_match('#data-title="(.*?)"#is' , $data , $title);
		preg_match('#src="(.*?)"#is' , $data , $src);
		preg_match('#watch\?v=(.*?)&#is' , $data , $vidid);
		preg_match('#<div class="timestamp"><span aria-label="(.*?)">(.*?)</span>#is' , $data , $duration);
		
		$videos[] = array
		(
			'title' 	   => $title[1],
			'src'		   => 'https://i.ytimg.com/vi/'.$vidid[1].'/hqdefault.jpg',
			'id'		   => $vidid[1],
			'duration_str' => $duration[1],
			'duration' 	   => end($duration),
			
		);
	}
	
	set_cache( 'popular_videos' , json_encode( $videos ) );
}

 ?>
<div data-info data-url="<?php echo home_url(); ?>" data-title="<?php echo $_config['title']; ?>" data-keywords="<?php echo $_config['keywords']; ?>" data-description="<?php echo $_config['description']; ?>"></div>
<div class="video-results">
	<h3 class="title">
		<i class="fab fa-youtube"></i> <span class="red">Youtube</span>'da Popüler Müzikler
		<span class="slug pull-right"><i class="fa fa-mr fa-download"></i>Videolara tıklayarak mp3 veya mp4 olarak indirebilirsiniz.</span>
	</h3>
	<ul class="video-list">
		<?php foreach( $videos as $video ) { ?>
		<li>
			<a href="<?php echo home_url( 'download' , array('id' => $video['id'], 'url' => generate_url($video['title'] ) ) ); ?>" data-ajax="">
				<img class="lazyload" src="<?php echo base_url('img/1x1.png'); ?>" data-src="<?php echo $video['src']; ?>" alt="<?php echo $video['title']; ?>"/>
				<span class="duration" title="<?php echo $video['duration_str']; ?>"><i class="fal fa-clock fa-mr"></i><?php echo $video['duration']; ?></span>
				<h3 class="video-title"><?php echo $video['title']; ?></h3>
			</a>
		</li>
		<?php }?>
	</ul>
</div>
<div class="clear"></div>