<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Consultas para tintas confirmadas e pendentes
$sql_confirmadas = "SELECT * FROM tintas WHERE status = 'confirmada'";
$sql_pendentes = "SELECT * FROM tintas WHERE status = 'pendente' OR status IS NULL";
$query_confirmadas = $conexao->query($sql_confirmadas) or die($conexao->error);
$query_pendentes = $conexao->query($sql_pendentes) or die($conexao->error);

// Consulta para exibir todos os pedidos
$sql_pedidos = "
    SELECT 
        pedidos.id AS pedido_id,
        pedidos.usuario_email,
        pedidos.quantidade_solicitada,
        pedidos.status AS pedido_status,
        pedidos.data_pedido,
        tintas.cor AS cor_tinta,
        tintas.imagem AS imagem_tinta
    FROM pedidos
    JOIN tintas ON pedidos.id_tinta = tintas.id
";
$query_pedidos = $conexao->query($sql_pedidos) or die($conexao->error);

// Excluir tinta
if (isset($_GET['excluir_id'])) {
    $id_tinta = (int)$_GET['excluir_id'];
    $sql_delete = "DELETE FROM tintas WHERE id = ?";

    if ($stmt = $conexao->prepare($sql_delete)) {
        $stmt->bind_param('i', $id_tinta);
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Erro ao excluir a tinta: " . $conexao->error;
        }
        $stmt->close();
    }
}

// Alterar status do pedido
if (isset($_GET['alterar_pedido_id']) && isset($_GET['novo_status'])) {
    $pedido_id = (int)$_GET['alterar_pedido_id'];
    $novo_status = $_GET['novo_status'];

    if (in_array($novo_status, ['pendente', 'confirmado', 'recusado'])) {
        $sql_update_pedido = "UPDATE pedidos SET status = ? WHERE id = ?";
        if ($stmt = $conexao->prepare($sql_update_pedido)) {
            $stmt->bind_param('si', $novo_status, $pedido_id);
            $stmt->execute();
            $stmt->close();
            header("Location: admin_dashboard.php");
            exit();
        }
    }
}

// Definir informações de retirada após confirmação
if (isset($_POST['definir_retirada']) && isset($_POST['pedido_id']) && isset($_POST['local_doacao']) && isset($_POST['dia_doacao']) && isset($_POST['horario_doacao'])) {
    $pedido_id = (int)$_POST['pedido_id'];
    $local_doacao = $_POST['local_doacao'];
    $dia_doacao = $_POST['dia_doacao'];
    $horario_doacao = $_POST['horario_doacao'];

    // Atualizar o pedido com as informações de retirada
    $sql_atualizar_retirada = "
        UPDATE pedidos 
        SET local_retirada = ?, 
            dia_retirada = ?, 
            horario_retirada = ?, 
            status = 'retirada definida' 
        WHERE id = ?
    ";
    if ($stmt = $conexao->prepare($sql_atualizar_retirada)) {
        $stmt->bind_param('sssi', $local_doacao, $dia_doacao, $horario_doacao, $pedido_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Dashboard do Administrador</h1>

    <!-- Listagem de Tintas Confirmadas -->
    <h2>Tintas Confirmadas</h2>
    <table border="1">
        <tr>
            <th>Imagem</th>
            <th>Cor</th>
            <th>Quantidade (L)</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php while ($tinta = $query_confirmadas->fetch_assoc()): ?>
            <tr>
                <td><img src="imagens/<?php echo htmlspecialchars($tinta['imagem']); ?>" alt="Imagem da Tinta" width="100"></td>
                <td><?php echo htmlspecialchars($tinta['cor']); ?></td>
                <td><?php echo (float)$tinta['quantidade_litros']; ?></td>
                <td><?php echo htmlspecialchars($tinta['status']); ?></td>
                <td>
                    <a href="editar_tinta.php?id=<?php echo $tinta['id']; ?>">Editar</a> |
                    <a href="admin_dashboard.php?excluir_id=<?php echo $tinta['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Listagem de Tintas Pendentes -->
    <h2>Tintas Pendentes</h2>
    <table border="1">
        <tr>
            <th>Imagem</th>
            <th>Cor</th>
            <th>Quantidade (L)</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php while ($tinta = $query_pendentes->fetch_assoc()): ?>
            <tr>
                <td><img src="imagens/<?php echo htmlspecialchars($tinta['imagem']); ?>" alt="Imagem da Tinta" width="100"></td>
                <td><?php echo htmlspecialchars($tinta['cor']); ?></td>
                <td><?php echo (float)$tinta['quantidade_litros']; ?></td>
                <td><?php echo htmlspecialchars($tinta['status']); ?></td>
                <td>
                    <a href="editar_tinta.php?id=<?php echo $tinta['id']; ?>">Editar</a> |
                    <a href="admin_dashboard.php?excluir_id=<?php echo $tinta['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Listagem de Pedidos de Tintas -->
    <h2>Pedidos de Tintas</h2>
    <table border="1">
        <tr>
            <th>Imagem</th>
            <th>Tinta</th>
            <th>Quantidade Solicitada (L)</th>
            <th>Usuário</th>
            <th>Status</th>
            <th>Data do Pedido</th>
            <th>Ações</th>
        </tr>
        <?php while ($pedido = $query_pedidos->fetch_assoc()): ?>
            <tr>
                <td><img src="imagens/<?php echo htmlspecialchars($pedido['imagem_tinta']); ?>" alt="Imagem da Tinta" width="100"></td>
                <td><?php echo htmlspecialchars($pedido['cor_tinta']); ?></td>
                <td><?php echo (float)$pedido['quantidade_solicitada']; ?></td>
                <td><?php echo htmlspecialchars($pedido['usuario_email']); ?></td>
                <td><?php echo htmlspecialchars($pedido['pedido_status']); ?></td>
                <td><?php echo htmlspecialchars($pedido['data_pedido']); ?></td>
                <td>
                    <!-- Ações de acordo com o status do pedido -->
                    <?php if (strtolower($pedido['pedido_status']) == 'pendente'): ?>
                        <!-- Botão para confirmar o pedido -->
                        <a href="admin_dashboard.php?alterar_pedido_id=<?php echo $pedido['pedido_id']; ?>&novo_status=confirmado">Confirmar Pedido</a> |
                        <a href="admin_dashboard.php?alterar_pedido_id=<?php echo $pedido['pedido_id']; ?>&novo_status=recusado">Recusar</a>
                    
                    <?php elseif (strtolower($pedido['pedido_status']) == 'confirmado'): ?>
                        <!-- Formulário para definir local, dia e horário após confirmação -->
                        <form method="POST" action="admin_dashboard.php">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['pedido_id']; ?>">
                            <div>
                                <label for="local_doacao">Local de Doação:</label>
                                <select name="local_doacao" id="local_doacao" required>
                                    <option value="Fatec Jundiaí">Fatec Jundiaí</option>
                                    <option value="Outros postos de coleta">Outros postos de coleta</option>
                                </select>
                            </div>
                            <div>
                                <label for="dia_doacao">Dia da Retirada:</label>
                                <select name="dia_doacao" id="dia_doacao" required>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                </select>
                            </div>
                            <div>
                                <label for="horario_doacao">Horário:</label>
                                <select name="horario_doacao" id="horario_doacao" required>
                                    <option value="08:00 às 11:00">08:00 às 11:00</option>
                                    <option value="13:00 às 17:00">13:00 às 17:00</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" name="definir_retirada">Definir Retirada</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
