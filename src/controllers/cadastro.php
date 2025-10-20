<?php
session_start();

// Usa o seu arquivo de conexão e a variável $link
require_once '../../db/conexao.php';

// Processa o formulário apenas se for um método POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    // Validação básica dos campos.
    if (!$email || empty($senha)) {
        die("Erro: E-mail inválido ou senha não preenchida.");
    }

    // Verifica se o e-mail já existe para evitar duplicatas (estilo procedural).
    $stmt_check = mysqli_prepare($link, "SELECT id FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result) > 0) {
        die("Erro: Este e-mail já está cadastrado.");
    }
    mysqli_stmt_close($stmt_check);

    // Criptografa a senha antes de salvar.
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco de dados (estilo procedural).
    $stmt_insert = mysqli_prepare($link, "INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt_insert, "ss", $email, $senha_hash);
    
    // Executa e redireciona em caso de sucesso.
    if (mysqli_stmt_execute($stmt_insert)) {
      //  header("Location: ../view/index.html?status=sucesso");
        echo "Cadastro realizado com sucesso! <a href='../view/index.html'>Clique aqui para fazer o login.</a>";
        exit;
    } else {
        die("Erro: Não foi possível realizar o cadastro.");
    }
    mysqli_stmt_close($stmt_insert);

} else {
    // Redireciona se o script for acessado diretamente.
    header("Location: ../view/cadastro.html");
    exit;
}

mysqli_close($link);
?>

