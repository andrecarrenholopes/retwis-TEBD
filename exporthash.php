<?
include("retwis.php");

if (!isLoggedIn() ) {
    header("Location:index.php");
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

$r = redisLink();
$hashtag = gt("hashtag");
$hashtagList = $r->lrange("hashtag:$hashtag",0, -1);
if (count($hashtagList) == 0) {
	header("Location: export.php");
	exit;
}
ob_start();
$df = fopen("php://output", 'w');
fputcsv($df,array("UserId", "Time", "Body"));
foreach ($hashtagList as $row) {
	$post = $r->hgetall("post:$row");
	fputcsv($df, array($post["user_id"],date('m/d/Y H:i:s',$post["time"]),$post["body"]));
}
fclose($df);
echo ob_get_clean();

//header("Location: export.php");
?>
