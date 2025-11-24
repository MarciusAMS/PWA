<?php
session_start();

require_once '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email || empty($senha)) {
        die("Erro: E-mail inválido ou senha não preenchida.");
    }

    $stmt_check = mysqli_prepare($link, "SELECT id FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result) > 0) {
        die("Erro: Este e-mail já está cadastrado.");
    }
    mysqli_stmt_close($stmt_check);

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt_insert = mysqli_prepare($link, "INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt_insert, "ss", $email, $senha_hash);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "Cadastro realizado com sucesso! <a href='../view/index.html'>Clique aqui para fazer o login.</a>";
        exit;
    } else {
        die("Erro: Não foi possível realizar o cadastro.");
    }
    mysqli_stmt_close($stmt_insert);

} else {
    header("Location: ../view/cadastro.html");
    exit;
}

mysqli_close($link);
?>

