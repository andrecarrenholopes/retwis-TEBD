<div id="welcomebox">
<div id="registerbox">
<h2>Registre-se!</h2>
<b>Quer testar o Twitter Pistola? Crie uma conta!</b>
<form method="POST" action="register.php">
<table>
<tr>
  <td>Usuário</td><td><input type="text" name="username"></td>
</tr>
<tr>
  <td>Senha</td><td><input type="password" name="password"></td>
</tr>
<tr>
  <td>Senha (de novo)</td><td><input type="password" name="password2"></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="submit" name="doit" value="Criar Conta"></td></tr>
</table>
</form>
<h2>Já tem registro? Login</h2>
<form method="POST" action="login.php">
<table><tr>
  <td>Usuário</td><td><input type="text" name="username"></td>
  </tr><tr>
  <td>Senha</td><td><input type="password" name="password"></td>
  </tr><tr>
  <td colspan="2" align="right"><input type="submit" name="doit" value="Login"></td>
</tr></table>
</form>
</div>
Olá! O Twitter Pistola é um clone muito simples do <a href="http://twitter.com">Twitter</a>, como uma demonstração para o banco de dados de valor-chave Redis. Pontos chave:
<ul>
<li>O Redis é um banco de dados de valor-chave e é o único banco de dados usado por este aplicativo, não é um MySQL ou semelhante.</li>
<li>Esse aplicativo pode ser dimensionado horizontalmente, pois não há ponto em que todo o conjunto de dados seja necessário no mesmo ponto. Com o hashing consistente (não implementado na demonstração para torná-lo mais simples), chaves diferentes podem ser armazenadas em servidores diferentes.</li>
<li>O PHP e o servidor Redis se comunicam usando o cliente de biblioteca Redis do PHP escrito por Ludovico Mangocavallo e incluído dentro da distribuição Redis tar.gz.</li>
<li>Este aplicativo é um trabalho para a disciplina Tópicos Especiais em Bancos de Dados</li>
</ul>
</div>









