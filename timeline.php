<?
include("retwis.php");
include("header.php");
?>
<h2>Timeline</h2>
<i>Últimos usuários registrados (exemplo de sorted sets)</i><br>
<?
showLastUsers();
?>
<i>Últimas 50 mensagens dos usuários do mundo todo!</i><br>
<?
showUserPosts(-1,0,50);
include("footer.php")
?>
