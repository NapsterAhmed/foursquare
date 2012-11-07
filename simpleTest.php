<?php
ob_start();
require_once 'EpiCurl.php';
require_once 'EpiFoursquare.php';
$clientId = ' JHM2SJMTUUHAIU15AEAPZNDXJKNKQOJTTNF3KBTTOOSLVALJ';
$clientSecret = 'N40P33YOPPHZMHZRWGLAPGA1YGVUGGV51VIWYZ4XQXKB1IM3';
$code = 'IXLU2UL1Q1GGQ2EIKIRSSVURZQ4BQMRSFZKT14DZQ3UPQCFG';
$accessToken = '1QGNV01LKEK1L00TPQ52JCR4V1OGZZESKMNDKEFQKF0B3XC5';
$redirectUri = 'http://vedohost.com/fs/simpleTest.php';
$userId = '40922561';
$fsObj = new EpiFoursquare($clientId, $clientSecret, $accessToken);
$fsObjUnAuth = new EpiFoursquare($clientId, $clientSecret);
?>
<script type="text/javascript">
function viewSource() {
	document.getElementById("source").style.display = "block";
}
</script>





<?php



 if(!isset($_GET['code']) && !isset($_COOKIE['access_token'])) { ?>
<h2>Generate the authorization link</h2>
<?php $authorizeUrl = $fsObjUnAuth->getAuthorizeUrl($redirectUri); ?>
<a href="<?php echo $authorizeUrl; ?>"><?php echo $authorizeUrl; ?></a>

<?php } else { ?>
	<h2>Display your own badges</h2>
	<?php
	if(!isset($_COOKIE['access_token'])) {
		$token = $fsObjUnAuth->getAccessToken($_GET['code'], $redirectUri);
		setcookie('access_token', $token->access_token);
		$_COOKIE['access_token'] = $token->access_token;
	}
	$fsObjUnAuth->setAccessToken($_COOKIE['access_token']);
	$badges = $fsObjUnAuth->get('/users/self/badges');

	// Process the returned object and display the badge images					
	if (is_object($badges->response)) {
		foreach ($badges->response->badges as $badge) {		
			echo "<img src=\"".$badge->image->prefix.$badge->image->sizes->{1}.$badge->image->name."\" title=\"".$badge->name."\" />";
		}
	}
	?>
	<div style="height: 400px; overflow: auto; width: 100%; border: 2px solid #ccc;">
		<pre><?php var_dump($badges->response); ?></pre>
	</div>
<?php } ?>

<hr>

<h2>Get a test user's checkins</h2>
<?php
$offset=0;
$limit = 250;
do {
foreach($fsObj->get("/users/{$userId}/checkins") as $creds) {
	var_dump($creds->response);
}
  $offset += $limit;
} while(count($fsObj->get("/users/{$userId}/checkins") >= $limit));
?>