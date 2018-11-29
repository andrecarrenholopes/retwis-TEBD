<?
include("retwis.php");
include("header.php");
?>
<div id="welcomebox">
<div id="registerbox">
	<h2>Exporte nossa base!</h2>
	<b>Quer exportar nossos Tuites? </br>
	Digite aqui em baixo a hashtag!</b>
	</br></br>
	<form method="POST" action="exporthash.php">
		<table>
			<tr>
			  <td>Hashtag</td><td><input type="text" name="hashtag"></td>
			</tr>
			<tr>
			<td colspan="2" align="right"><input type="submit" name="doit" value="Buscar Hashtag"></td></tr>
		</table>
	</form>
</div>
Bem vindo a área para exportar nossos Tuites.
<p>

<ul>
<li>Em parceria com o grupo responsavel por criar rankings sobre a emoção de Twittes nós criamos está página. Basta digitar a <strong>#Hashtag</strong> desejada aqui do lado e todos os Tuites e suas informações serão exportadas em um CSV para você fazer o download <p></li>
<pre>


<ul>
<li>Andre Carrenho Lopes 9065681
<li>Fernando Fujimoto Inoue 9004421
</ul>
</pre>


</div>
<?
$start = gt("start") === false ? 0 : intval(gt("start"));
$hashtag = str_replace('#', '', utf8entities(gt("search")));
showHashtagPosts($hashtag,$start,10);
include("footer.php")
?>
