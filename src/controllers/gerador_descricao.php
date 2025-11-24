<?php

header('Content-Type: application/json');

$GEMINI_API_KEY = 'AIzaSyDvmeaV5ytRvctZHELvj_hQKrHfd8wHV0o';

$GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=' . $GEMINI_API_KEY;

$corpoRequisicao = file_get_contents('php://input');
$dados = json_decode($corpoRequisicao);

if (!$dados || empty($dados->marca) || empty($dados->modelo)) {
    echo json_encode(['erro' => 'Marca ou modelo não fornecidos.']);
    exit;
}

$marca = $dados->marca;
$modelo = $dados->modelo;

$prompt = "Crie uma descrição de venda curta (máximo 3 frases) e empolgante para a motocicleta: {$marca} {$modelo}. Foque em performance e estilo. Retorne apenas o texto.";

$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $GEMINI_API_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$respostaApi = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
    $resposta = json_decode($respostaApi);

    $textoGerado = $resposta->candidates[0]->content->parts[0]->text ?? null;

    if ($textoGerado) {
        echo json_encode(['descricao' => trim($textoGerado)]);
    } else {
        echo json_encode(['erro' => 'A API não retornou um texto. Resposta: ' . $respostaApi]);
    }
} else {
    echo json_encode(['erro' => "Falha na API do Gemini (HTTP {$httpcode}). Resposta: " . $respostaApi]);
}
?>