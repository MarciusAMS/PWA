<?php
$link=mysqli_connect("localhost", "root", "") or die("Erro no mysql");

// função de conexão com o mysql, de maneira local, com os usuários root e sem senha
// or die é para caso haja algum erro na conexão
mysqli_select_db($link, "motorcompany") or die("Erro na base de dados");
?>