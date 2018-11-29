<?
include("retwis.php");


if (!isLoggedIn()) {
    header("Location:dindex.php");
    exit;
}

if (isLoggedIn() && $User['id'] != gt("userid")) {
header("Location: ".gt("userid")."foi.php");
}
else {
	$r = redisLink();
	$postid = gt("postid");
	$r = redisLink();
	$r->del("post:$postid");
}
?>
<?
header("Location: index.php");
?>
