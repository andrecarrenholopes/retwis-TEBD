<?
include("retwis.php");

$r = redisLink();
if (!isLoggedIn() || !gt("uid") || gt("f") === false ||
    !($username = $r->hget("user:".gt("uid"),"username"))) {
    header("Location:index.php");
    exit;
}

$f = intval(gt("f"));
$uid = intval(gt("uid"));
if ($uid != $User['id']) {
    if ($f) {
        $r->zadd("Seguidores:".$uid,time(),$User['id']);
        $r->zadd("Seguindo:".$User['id'],time(),$uid);
    } else {
        $r->zrem("Seguidores:".$uid,$User['id']);
        $r->zrem("Seguindo:".$User['id'],$uid);
    }
}
header("Location: profile.php?u=".urlencode($username));
?>
