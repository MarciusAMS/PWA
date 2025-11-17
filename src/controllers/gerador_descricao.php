<?php
// Define que a resposta será um JSON
header('Content-Type: application/json');

// --- PASSO 1: Configure sua chave de API do Gemini ---
// ATENÇÃO: Cole sua chave de API aqui.
$GEMINI_API_KEY = 'AIzaSyDvmeaV5ytRvctZHELvj_hQKrHfd8wHV0o';

// URL da API do Gemini (gemini-2.5-flash)
$GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=' . $GEMINI_API_KEY;

// --- PASSO 2: Ler os dados (marca/modelo) enviados pelo JavaScript ---
$corpoRequisicao = file_get_contents('php://input');
$dados = json_decode($corpoRequisicao);

if (!$dados || empty($dados->marca) || empty($dados->modelo)) {
    echo json_encode(['erro' => 'Marca ou modelo não fornecidos.']);
    exit;
}

$marca = $dados->marca;
$modelo = $dados->modelo;

// --- PASSO 3: Criar o Prompt e o Payload para o Gemini ---
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

// --- PASSO 4: Fazer a chamada para a API (usando cURL) ---
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $GEMINI_API_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$respostaApi = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// --- PASSO 5: Processar a Resposta e Enviar de volta ao JavaScript ---
if ($httpcode == 200) {
    $resposta = json_decode($respostaApi);
    
    // Caminho para o texto gerado na resposta do Gemini
    $textoGerado = $resposta->candidates[0]->content->parts[0]->text ?? null;

    if ($textoGerado) {
        // Sucesso! Envia a descrição formatada.
        echo json_encode(['descricao' => trim($textoGerado)]);
    } else {
        // Se a API do Gemini respondeu mas o texto está vazio
        echo json_encode(['erro' => 'A API não retornou um texto. Resposta: ' . $respostaApi]);
    }
} else {
    // Se a chamada para a API do Gemini falhou (erro 400, 500, etc.)
    echo json_encode(['erro' => "Falha na API do Gemini (HTTP {$httpcode}). Resposta: " . $respostaApi]);
}
?>