<?php

require_once ("fbCredentials.php");


require 'lib/facebook.php';
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret,
  'cookie' => true
));
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    echo $e;
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(	'scope' =>'user_photos'));
}

// This call will always work since we are fetching public data.
if($user){
try{
//	$user_info	= $facebook->api('/' . $user);
//	$friends_list	= $facebook->api('/' . $user . '/friends');
	//$photos		= $facebook->api('/' . $user . '/photos');
	$album_id		= $facebook->api('/me/albums');
	$alength=count($album_id['data']);
	echo $alength;
//			$album_id = $_REQUEST['album_id'];
			//$access_token=$facebook->getAccessToken();
              //$photos = ''; 
            //  $photos = $facebook->api("/album_id/photos", array("access_token" => "$access_token"));

}
catch(FacebookApiException $e){
	error_log($e);
}
}
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
	  div {
	  	display:inline;
	  }
    </style>
  </head>
  <body>
    <h1>php-sdk</h1>
	<?php 
	if($user)
	{

              /*$photo = '';
			  foreach($photos['data'] as $photo)
			 	{
			  		echo "<img src=".$photo['source']." width='90px' height='70px' border=2 margin=5px />";
				}
				print_r ($user_info);*/
				
				for ($i=0;$i<=$alength;$i++)
				{ 
				 echo "<div><table><tr><td>";
					$album_id1=$facebook->api($album_id['data'][$i]['id'].'/photos');
					echo "<img src=".$album_id1['data'][0]['source']." width='200px' height='200px' border=2 margin=5px /> </td></tr><tr><td>";
					echo  $ab_id['name'] ."</td></tr><table></div>";
								//		print_r ($album_id);
				}
              
//		print_r ($photos['data']);
		 ?>
		 <a href="<?php echo $logoutUrl; ?>">Logout</a>
	<?php
	}
	else ?>
		 <a href="<?php echo $loginUrl; ?>">Login</a><?php
	?>
  </body>
</html>
