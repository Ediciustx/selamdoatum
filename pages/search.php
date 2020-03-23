<?php if( !defined('BASEPATH') ) exit('-1');

$q 	= $_GET['q'];
$_title = $q ? 'Arama Sonuçları &raquo; '.$q : 'Aramanız ile ilgili bir sonuç bulunamadı.';

// $parse = end(explode('v=' , parse_url( $q , PHP_URL_QUERY )));
// if( $parse && trim( $parse ) )
// {
	// echo( home_url('download' , array('id' => $parse , 'url' => 'download') ) );
	// exit;
// }
$ckey   = 'searchresults_'.generate_url($q);
if( $cache = get_cache( $ckey , 3600 ) )
{
	$videos = json_decode( $cache , TRUE );
}
else
{
	$query = curl('https://www.youtube.com/results?search_query='.urlencode($q));
	preg_match_all('#div class="yt-lockup-content">(.*?)</div>#is' , $query,  $match);

	for( $i = 0; $i < count($match[0]); $i++ )
	{
		$data = $match[0][$i];
		if( !stristr( $data , 'liste') )
		{
			preg_match( '@\?v=(.*?)"@is' , $data , $vidid );
			preg_match( '#title="(.*?)"#is' , $data , $title);
			preg_match( '#Süre: (.*?)"#is' , $data , $duration);
			if( trim($vidid[1]) && trim($title[1]) )
			{
				$videos[] = array
				(
					'title'=> $title[1],
					'src' => 'https://i.ytimg.com/vi/'.$vidid[1].'/hqdefault.jpg',
					'duration' => str_replace('.' ,'',$duration[1]),
					'id'	=> $vidid[1],
				);
			}
		}
	}
	
	set_cache( $ckey , json_encode( $videos ) );
}
?>
<div class="video-results">
	<h3 class="title">
		<i class="fab fa-youtube"></i> Arama Sonuçları &raquo; <span class="red" data-type="query"><?php echo $q; ?></span>
		<span class="slug pull-right"><i class="fa fa-mr fa-download"></i>Videolara tıklayarak mp3 veya mp4 olarak indirebilirsiniz.</span>
	</h3>
	<?php if( count( $videos ) > 0 ) { ?>
	<ul class="video-list">
		
		<?php foreach( $videos as $video ) { ?>
		<li>
			<a href="<?php echo home_url('download' , array('id' => $video['id'] , 'url' => generate_url($video['title']) ) ); ?>">
				<img class="lazyload" src="<?php echo $video['src']; ?>" alt=""/>
				<span class="duration" title="<?php echo $video['duration_str']; ?>"><i class="fal fa-clock fa-mr"></i><?php echo $video['duration']; ?></span>
				<h3 class="video-title"><?php echo $video['title']; ?></h3>
			</a>
		</li>
		<?php }?>
	</ul>
	<?php }else{ ?>
	<div class="alert alert-warning">
		<p><?php echo $q; ?> ile ilgili aramanız ile hiç bir sonuç bulamadık.</p>
	</div>
	<?php } ?>
</div>
<div class="clear"></div>