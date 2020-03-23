<?php
require 'loader.php';

ob_start();

if( $_GET['suggestqueries'] == '1' )
{
	$term = $_GET['term'];
	if( $term )
	{
		$url = curl('http://suggestqueries.google.com/complete/search?client=firefox&ds=yt&q='.urlencode($term) );
		$url = json_decode($url);
		$ckey = 'search_'.generate_url($term);
		if( $cache = get_cache( $ckey , 3600 *24 ) )
		{
			echo $cache;
		}
		else
		{
			if( is_array( $url[1] ) && count( $url[1] ) && $url[1][0] )
			{
				echo '<ul>';
				foreach( $url[1] as $q )
				{
					echo '<li><a href="'.home_url('search').'?q=' . $q.'">'.$q.'</a></li>';
				}
				echo '</ul>';
			}
			set_cache( $ckey , ob_get_contents() );
		}
	}
	
	exit;
}
$match  = $RT->match();
$params = $match['params'];

if( $match )
{
	include BASEPATH . '/pages/'.$match['target'].'.php';
}

$title = $_title ? $_title : $_config['title'];
$keywords = $keywords ? $keywords : $_config['keywords'];
$description = $description ? $description : $_config['description'];

// $do = $_GET['do'];
// $q  = $_GET['q'];
// if( file_exists( BASEPATH . '/pages/'.$do.'.php' ) )
// {
	// include BASEPATH . '/pages/'.$do.'.php';
// }
// else
// {
	// include BASEPATH . '/pages/index.php';
// }

$PAGE_CONTENT = ob_get_clean();

if( $_GET['btu_ajax_page_load'] == '1' )
{
	echo $PAGE_CONTENT;
	exit;
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="<?php echo $keywords; ?>"/>
	<meta name="description" content="<?php echo $description; ?>"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/css/font-awesome/css/fontawesome-all.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/css/flags/flags.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/css/style.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<style type="text/css">* {outline: 0},a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}table{border-collapse:collapse;border-spacing:0}
	.container { max-width: 1200px; margin: 0 auto }
	body {font-family: roboto}
	#header {background: #2d2c2c; height: 60px}
	#header .logo {color: rgba(255,255,255,.9); min-width: 250px; text-decoration: none; font-size: 25px; line-height: 55px; display: inline-block}
	span.red {font-weight: 500; color: #ff0000}
	#header .logo:hover {color: #fff}
	.ps-relative { position: relative }
	.ps-absolute { position: absolute}
	button {background:transparent}
	* {box-sizing: border-box}
	.fa-mr {margin-right: 5px}
	.radius-3 {-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;}
	.form-control{ border-radius: 3px; outline: 0}
	.form-control:focus {background: rgba(255,255,255,.05);}
	.form-control::placeholder {}
	.search-form-wrapper { display: inline-block; width: 500px}
	.search-form-wrapper input {font-size: 15px; padding:6px 10px; color: #fff; width: 100%; background-color:rgba(255,255,255,.1); height: 40px}
	.search-form-wrapper .submit-btn {cursor: pointer; outline: 0; height: 30px; width: 30px;top: 5px; right: 2px; color: #fff}
	.search-form-wrapper input::placeholder {color: rgba(255,255,255,0.4); font-weight: 300}
	.notice {background: #920b16; color: rgba(255,255,255,0.9); height: 40px; line-height: 40px}
	.clear {clear: both}
	.video-downloader {padding: 20px; background:#f6f0e9 url('<?php echo base_url(); ?>/img/search-bg.jpeg') no-repeat center center fixed; -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;
  background-size: cover;; height: 275px }
	.shadow {-webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.41);
-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.41);
box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.41);}
	.converter-form h3 {width: 100%; left: 25px; position: relative; font-weight: 300; font-size: 17px; color: #f1f1f1; margin-bottom: 20px; background-color: #444; padding: 10px; border-radius: 3px  }
	.converter-form {left: -25px;  max-width: 750px; margin: 48px auto; text-align: center;}
	.converter-form input.form-control {border-radius: 3px 0 0 3px; height: 60px; background-color: white;   display: block; font-size: 18px; margin: 0 auto 9px; padding: 9px 9px 10px 15px; width: 100%;}
	.converter-form input:focus {border-top-color: #2d2c2c}
	.converter-form button {width: 62px; text-align:center; cursor: pointer; border-radius: 0 3px 3px 0; font-weight: 500; padding: 15px 20px; top: 0; right: -50px; background-color: #2d2c2c; color: #fff; height: 60px}
	.topmenu {float: right; list-style-type: none; margin-top: 13px}
	.topmenu li {position: relative; display: inline-block; margin-left: 5px}
	.topmenu li a {background-color: rgba(255,255,255,0.04); border-radius: 3px; font-size: 14px; font-weight: 400; color: rgba(255,255,255,0.5); text-decoration: none; display: block; padding: 10px 15px}
	.topmenu li a:hover {opacity: 0.9}
	.topmenu li a .far {margin-right: 5px}
	.topmenu .flag { float: left; margin-right: 5px; position: relative; top: -5px}
	
	.video-list {margin-left: -20px; margin-right: -20px}
	.video-list li {border-radius: 3px; height: 216px; position: relative; margin: 10px; width: 23.3%; float: left;}
	.video-list li img {width: 100%; height: 216px; border-radius: 3px; max-width: 100%}
	.video-list li a{display: block; text-align: center}
	.video-list li a::before {position: absolute;  bottom: 0;
    width: 100%;
    left: 0;
    content: "";
    z-index: 99;
    height: 60%;
	border-radius: 3px;
    background: -moz-linear-gradient(top,transparent 0,rgba(0,0,0,.55) 54%,#000 98%);
    background: -webkit-linear-gradient(top,transparent 0,rgba(0,0,0,.55) 54%,#000 98%);
    background: linear-gradient(to bottom,transparent 0,rgba(0,0,0,.55) 54%,#000 98%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#000000', GradientType=0 );
    transition: all .3s ease;}
	.video-list li h3.video-title {font-size: 15x; line-height: 24px; text-align: center; padding: 10px; display: block; position: absolute; bottom: 0; right:0; left: 0; z-index: 9999; color: #fff}
	.video-list li .duration { position: absolute; top: 10px; right: 10px; letter-spacing: 0.5px; background-color: rgba(0,0,0,0.8); color: #fff; border-radius: 3px; font-size: 16px; font-weight: 300; padding: 2px 10px}
	h3 span.slug { position: relative; top:-2px; font-size: 15px; color: #999; font-weight: 400;  margin-top: 10px}
	.pull-right { float: right}
	h3.title {font-size: 30px; margin-top: 20px; font-weight: 300; margin-bottom: 20px}
	.alert {font-size: 30px; padding:15px; background-color: #F6F0E9; color: #d08b3b; border-radius: 3px; -webkit-box-shadow: 1px 1px 1px 0px rgba(0,0,0,0.34);
-moz-box-shadow: 1px 1px 1px 0px rgba(0,0,0,0.34);
box-shadow: 1px 1px 1px 0px rgba(0,0,0,0.34);}
	.alert h3 { color: #333; margin: 0; padding: 0; margin-bottom: 15px; font-size: 17px}
	.alert p {font-size: 15px; margin-top: 10px; color: #444; font-weight: 500; margin-bottom: 10px}
	.show-mobile {display: none}
	@media( max-width: 800px )
	{
		#header .container {padding:0 10px}
		.hide-mobile{display: none}
		.show-mobile {display: block}
		.video-downloader.shadow {padding: 5px; height: 80px}
		.converter-form  {left: auto; margin: 10px}
		h3.title { font-size: 17px; margin: 10px; margin-bottom: 5px; display: block}
		h3.title .slug { display: block; padding: 10px; text-align: center; font-size: 12px; float: none; margin: 5px auto}
		.converter-form button {right: 0; }
		.converter-form h3 {display: none; padding: 2px 5px; font-size: 12px; line-height: 24px; left: auto; margin-bottom: 5px}
		.video-list {margin: 10px}
		.converter-form input.form-control , .converter-form button {font-size: 14px; height: 45px}
		.video-list li {float: none; margin: 0px; margin-bottom: 10px; background: #000; width: 100% }
	}
	@media( max-width: 320px )
	{
		h3.title .slug {font-size: 10px}
	}
	</style>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>/js/jquery-2.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>/js/lazyload.js"></script>
	<script type="text/javascript">
	var ajaxRequest = new XMLHttpRequest();
	var is_mobile   = false;
	var base_url	= '<?php echo base_url(); ?>';
	$(function() {
		lazyload();
	});

	$(document).on('click' , function(){
		$('.auto-suggestion').html('')
	});
	// function BTU_ajax_page_load( href )
	// {
		// var output		= $('div[data-type="btu-ajax-output"]');
		// output.find('*').css('opacity' , '0.6');
		// $.get( href , {'btu_ajax_page_load' : 1} , function( res ) {
			// output.removeAttr('style');
			// output.html( res );
			// var page = $('[data-info]').data();
			// window.history.pushState({urlPath: page.url || href }, page.title || document.title , href);
		// } );
	// }
	

	$(document).on('submit' , 'form.yt-query-form' , function(){
		window.location.href = '<?php echo home_url('search'); ?>?q=' + $(this).find('[name="q"]').val();
		return false;
	});
	
	
	
	function delay(callback, ms) {
	  var timer = 0;
	  return function() {
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function () {
		  callback.apply(context, args);
		}, ms || 0);
	  };
	}


	$(function(){
		$('.yt-query-form input[name="q"]').keyup(delay(function (e) {
			var val = $.trim($(this).val());
			if( !val ) return false;
			$.get( '?suggestqueries=1&term=' + val , function(res){
				if( res )
				{
					$('.auto-suggestion').html( res ).show();
				}
				else
				{
					$('.auto-suggestion').hide();
				}
			});
		}, 200));
	});

	// function yt_query_form()
	// {
		// var output	= $('[data-type="btu-ajax-output"]');
		// var text = jQuery('[name="q"]').val();
		// var form = $('form.yt-query-form');
		// $('.auto-suggestion').html('').hide();
		// var q 	 = $.trim(form.find('[name="q"]').val());
		// output.find('*').css('opacity' , '0.5');
		// ajaxRequest.open("GET", "<?php echo home_url('search'); ?>?btu_ajax_page_load=1&q=" + q, true);
		// ajaxRequest.onreadystatechange = function() {
			// if (this.readyState == 4 && this.status == 200) {
				// output.removeAttr('style');
				// output.html( this.responseText );
				// var page = $('[data-info]').data();
				// window.history.pushState({urlPath: page.url || window.location.href }, page.title || document.title , page.url);
				// $('.auto-suggestion').html('').hide();
			// }
		// };
		// ajaxRequest.send(null);
		// return false;
	// }
	
	// xhttp.onreadystatechange = function() {
	// if (this.readyState == 4 && this.status == 200) {
	  // document.getElementById("demo").innerHTML =
	  // this.responseText;
	// }
	// };
	// xhttp.open("GET", "ajax_info.txt", true);
	// xhttp.send();
	
	// $(document).on('click' , '[data-ajax]' , function(){
		// var el = $(this) , href = el.attr('href') , is_disabled = el.hasClass('disabled');
		// if( !href || is_disabled ) return false;
		// BTU_ajax_page_load(href);
		// return false;
	// });
	
	</script>
</head>
<body>
	<div id="header">
		<div class="container">
			<a href="<?php echo home_url(); ?>" class="logo"><span class="red">mp3</span>-indir.org</a>
			<?php
			/*
			<div class="search-form-wrapper ps-relative">
				<form action="" class="search-form">
					<input type="text" placeholder="<?php _e('Video arayın veya Youtube video adresini buraya yapıştırın.'); ?>" name="" class="form-control"/>
					<button type="submit" class="ps-absolute submit-btn"><i class="fal fa-search"></i></button>
				</form>
			</div>
			*/
			?>
			<ul class="topmenu hide-mobile">
				<li><a href="<?php echo home_url(); ?>" data-ajax><i class="far fa-home"></i>Anasayfa</a></li>
				<li><a href="<?php echo home_url('populer-yabanci-sarkilar'); ?>" data-ajax><i class="far fa-music"></i>Popüler Yabancı Şarkılar</a></li>
				<li><a href="<?php echo home_url('populer-turkce-sarkilar'); ?>" data-ajax><i class="far fa-music"></i>Popüler Türkçe Şarkılar</a></li>
				<li><a href="mailto:hiadeveloper@gmail.com" data-ajax><i class="far fa-envelope"></i>İletişim</a></li>
				<li><a href="<?php echo home_url(); ?>" data-ajax><i class="flag flag-tr"/></i> Türkçe <i class="far fa-angle-down"></i></a></li>
			</ul>
		</div>
	</div>
	<div class="notice hide-mobile">
		<div class="container">
			<i class="far fa-bullhorn fa-mr"></i>
			YouTube mp3 dönüştürücü ne işe yarıyor? Bu websitesi nasıl kullanılır?.
		</div>
	</div>
	<style type="text/css">
	.auto-suggestion {width: 100%; left: 0; position: absolute; background: #fff; border-radius: 3px; z-index: 99999; }
	.auto-suggestion ul {max-height: 250px; overflow: auto}
	.auto-suggestion ul li a {display: block; color: #444; text-align: left; padding: 15px 10px; text-decoration: none}
	.auto-suggestion ul li a:hover {border-radius: 3px; background-color: #efefef}
	</style>
	<div class="clear"></div>
	<div class="video-downloader shadow">
		<div class="container">
			<div class="converter-form">
				<h3 class="slug radius shadow"><i class="fal fa-download fa-mr"></i>İndirmek istediğiniz videonun bağlantı adresini yapıştırınız veya şarkı / sanatçı adını giriniz.</h3>
				<form action="" autocomplete="off" class="yt-query-form shadow ps-relative" method="post">
					<input type="text" name="q" autofocus="" required="" class="form-control" placeholder="Sanatçı adı şarkı veya bağlantı adresini giriniz."/>
					<button class="ps-absolute"><i class="far fa-search fa-mr"></i></button>
					<div class="auto-suggestion shadow"></div>
				</form>
			</div>
		</div>
	</div>
	<div class="container btu-ajax-output" data-type="btu-ajax-output">
		<?php echo $PAGE_CONTENT; ?>
	</div>
</body>
</html>