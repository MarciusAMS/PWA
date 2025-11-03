<?php
session_start();

// Usa o seu arquivo de conexão e a variável $link
require_once '../../db/conexao.php';

// Processa o formulário apenas se for um método POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Coleta e sanitiza os dados do formulário
    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    // Filtra o valor como float, removendo caracteres inválidos.
    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // 2. Validação básica dos campos.
    if (empty($nome_empresa) || empty($modelo) || empty($valor)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    // 3. Insere a nova motocicleta no banco de dados
    // (Não é necessário verificar duplicatas ou hashear a senha aqui)
    
    $sql = "INSERT INTO motocicletas (nome_empresa, modelo, valor) VALUES (?, ?, ?)";
    
    $stmt_insert = mysqli_prepare($link, $sql);
    
    // "ssd" = string, string, double
    mysqli_stmt_bind_param($stmt_insert, "ssd", $nome_empresa, $modelo, $valor);
    
    // 4. Executa e redireciona
    if (mysqli_stmt_execute($stmt_insert)) {
        // Sucesso: Redireciona para a página principal
            echo "<script>
                alert('Moto cadastrada com sucesso!');
                window.location.href = '../view/principal.html';
              </script>";
    } else {
        // Falha: Exibe o erro (para depuração)
        die("Erro: Não foi possível realizar o cadastro da motocicleta: " . mysqli_error($link));
    }
    
    mysqli_stmt_close($stmt_insert);

} else {
    // Redireciona se o script for acessado diretamente.
    header("Location: ../view/cadastro_moto.html");
    exit;
}

mysqli_close($link);
?>
