<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Consulta para pegar todas as tintas confirmadas
$sql_code_confirmadas = "SELECT * FROM tintas WHERE status = 'confirmada'";
$sql_query_confirmadas = $conexao->query($sql_code_confirmadas) or die($conexao->error);

// Consulta para pegar os pedidos do usuário
$sql_code_pedidos = "SELECT 
                        pedidos.id AS pedido_id,
                        pedidos.quantidade_solicitada,
                        pedidos.status AS pedido_status,
                        pedidos.data_pedido,
                        tintas.cor AS cor_tinta
                    FROM pedidos
                    JOIN tintas ON pedidos.id_tinta = tintas.id
                    WHERE pedidos.usuario_email = '{$_SESSION['email']}'";
$sql_query_pedidos = $conexao->query($sql_code_pedidos) or die($conexao->error);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Usuário</title>
</head>
<body>
    <h1>Dashboard do Usuário</h1>

    <h2>Tintas Confirmadas</h2>
    <!-- Exibindo as tintas confirmadas -->
    <table border="1">
        <thead>
            <tr>
                <th>Cor</th>
                <th>Quantidade (L)</th>
                <th>Validade</th>
                <th>Imagem</th>
                <th>Fazer Pedido</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($tinta = $sql_query_confirmadas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tinta['cor']); ?></td>
                    <td><?php echo (float)$tinta['quantidade_litros']; ?></td>
                    <td><?php echo htmlspecialchars($tinta['validade']); ?></td>
                    <td>
                        <img src="imagens/<?php echo htmlspecialchars($tinta['imagem']); ?>" alt="Imagem da Tinta" width="100">
                    </td>
                    <td>
                        <!-- Formulário para redirecionar para pedido.php -->
                        <form method="GET" action="pedido.php">
                            <input type="hidden" name="id_tinta" value="<?php echo $tinta['id']; ?>">
                            <input type="number" name="quantidade_solicitada" min="1" max="<?php echo (float)$tinta['quantidade_litros']; ?>" required>
                            <button type="submit">Fazer Pedido</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Meus Pedidos</h2>
    <!-- Exibindo os pedidos do usuário -->
    <table border="1">
        <thead>
            <tr>
                <th>Tinta</th>
                <th>Quantidade Solicitada (L)</th>
                <th>Status</th>
                <th>Data do Pedido</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $sql_query_pedidos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pedido['cor_tinta']); ?></td>
                    <td><?php echo (float)$pedido['quantidade_solicitada']; ?></td>
                    <td><?php echo htmlspecialchars($pedido['pedido_status']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['data_pedido']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <nav>
        <ul>
            <li><a href="usuario_dashboard.php">Início</a></li>
            <li><a href="addtinta.php">Doar tinta</a></li>
            <li><a href="curiosidades.php">Curiosidades</a></li>
            <li><a href="sobre.php">Sobre</a></li>
        </ul>
    </nav>
</body>
</html>
