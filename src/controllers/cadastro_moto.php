<?php
session_start();

require_once '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

    if (empty($nome_empresa) || empty($modelo) || empty($valor)) {
        die("Erro: Marca, modelo e valor são obrigatórios.");
    }

    $sql = "INSERT INTO motocicletas (nome_empresa, modelo, valor, descricao) VALUES (?, ?, ?, ?)";
    
    $stmt_insert = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt_insert, "ssds", $nome_empresa, $modelo, $valor, $descricao);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "<script>
                alert('Moto cadastrada com sucesso!');
                window.location.href = '../view/principal.html';
              </script>";
        exit;
    } else {
        die("Erro: Não foi possível realizar o cadastro da motocicleta: " . mysqli_error($link));
    }
    
    mysqli_stmt_close($stmt_insert);

} else {
    header("Location: ../view/cadastro_moto.html");
    exit;
}

mysqli_close($link);
?>