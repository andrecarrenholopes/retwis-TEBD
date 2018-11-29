<?
include("retwis.php");

# Form sanity checks
if (!gt("username") || !gt("password"))
    goback("Faltou preencher as paradas ai bro.");

# The form is ok, check if the username is available
$username = gt("username");
$password = gt("password");
$r = redisLink();
$userid = $r->hget("users",$username);
if (!$userid)
    goback("ERRROOOOOUUUUUU usuario ou senha");
$realpassword = $r->hget("user:$userid","password");
if ($realpassword != $password)
    goback("ERRROOOOOUUUUUU usuario ou senha");

# Username / password OK, set the cookie and redirect to index.php
$authsecret = $r->hget("user:$userid","auth");
setcookie("auth",$authsecret,time()+3600*24*365);
header("Location: index.php");
?>
