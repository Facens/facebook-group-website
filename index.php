<?php

require "facebook/facebook.php";

// Facebook

	function link_it($text)  
	{  
	    $text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $text);  
	    $text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $text);  
	    $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $text);  
	    return($text);  
	}


	$appId = ''; //appid from facebook
	$secret = ''; //secrete from facebook
	$app_id = $appId;
	$app_secret = $secret;
	$groupId = ''; //facebook groupid
	$my_url = 'http://www.groupwebsite.com/';
	
	$facebook = new Facebook(array(
	'appId' => $appId,
	'secret' => $secret,
	'cookie' => true,
	));


// Meno testo grazie - Jany

function troncatesto($string, $limit, $break=".", $pad="...") {
if(strlen($string) <= $limit) return $string;
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	} return $string;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>ISS - Italian Startup Scene, Facebook Group</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />
	<link rel="alternate" href="http://it.startupscene.org/rss.xml" title="RSS Feed - Italian Startup Scene" type="application/rss+xml" />
	 
<!--[if IE]>
<style type="text/css">
  .clearfix {
    zoom: 1;     /* triggers hasLayout */
    }  /* Only IE can see inside the conditional comment
    and read this CSS rule. Don't ever use a normal HTML
    comment inside the CC or it will close prematurely. */
</style>
<![endif]-->

<!-- Roba di FB -->
<div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '<?php echo $facebook->getAppId(); ?>',
            status     : true, 
            cookie     : true,
            xfbml      : true
          });
		/*FB.login({
        scope: 'email,user_groups,user_likes,user_online_presence,user_website,user_about_me,offline_access,user_location,read_stream,publish_stream',
      });
FB.Event.subscribe('auth.login', function() {
          window.location.reload();
        });*/
        (function(d){
           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           d.getElementsByTagName('head')[0].appendChild(js);
         }(document));
};
      </script>

</head>

<body>

<div id="wrapper">

<?php
// Get User ID / Session - Jany
$user = $facebook->getUser();
$loginUrl = $facebook->getLoginUrl();
$logoutUrl = $facebook->getLogoutUrl();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
 	// Login or logout url will be needed depending on current user state.
/*if ($user) {  

	if( array_key_exists('publish_stream', $permissions['data'][0]) ) {
	// Permission is granted!
	// Do the related task
	$post_id = $facebook->api('/me/feed', 'post', array('message'=>'Hello World!'));
} else {
	// We don't have the permission
	// Alert the user or ask for the permission!
	header( "Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream")) );
}
} else {
  
  echo '<div class="fb-login-button" data-show-faces="true" data-max-rows="1" perms="email,user_groups,user_likes,user_online_presence,user_website,user_about_me,offline_access,user_location,read_stream,publish_stream"></div>';
}*/
?>


	<?php if ($cookie || $user) { ?>
	<p>Il tuo ID Facebook &egrave; <?= $cookie['uid']; ?>. Se lo vedi vuol dire che va tutto bene :)</p>
	<a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php  } else { ?>
      <div class="fb-login-button" data-show-faces="true" data-max-rows="1" perms="email,user_groups,user_likes,user_online_presence,user_website,user_about_me,offline_access,user_location,read_stream,publish_stream"></div>
    <?php } ?>

	<div id="container">
		<div id="main">

			<div class="social">   
			<a href="http://it.startupscene.org/rss.xml" target="_blank"><img src="img/rss.png" alt="RSS" />
			<a href="https://www.facebook.com/groups/italianstartups/" target="_blank"><img src="img/facebook.png" alt="ISS su Facebook" /></a>
			</div>

	<?php

	// Date - Jany

		// Formattazione data con X tempo fa - Jany

		function ShowDate($date) // $date -- time(); value
		{
		$stf = 0;
		$cur_time = time();
		$diff = $cur_time - $date;
		$phrase = array('second','minute','hour','day','week','month','year','decade');
		$length = array(1,60,3600,86400,604800,2630880,31570560,315705600);
		for($i = sizeof($length)-1; ($i >= 0)&&(($no = $diff/$length[$i])<= 1); $i--); if($i < 0) $i = 0; $_time = $cur_time -($diff%$length[$i]);
		$no = floor($no); if($no <> 1) $phrase[$i] .='s'; $value=sprintf("%d %s ",$no,$phrase[$i]);
		if(($stf == 1)&&($i >= 1)&&(($cur_tm-$_time) > 0)) $value .= time_ago($_time);
		return $value.' ago';
		} 

		function ShowReplyDate($reply_date) // $date -- time(); value
		{
		$stf = 0;
		$cur_time = time();
		$diff = $cur_time - $reply_date;
		$phrase = array('second','minute','hour','day','week','month','year','decade');
		$length = array(1,60,3600,86400,604800,2630880,31570560,315705600);
		for($i = sizeof($length)-1; ($i >= 0)&&(($no = $diff/$length[$i])<= 1); $i--); if($i < 0) $i = 0; $_time = $cur_time -($diff%$length[$i]);
		$no = floor($no); if($no <> 1) $phrase[$i] .='s'; $value=sprintf("%d %s ",$no,$phrase[$i]);
		if(($stf == 1)&&($i >= 1)&&(($cur_tm-$_time) > 0)) $reply_value .= time_ago($_time);
		return $reply_value.' ago';
		} 

	print "<div id=\"header\">";
	

	// Qualche dettaglio in più sul group - Jany

	$group_stuff = $facebook->api('/'.$groupId.'/', array('fields'=>'icon,owner,name,description'));
	print "<img class=\"rounded logo\" src=\"img/logo.png\"/>";
	print "<h1><a href=\"/\">".$group_stuff['name']."</a></h1>";
	//print "<h2>".$group_stuff['description']."</h2>";
	print "<h2>Gruppo di ritrovo degli startupper italiani. Imprenditori, investitori, bloggers, sviluppatori e chiunque abbia un interesse per startups e venture capital. </h2>";

	print "<hr class=\"clearfix\" />";
	print "</div><!-- End #header-->";


	// prova a pubblicare direttamente da sta pag - Jany

	if($_POST['testo'])
	{

	$facebook->api('/'.$groupId.'/feed', 'post', array('message'=>''.$_POST['testo'].''));
	echo 'Post Pubblicato!';

	}
	else
	{
	?>

	<div class="post invia_post" style="display:none;">
		<?php if ($user): ?><img class="rounded avatar avatar-main" src="https://graph.facebook.com/<?php echo $user; ?>/picture"><?php endif; ?>
		<div class="message">
		<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
		<textarea name="testo"></textarea>
		<input name="Invia" value="Invia" type="submit" class="button" />
		</form>
		</div> <!-- end .message -->
	</div>

	<?php
	}

	// I post - Edits ovunque di Jany :D - Edits di Andrea: riorganizzazione/ottimizzazione lato HTML/CSS

	$response = $facebook->api('/'.$groupId.'/feed', array('limit' => 10, 'fields'=>'id,from,message,created_time,likes,comments,picture,link,description,updated_time')); 

	// Inizia il retrieve dei post
	
	foreach ($response['data'] as $value) {
	$id = explode('_',$value['id']);
	print "
	<div class=\"post\"><a name='".$id[1]."'></a><img class=\"rounded avatar avatar-main\" src=\"http://graph.facebook.com/".$value['from']['id']."/picture\" /><div class=\"message\"><h4><a href=\"http://www.facebook.com/profile.php?id=".$value['from']['id']."\">".$value['from']['name']."</a> ha scritto:</h4>";

	print "<p>".link_it($value['message'])."</p>";
	
	
	// formattiamo le date delle risp con X tempo fa - Jany
	$date = strtotime($value['created_time']);

	print "<small>Updated ".ShowDate($date)."</small>"; //

	print "</div> <!-- end .message -->";
	
	// Andrea: box che mostra numero likes
	print "<div class=\"message-box like-box\"><img src=\"img/like.png\" />";

	if ($value['likes']) {
		print "<span>".$value['likes']['count']."</span>\nlikes"; // corretto il bug del numero di likes se più di 0 - Jany
		}
	else {
		// da togliere se superfluo
		print "<span>0</span>\nlikes";

	}
	print "</div> <!-- end .message-box -->";

	// Andrea: box che mostra numero commenti
	print "<div class=\"message-box comment-box\"><img src=\"img/comments.png\" />";
	if ($value['comments']['count']) {
		print "<span>".$value['comments']['count']."</span>\ncommenti";
		}
	else {
		// da togliere se superfluo
		print "<span>0</span>\ncommenti";

	}
	print "</div> <!-- end .message-box -->";

	// Andrea: box che mostra il permalink a FB
	$id = explode('_',$value['id']);
	print "<div class=\"message-box view-box\"><img src=\"img/fbicon.png\" /><a href=\"https://www.facebook.com/groups/italianstartups/".$id[1]."/\" target=\"_blank\">Visualizza su Facebook</a></div><!-- end .message-box -->";
	
	// Clearfix per evitare problemi di allineamento
	print "<hr class=\"clearfix\" />";
	
	// Ci sono attached?
	if ($value['link']) {
		print "<div class='attached'><a href='".$value['link']."'>";
		if ($value['picture']){
			print "<img class=\"rounded\" src='".$value['picture']."' />";
			}
			if ($value['description']) {
		print $value['description']."<br />";
			}
		print "<small>".$value['link']."</small></a><hr class=\"clearfix\" /></div>";

		// crea l'xml dell'rss nel frattempo - Jany

		$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
		$rssfeed .= '<rss version="2.0">';
		$rssfeed .= '<channel>';
		$rssfeed .= '<title>ISS - Italian Startup Scene</title>';
		$rssfeed .= '<link>http://it.startupscene.org/</link>';
		$rssfeed .= '<description>Feed del Gruppo Italian Startup Scene su Facebook</description>';
		$rssfeed .= '<language>it-it</language>';
		$rssfeed .= '<copyright>Copyright (C) 2011-2012 startupscene.org</copyright>';

		foreach ($response['data'] as $value) {
		
		$testolungo = $value['message'];
		$testotroncato = troncatesto($testolungo, 300);
		$datapub = strtotime($value['created_time']);
		$datagg = strtotime($value['updated_time']);
		$id = explode('_',$value['id']);

		$rssfeed .= '<item>';
        $rssfeed .= '<title>'.$value['from']['name'].' - aggiornato il '.date('d M Y H:i',$datagg) .'</title>';
        $rssfeed .= '<description>'.$testotroncato.'</description>';
        //$rssfeed .= '<link>' . $value['link'] . '</link>';
		$rssfeed .= '<link>http://it.startupscene.org/#'.$id[1].'</link>';
        $rssfeed .= '<pubDate>' . date('D, m F Y H:i:s T',$datapub) . '</pubDate>';
        $rssfeed .= '</item>';
    }
 
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
 
    //echo $rssfeed;

	$handle = fopen("rss.xml", "c");
	fwrite($handle, $rssfeed);
	fclose($handle);

} // fine foreach del retrieve dei post
	
		// Lista likes e commenti
	?>
	
		<div class="indent">
	
	<?php		//Likes
		if ($value['likes']) {
		print "<div class=\"inner-block\"><h4 class=\"inner-left\">".$value['likes']['count']." likes</h4>";
		print "<ul class=\"blocco_likes\">";

		// SE likes > 0 : mostra chi ha fatto like al post - Jany

		$id = explode('_',$value['id']);
		$likes = $facebook->api('/'.$groupId.'_'.$id[1].'/likes', array('fields'=>'id,name'));
		foreach ($likes['data'] as $like) {
		print "<li><a href='http://www.facebook.com/profile.php?id=".$like['id']."'><img class=\"rounded\" src='http://graph.facebook.com/".$like['id']."/picture' title='".$like['name']."' /></a></li>";
		} 
		print "</ul>";

		?>
		</div>
		<hr />
	<?php }	// fine IF likes > 0

		//Commenti

		if ($value['comments']['count']) {
		print "<div class=\"inner-block\">";
			print "<h4 class=\"inner-left\">".$value['comments']['count']." commenti</h4>";

		print "<button class=\"btn-slide button\">Mostra i commenti</button>";
		print "<ul class=\"commenti\">";
	
		// SE commenti > 0 : mostra commenti al post - Jany

		$comments = $facebook->api('/'.$value['id'].'/comments');// array('limit' => 10));
		//$comments = $facebook->api('/'.$groupId.'_'.$id[1].'/comments', array('limit' => 10, 'fields'=>'id,from,message,created_time'));
		
		foreach ($comments['data'] as $comment) {
		print "<li><img class=\"avatar rounded\" src='http://graph.facebook.com/".$comment['from']['id']."/picture'>";
		print "<div><h4><a href='http://www.facebook.com/profile.php?id".$comment['from']['id']."'>".$comment['from']['name']."</a> ha risposto:</h4>";
		print "<p>".$comment['message']."</p>";

		// formattiamo le date delle risp con X tempo fa - Jany
		$date = strtotime($comment['created_time']);
		print "<small>".ShowDate($date)."</small></div></li>";

		} ?>
		
		<?php
			print "<li><img class=\"avatar\" src=\"img/fbicon.png\" /><div><h4><a href='https://www.facebook.com/groups/italianstartups/".$id[1]."/' target='_blank'>Visualizza il post su Facebook</a></h4></div></li>";
		?>
		</ul>
		</div>
<?php } // fine IF comments > 0
	$id = explode('_',$value['id']);
	 ?>
	
				<?php 
				// prova a rispondere direttamente dal post - Jany - IN DEV

				if($_POST['testo_commento'])
				{

				$facebook->api('/'.$groupId.'/feed/'.$id[1].'', 'post', array('message'=>''.$_POST['testo_commento'].''));
				echo 'Commento Pubblicato!';

				}
				else
				{
				?>

				<div class="invia_commento" style="display:none;">
					<?php if ($user): ?><img class="rounded avatar avatar-main" src="https://graph.facebook.com/<?php echo $user; ?>/picture"><?php endif; ?>
					<div class="message">
					<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
					<textarea name="testo_commento"></textarea>
					<input name="Invia" value="Invia" type="submit" class="button" />
					</form>
					</div> <!-- end .message -->
				</div>

				<?php } ?>
				
				</div> <!-- end of .indent -->

				<hr class="clearfix" />
			</div> <!-- end of .post -->
			<?php }	?>


		</div> <!-- end of main -->
   
		<div class="footer">
			<p>Dalla collaborazione di <a href="http://www.facebook.com/stefano" target="_blank">Stefano Bernardi</a>, <a href="http://www.facebook.com/jany.martelli" target="_blank">Jany Martelli</a>, <a href="http://www.facebook.com/andrea.giannangelo" target="_blank">Andrea Giannangelo</a>, <a href="http://www.facebook.com/fabio.lalli" target="_blank">Fabio Lalli</a> e tutto il gruppo <a href="http://www.facebook.com/home.php?sk=group_163895500288173" target="_blank">Italian Startup Scene</a> :)</p>
		</div>

	</div> <!-- end of container-->

	<!-- Barra laterale -->
	<div class="sidebar_dx">
		
		<div class="doc_box">
			<h3><a href="" target="_blank">I docs di ISS</a></h3>
			<?php // e ora anche i docs! - Jany
			$docresponse = $facebook->api('/'.$groupId.'/docs', array('limit' => 10, 'fields'=>'id,subject,from,message,updated_time'));
			foreach ($docresponse['data'] as $docvalue) {
			$docdatagg = strtotime($docvalue['updated_time']);
			?>
			<div class="doc_item">
				<h4><a href="https://www.facebook.com/groups/italianstartups/doc/<?php echo $docvalue['id']; ?>/" target="_blank"><?php echo $docvalue['subject']; ?></a></h4>
				<!-- <span>di <?php //echo $docvalue['from']['name']; ?></span> -->
				<small>agg. il <?php echo date('j M Y',$docdatagg); ?></small>
			</div>
			<?php }
			?>

		</div>
		<!-- Fine Barra Laterale -->

	<!-- Feedback - Jany -->
		<div id="feedback">
			<div class="section">
			   <h6><span class="arrow up"></span>Mandaci un Feedback!</h6>
				<p class="message"></p>
				<textarea></textarea>
				<a class="submit" href="">Invia</a>
			</div>
		</div> 

	</div> <!-- end of sidebar_dx -->

</div> <!-- end of wrapper-->

<!-- Tutti i JS in fondo grazie! - Jany -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.js"></script>
<script type="text/javascript" src="script/feedback.js"></script>

<!-- jquery per far vedere i commenti -->
<script>
$(document).ready(function(){

	$(".btn-slide").click(function(){
		if (!($(this).next().is(':visible'))){
			$(this).next().slideDown();
			$(this).text('Nascondi i commenti');
		} else {
			$(this).next().slideUp();
			$(this).text('Mostra i commenti');
		}
	});

});
</script>

</body>
</html>