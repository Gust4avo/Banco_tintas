<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Verifique se os valores foram recebidos via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se todos os campos necessários foram enviados
    if (isset($_POST['id_tinta'], $_POST['quantidade_solicitada'], $_POST['local_retirada'], $_POST['dia_retirada'], $_POST['horario_retirada'])) {
        $id_tinta = $_POST['id_tinta'];
        $quantidade_solicitada = $_POST['quantidade_solicitada'];
        $local_retirada = $_POST['local_retirada'];
        $dia_retirada = $_POST['dia_retirada'];
        $horario_retirada = $_POST['horario_retirada'];

        // Consulta para obter detalhes da tinta usando prepared statements
        $stmt = $conexao->prepare("SELECT cor, quantidade_litros FROM tintas WHERE id = ?");
        $stmt->bind_param("i", $id_tinta);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se a tinta foi encontrada
        if ($result && $result->num_rows > 0) {
            $tinta = $result->fetch_assoc();
            
            // Quantidade restante no estoque
            $quantidade_restante = $tinta['quantidade_litros'] - $quantidade_solicitada;

            // Atualiza o estoque de tintas no banco de dados
            $stmt_update = $conexao->prepare("UPDATE tintas SET quantidade_litros = ? WHERE id = ?");
            $stmt_update->bind_param("ii", $quantidade_restante, $id_tinta);
            $stmt_update->execute();

            // Insere o pedido no banco de dados
            $email_usuario = $_SESSION['email'];
            $stmt_inserir = $conexao->prepare("INSERT INTO pedidos (id_tinta, quantidade_solicitada, email_usuario, local_retirada, dia_retirada, horario_retirada, status, data_pedido) VALUES (?, ?, ?, ?, ?, ?, 'Pendente', NOW())");
            $stmt_inserir->bind_param("iissss", $id_tinta, $quantidade_solicitada, $email_usuario, $local_retirada, $dia_retirada, $horario_retirada);
            $stmt_inserir->execute();
            ?>
            
            <!DOCTYPE html>
            <html lang="pt-br">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Pedido Confirmado</title>
            </head>
            <body>
                <h1>Pedido Confirmado</h1>
                <p>Você solicitou <?php echo $quantidade_solicitada; ?> litros da tinta de cor: <strong><?php echo $tinta['cor']; ?></strong></p>
                <p>Quantidade restante no estoque: <?php echo $quantidade_restante; ?> litros</p>
                <p>Status do pedido: <strong>Pendente</strong></p>
                <p>Local de Retirada: <?php echo $local_retirada; ?></p>
                <p>Dia de Retirada: <?php echo $dia_retirada; ?></p>
                <p>Horário de Retirada: <?php echo $horario_retirada; ?></p>
                <p><a href="usuario_dashboard.php">Voltar para o Dashboard</a></p>
            </body>
            </html>
            <?php
        } else {
            echo "Erro ao obter detalhes da tinta. Verifique se o ID da tinta está correto e existe no banco de dados.";
        }
    } else {
        echo "Erro ao obter detalhes do pedido. Todos os campos devem ser preenchidos.";
    }
} else {
    echo "Acesso inválido.";
}
?>
