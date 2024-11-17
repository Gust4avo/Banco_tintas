<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado e tem nível de acesso de administrador
if (!isset($_SESSION['email']) || $_SESSION['nivelacesso'] != 2) {
    header("Location: login.php");
    exit();
}

// Variável para mensagens de sucesso ou erro
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Confirmar a tinta
    if (isset($_GET['confirmar_id'])) {
        $id = (int)$_GET['confirmar_id'];
        
        // Verifica se a tinta já está confirmada antes de atualizar
        $sql_check = "SELECT status FROM tintas WHERE id = $id";
        $result_check = $conexao->query($sql_check);
        $tinta = $result_check->fetch_assoc();

        if ($tinta['status'] != 'confirmada') {
            // Atualiza o status para 'confirmada'
            $sql_update = "UPDATE tintas SET status = 'confirmada' WHERE id = $id";
            if ($conexao->query($sql_update)) {
                $msg = "Tinta confirmada com sucesso!";
                // Redireciona para evitar reenvio do formulário
                header("Location: confirmar_tinta.php?msg=" . urlencode($msg));
                exit();
            } else {
                $msg = "Erro ao confirmar tinta: " . $conexao->error;
            }
        } else {
            $msg = "A tinta já está confirmada.";
        }
    }

    // Excluir a tinta
    if (isset($_GET['excluir_id'])) {
        $id = (int)$_GET['excluir_id'];
        $sql_delete = "DELETE FROM tintas WHERE id = $id";
        if ($conexao->query($sql_delete)) {
            $msg = "Tinta excluída com sucesso!";
            header("Location: confirmar_tinta.php?msg=" . urlencode($msg));
            exit();
        } else {
            $msg = "Erro ao excluir tinta: " . $conexao->error;
        }
    }
}

// Consulta para pegar as tintas pendentes
$sql_code = "SELECT * FROM tintas WHERE status = 'pendente'";
$sql_query = $conexao->query($sql_code) or die($conexao->error);

// Mensagem de sucesso/erro via GET
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Tintas</title>
</head>
<body>
    <h1>Confirmar Tintas Pendentes</h1>

    <?php if ($msg != ''): ?>
        <p><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Cor</th>
                <th>Quantidade (litros)</th>
                <th>Validade</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($tinta = $sql_query->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tinta['cor']); ?></td>
                    <td><?php echo (float)$tinta['quantidade_litros']; ?></td>
                    <td><?php echo htmlspecialchars($tinta['validade']); ?></td>
                    <td>
                        <img src="imagens/<?php echo htmlspecialchars($tinta['imagem']); ?>" alt="Imagem da Tinta" width="100">
                    </td>
                    <td>
                        <a href="confirmar_tinta.php?confirmar_id=<?php echo $tinta['id']; ?>" onclick="return confirm('Tem certeza que deseja confirmar esta tinta?');">Confirmar</a>
                        <a href="confirmar_tinta.php?excluir_id=<?php echo $tinta['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
