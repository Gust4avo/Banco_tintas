<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$id_tinta = $_GET['id'];
$sql_tinta = "SELECT * FROM tintas WHERE id = $id_tinta";
$query_tinta = $conexao->query($sql_tinta) or die($conexao->error);
$tinta = $query_tinta->fetch_assoc();

// Atualizar tinta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_edicao'])) {
    $cor = $conexao->real_escape_string(trim($_POST['cor']));
    $quantidade_litros = (float)$_POST['quantidade_litros'];
    $validade = $conexao->real_escape_string(trim($_POST['validade']));
    $imagem_atual = $_POST['imagem_atual'];
    $imagem = $_FILES['imagem']['name'] ? $_FILES['imagem']['name'] : $imagem_atual;
    $tipo_embalagem = $conexao->real_escape_string(trim($_POST['tipo_embalagem']));
    $local_doacao = $conexao->real_escape_string(trim($_POST['local_doacao']));
    $dia_doacao = $conexao->real_escape_string(trim($_POST['dia_doacao']));
    $horario_doacao = $conexao->real_escape_string(trim($_POST['horario_doacao']));
    $status = in_array($_POST['status'], ['pendente', 'confirmada']) ? $_POST['status'] : 'pendente';
    $indicacao_aplicacao = $conexao->real_escape_string(trim($_POST['indicacao_aplicacao']));
    $marca = $conexao->real_escape_string(trim($_POST['marca']));
    $tipo_linha = $conexao->real_escape_string(trim($_POST['tipo_linha']));
    $acabamento = $conexao->real_escape_string(trim($_POST['acabamento']));

    // Upload de imagem
    if ($_FILES['imagem']['name']) {
        $target_dir = "imagens/";
        $target_file = $target_dir . basename($imagem);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file);
    }

    // Atualizar dados no banco
    $sql_update = "
        UPDATE tintas SET
            cor = '$cor',
            quantidade_litros = $quantidade_litros,
            validade = '$validade',
            imagem = '$imagem',
            tipo_embalagem = '$tipo_embalagem',
            local_doacao = '$local_doacao',
            dia_doacao = '$dia_doacao',
            horario_doacao = '$horario_doacao',
            status = '$status',
            indicacao_aplicacao = '$indicacao_aplicacao',
            marca = '$marca',
            tipo_linha = '$tipo_linha',
            acabamento = '$acabamento'
        WHERE id = $id_tinta
    ";

    if ($conexao->query($sql_update)) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Erro ao atualizar a tinta: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tinta</title>
</head>
<body>
    <h1>Editar Tinta</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="imagem_atual" value="<?php echo $tinta['imagem']; ?>">

        <label>Cor:</label>
        <input type="text" name="cor" value="<?php echo $tinta['cor']; ?>" required><br>

        <label>Quantidade (L):</label>
        <input type="number" step="0.01" name="quantidade_litros" value="<?php echo $tinta['quantidade_litros']; ?>" required><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="pendente" <?php echo $tinta['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
            <option value="confirmada" <?php echo $tinta['status'] == 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
        </select><br>

        <button type="submit" name="salvar_edicao">Salvar Alterações</button>
    </form>
</body>
</html>
