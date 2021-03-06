<?
require 'Predis/Autoloader.php';
Predis\Autoloader::register();

function getrand() {
    //$fd = fopen("/dev/urandom","r");
    //$data = fread($fd,16);
    //fclose($fd);
	$data = random_bytes(16);
    return md5($data);
}

function isLoggedIn() {
    global $User, $_COOKIE;

    if (isset($User)) return true;

    if (isset($_COOKIE['auth'])) {
        $r = redisLink();
        $authcookie = $_COOKIE['auth'];
        if ($userid = $r->hget("auths",$authcookie)) {
            if ($r->hget("user:$userid","auth") != $authcookie) return false;
            loadUserInfo($userid);
            return true;
        }
    }
    return false;
}

function loadUserInfo($userid) {
    global $User;

    $r = redisLink();
    $User['id'] = $userid;
    $User['username'] = $r->hget("user:$userid","username");
    return true;
}

function redisLink() {
    static $r = false;

    if ($r) return $r;
    $r = new Predis\Client();
    return $r;
}

# Access to GET/POST/COOKIE parameters the easy way
function g($param) {
    global $_GET, $_POST, $_COOKIE;

    if (isset($_COOKIE[$param])) return $_COOKIE[$param];
    if (isset($_POST[$param])) return $_POST[$param];
    if (isset($_GET[$param])) return $_GET[$param];
    return false;
}

function gt($param) {
    $val = g($param);
    if ($val === false) return false;
    return trim($val);
}

function utf8entities($s) {
    return htmlentities($s,ENT_COMPAT,'UTF-8');
}

function goback($msg) {
    include("header.php");
    echo('<div id ="error">'.utf8entities($msg).'<br>');
    echo('<a href="javascript:history.back()">Please return back and try again</a></div>');
    include("footer.php");
    exit;
}

function strElapsed($t) {
    $d = time()-$t;
    if ($d < 60) return "$d seconds";
    if ($d < 3600) {
        $m = (int)($d/60);
        return "$m minute".($m > 1 ? "s" : "");
    }
    if ($d < 3600*24) {
        $h = (int)($d/3600);
        return "$h hour".($h > 1 ? "s" : "");
    }
    $d = (int)($d/(3600*24));
    return "$d day".($d > 1 ? "s" : "");
}

function showPost($id,$useridorigin) {
    $r = redisLink();
    $post = $r->hgetall("post:$id");
    if (empty($post)) return false;

    $userid = $post['user_id'];
    $username = $r->hget("user:$userid","username");
    $elapsed = strElapsed($post['time']);
    $userlink = "<a class=\"username\" href=\"profile.php?u=".urlencode($username)."\">".utf8entities($username)."</a>";

	//hashtag
	$body = $post['body'];
	$pos = strpos($body, '#');
	if ($pos !== false) {
		$body = preg_replace('/(\A|\s)#(\w+)/', ' <a class="hashtag" href="search.php?search=$2">$0</a> ', $body);
	}
	
    echo('<div class="post">'.$userlink.' '.$body."<br>");
	global $User;
	
	//echo $User['id'];
	//Delete
	if($User['id'] == $useridorigin) {
		//echo ('<div class="option"><a href="deletepost.php?postid='.$id.'" class="button button-apagar">Apagar post</a></div>');
		echo ('<form method="POST" action="deletepost.php">	<input type="hidden" value="'.$useridorigin.'" name="userid"><input type="hidden" value="'.$id.'" name="postid"><input type="submit" name="doit" value="Apagar" class="button-apagar"></form>');
	}
    echo('<i>posted '.$elapsed.' ago via web</i></div>');
    return true;
}

function showUserPosts($userid,$start,$count) {
    $r = redisLink();
    $key = ($userid == -1) ? "timeline" : "posts:$userid";
    $posts = $r->lrange($key,$start,$start+$count);
    $c = 0;
    foreach($posts as $p) {
        if (showPost($p,$userid)) $c++;
        if ($c == $count) break;
    }
    return count($posts) == $count+1;
}

function showUserPostsWithPagination($username,$userid,$start,$count) {
    global $_SERVER;
    $thispage = $_SERVER['PHP_SELF'];

    $navlink = "";
    $next = $start+10;
    $prev = $start-10;
    $nextlink = $prevlink = false;
    if ($prev < 0) $prev = 0;

    $u = $username ? "&u=".urlencode($username) : "";
    if (showUserPosts($userid,$start,$count))
        $nextlink = "<a href=\"$thispage?start=$next".$u."\">Older posts &raquo;</a>";
    if ($start > 0) {
        $prevlink = "<a href=\"$thispage?start=$prev".$u."\">&laquo; Newer posts</a>".($nextlink ? " | " : "");
    }
    if ($nextlink || $prevlink)
        echo("<div class=\"rightlink\">$prevlink $nextlink</div>");
}

function showHashtagPostsWithPagination($username,$userid,$start,$count) {
	global $_SERVER;
    $thispage = $_SERVER['PHP_SELF'];

    $navlink = "";
    $next = $start+10;
    $prev = $start-10;
    $nextlink = $prevlink = false;
    if ($prev < 0) $prev = 0;
	
	$u = $username ? "&u=".urlencode($username) : "";
    if (showHashtagPosts($userid,$start,$count))
        $nextlink = "<a href=\"$thispage?start=$next".$u."\">Older posts &raquo;</a>";
    if ($start > 0) {
        $prevlink = "<a href=\"$thispage?start=$prev".$u."\">&laquo; Newer posts</a>".($nextlink ? " | " : "");
    }
    if ($nextlink || $prevlink)
        echo("<div class=\"rightlink\">$prevlink $nextlink</div>");
}

function showHashtagPosts($hashtag,$start,$count) {
    $r = redisLink();
    //$key = ($userid == -1) ? "timeline" : "posts:$userid";
    $posts = $r->lrange("hashtag:$hashtag",$start,$start+$count);
    $c = 0;
    foreach($posts as $p) {
        if (showPost($p,-1)) $c++;
        if ($c == $count) break;
    }
    return count($posts) == $count+1;
}

function showLastUsers() {
    $r = redisLink();
    $users = $r->zrevrange("users_by_time",0,9);
    echo("<div>");
    foreach($users as $u) {
        echo("<a class=\"username\" href=\"profile.php?u=".urlencode($u)."\">".utf8entities($u)."</a> ");
    }
    echo("</div><br>");
}

?>
