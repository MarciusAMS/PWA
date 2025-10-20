<?php
include("./db/conexao.php");//inclui o arquivo conecta.php
session_start();//inicia a sessão
$usuario=$_POST['email'];//recebe o valor do campo usuario do formulário
$senha=$_POST['senha']; //recebe o valor do campo senha do formulário
$_SESSION['usuario']=$usuario;//cria a variável de sessão usuario
$consulta="Select *from usuarios where usuario='$usuario' and senha='$senha'";
//consulta sql para selecionar todos os campos da tabela usuarios onde o campo usuario é igual ao valor da variável $usuario e o campo senha é igual ao valor da variável $senha
$resultado=mysqli_query($link,$consulta);//executa a consulta no banco de dados
if(mysqli_num_rows($resultado)>0){//se o número de linhas do resultado for maior que zero,ou seja, se houver um registro com o usuário e senha informados
    header("Location:principal.html");//redireciona para a página principal.php
}else{
    unset ($_SESSION['usuario']);//destrói a variável de sessão usuario
    header("Location:index.html");//redireciona para a página index.html
}
?>