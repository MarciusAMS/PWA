<?php
$link=mysqli_connect("localhost", "root", "") or die("Erro no mysql");

mysqli_select_db($link, "motorcompany") or die("Erro na base de dados");
?>