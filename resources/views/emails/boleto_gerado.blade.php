<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleto Kanastra Gerado</title>
</head>
<body>
    <h1>Boleto Gerado com Sucesso</h1>
    <p>Olá {{ $data['name'] }},</p>
    <p>Seu boleto no valor de R$ {{ number_format($data['debtAmount'], 2, ',', '.') }} foi gerado com sucesso.</p>
    <p>Data de Vencimento: {{ $data['debtDueDate'] }}</p>
    <p>ID do Boleto: {{ $data['debtId'] }}</p>
</body>
</html>
