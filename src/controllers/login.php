<?php

session_start();

require_once '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email || empty($senha)) {
        header("Location: ../view/index.html?error=invalid_credentials");
        exit;
    }

    $sql = "SELECT id, email, senha FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result)) {

        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];

            header("Location: ../view/principal.html");
            exit;

        } else {
            header("Location: ../view/index.html?error=invalid_credentials");
            exit;
        }

    } else {
        header("Location: ../view/index.html?error=invalid_credentials");
        exit;
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: ../view/index.html");
    exit;
}

mysqli_close($link);
?>
