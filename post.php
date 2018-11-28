<?
include("retwis.php");

if (!isLoggedIn() || !gt("status")) {
    header("Location:index.php");
    exit;
}

$r = redisLink();
$postid = $r->incr("next_post_id");
$status = str_replace("\n"," ",gt("status"));
$r->hmset("post:$postid","user_id",$User['id'],"time",time(),"body",$status);
$followers = $r->zrange("followers:".$User['id'],0,-1);
$followers[] = $User['id']; /* Add the post to our own posts too */

foreach($followers as $fid) {
    $r->lpush("posts:$fid",$postid);
}
# Push the post on the timeline, and trim the timeline to the
# newest 1000 elements.
$r->lpush("timeline",$postid);
$r->ltrim("timeline",0,1000);
$hashtag;
# Find the hashtag and push the posts 
$pos = strpos($status, '#');
if ($pos !== false) {
	$fim = strpos($status, ' ', $pos);
	if ($fim !== false) {
		$statusLen = strlen(utf8_decode($status));
		$pos = $pos  +1;
		$hashtag = substr($status, $pos, $fim - $statusLen);
		$r->lpush("hashtags",$hashtag);
		#records the hashtag in redis
		$r->lpush("hashtag:$hashtag", $postid);
	}
}

header("Location: index.php#$hashtag");
?>
