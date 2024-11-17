<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Verifica se os parâmetros foram passados via GET
if (isset($_GET['id_tinta']) && isset($_GET['quantidade_solicitada'])) {
    $id_tinta = $_GET['id_tinta'];
    $quantidade_solicitada = $_GET['quantidade_solicitada'];

    // Consulta para obter detalhes da tinta
    $sql_tinta = "SELECT cor, quantidade_litros FROM tintas WHERE id = ?";
    $stmt = $conexao->prepare($sql_tinta);
    $stmt->bind_param("i", $id_tinta);
    $stmt->execute();
    $result = $stmt->get_result();
    $tinta = $result->fetch_assoc();
} else {
    echo "Erro ao obter detalhes da tinta.";
    exit();
}

// Se o formulário for enviado via POST, processe o pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['local_retirada'], $_POST['dia_retirada'], $_POST['horario_retirada'])) {
        $local_retirada = $_POST['local_retirada'];
        $dia_retirada = $_POST['dia_retirada'];
        $horario_retirada = $_POST['horario_retirada'];
        $email_usuario = $_SESSION['email'];

        // Inserindo o pedido no banco de dados
        $stmt_pedido = $conexao->prepare("INSERT INTO pedidos (id_tinta, quantidade_solicitada, usuario_email, local_retirada, dia_retirada, horario_retirada, status, data_pedido) VALUES (?, ?, ?, ?, ?, ?, 'Pendente', NOW())");
        $stmt_pedido->bind_param("iissss", $id_tinta, $quantidade_solicitada, $email_usuario, $local_retirada, $dia_retirada, $horario_retirada);
        $stmt_pedido->execute();

        echo "Pedido realizado com sucesso!";
        exit();
    } else {
        echo "Por favor, preencha todos os campos de retirada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação do Pedido</title>
</head>
<body>
    <h1>Confirmação do Pedido</h1>
    <p>Cor da Tinta: <strong><?php echo htmlspecialchars($tinta['cor']); ?></strong></p>
    <p>Quantidade Solicitada: <?php echo (float)$quantidade_solicitada; ?> litros</p>

    <form method="POST" action="">
        <label for="local_retirada">Local de Retirada:</label>
        <select name="local_retirada" id="local_retirada" required>
            <option value="Fatec Jundiaí">Fatec Jundiaí</option>
            <option value="Outros postos de coleta">Outros postos de coleta</option>
        </select>

        <label for="dia_retirada">Dia de Retirada:</label>
        <select name="dia_retirada" id="dia_retirada" required>
            <option value="Segunda-feira">Segunda-feira</option>
            <option value="Terça-feira">Terça-feira</option>
            <option value="Quarta-feira">Quarta-feira</option>
            <option value="Quinta-feira">Quinta-feira</option>
            <option value="Sexta-feira">Sexta-feira</option>
        </select>

        <label for="horario_retirada">Horário de Retirada:</label>
        <select name="horario_retirada" id="horario_retirada" required>
            <option value="08:00 às 11:00">08:00 às 11:00</option>
            <option value="13:00 às 17:00">13:00 às 17:00</option>
        </select>

        <button type="submit">Confirmar Pedido</button>
    </form>
</body>
</html>
