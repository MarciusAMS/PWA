<?php

require_once '../../db/conexao.php';

$sql = "SELECT id, nome_empresa, modelo, valor, descricao FROM motocicletas ORDER BY id ASC";
$resultado = mysqli_query($link, $sql);

if (!$resultado) {
    die("Erro ao consultar o banco de dados: " . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Motocicletas - Motor Company</title>
     <link rel="stylesheet" href="../style/consulta_motos.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Estoque de Motocicletas</h1>
            <a href="principal.html" class="btn-voltar">Voltar ao Início</a>
        </header>

        <table class="moto-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Modelo</th>
                    <th>Valor (R$)</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    // Loop para exibir cada motocicleta
                    while ($moto = mysqli_fetch_assoc($resultado)) {
                        // Formata o valor para o padrão brasileiro
                        $valor_formatado = number_format($moto['valor'], 2, ',', '.');
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($moto['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($moto['nome_empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($moto['modelo']) . "</td>";
                        echo "<td>" . htmlspecialchars($valor_formatado) . "</td>";
                        echo "<td>" . htmlspecialchars($moto['descricao'] ) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr class='no-results'><td colspan='5'>Nenhuma motocicleta cadastrada no momento.</td></tr>";
                }
                mysqli_close($link);
                ?>
            </tbody>
        </table>

    </div>
</body>

</html>