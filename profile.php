<?
include("retwis.php");
include("header.php");

$r = redisLink();
if (!gt("u") || !($userid = $r->hget("users",gt("u")))) {
    header("Location: index.php");
    exit(1);
}
echo("<div class=\"container-photo\"><img class=\"profilephoto\" src=\"https://conteudo.imguol.com.br/blogs/3/files/2014/06/ret_neymar.png\"></div>");
echo("<h2 class=\"username\">".utf8entities(gt("u"))."</h2>");
if (isLoggedIn() && $User['id'] != $userid) {
    $isfollowing = $r->zscore("following:".$User['id'],$userid);
    if (!$isfollowing) {
        echo("<a href=\"follow.php?uid=$userid&f=1\" class=\"button\">Follow this user</a>");
    } else {
        echo("<a href=\"follow.php?uid=$userid&f=0\" class=\"button\">Stop following</a>");
    }
}
else {
	echo("<a href=\"#\" class=\"button\">Editar perfil </a>");
}
?>
<?
$start = gt("start") === false ? 0 : intval(gt("start"));
showUserPostsWithPagination(gt("u"),$userid,$start,10);
include("footer.php")
?>
