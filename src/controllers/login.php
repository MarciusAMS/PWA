<?php
// Inicia a sessão para armazenar os dados do usuário logado
session_start();

// Inclui seu arquivo de conexão
require_once '../../db/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    // Valida se os campos foram preenchidos
    if (!$email || empty($senha)) {
        // Redireciona de volta com uma mensagem de erro genérica
        header("Location: ../view/index.html?error=invalid_credentials");
        exit;
    }

    // 1. Prepara a consulta para buscar o usuário pelo e-mail (evita SQL Injection)
    $sql = "SELECT id, email, senha FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // 2. Verifica se o usuário foi encontrado
    if ($usuario = mysqli_fetch_assoc($result)) {
        
        // 3. Verifica se a senha enviada corresponde à senha criptografada no banco
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta! Login bem-sucedido.
            
            // Armazena informações na sessão (evite armazenar a senha)
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];

            // Redireciona para a página principal
            header("Location: ../view/principal.html");
            exit;

        } else {
            // Senha incorreta
            header("Location: ../view/index.html?error=invalid_credentials");
            exit;
        }

    } else {
        // E-mail não encontrado
        header("Location: ../view/index.html?error=invalid_credentials");
        exit;
    }

    mysqli_stmt_close($stmt);

} else {
    // Se não for um POST, redireciona para a página de login
    header("Location: ../view/index.html");
    exit;
}

mysqli_close($link);
?>
