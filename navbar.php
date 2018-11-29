<div id="navbar">
<div class="search-container">
    <form action="/search.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
</div>
<a href="index.php">home</a>
| <a href="timeline.php">timeline</a>
<?if(isLoggedIn()) {?>
| <a href="export.php">exportar</a>
| <a href="logout.php">logout</a>
<?}?>
</div>
