<?php
include("conexao.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_tinta'])) {
    if (isset($_POST['ciente'])) { // Verifica se a caixinha foi marcada

        // Captura os dados do formulário
        $cor = $conexao->real_escape_string(trim($_POST['cor']));
        $quantidade_litros = (float)$_POST['quantidade_litros']; // Mudança para litros
        $validade = $conexao->real_escape_string(trim($_POST['validade']));
        $imagem = $_FILES['imagem']['name'];
        $caminho_imagem = "imagens/" . basename($imagem);

        // Novos campos do formulário
        $local_doacao = $conexao->real_escape_string(trim($_POST['local_doacao']));
        $dia_doacao = $conexao->real_escape_string(trim($_POST['dia_doacao']));
        $horario_doacao = $conexao->real_escape_string(trim($_POST['horario_doacao']));
        $indicacao_aplicacao = $conexao->real_escape_string(trim($_POST['indicacao_aplicacao']));
        $marca = $conexao->real_escape_string(trim($_POST['marca']));
        $tipo_linha = $conexao->real_escape_string(trim($_POST['tipo_linha']));
        $acabamento = $conexao->real_escape_string(trim($_POST['acabamento']));

        // Define o status como 'pendente'
        $status = 'pendente';

        // Faz o upload da imagem
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {

            // Insere os dados no banco de dados
            $sql_insert = "INSERT INTO tintas (cor, quantidade_litros, validade, imagem, tipo_embalagem, local_doacao, dia_doacao, horario_doacao, indicacao_aplicacao, marca, tipo_linha, acabamento, status)
                           VALUES ('$cor', $quantidade_litros, '$validade', '$imagem', 'Embalagem Padrão', '$local_doacao', '$dia_doacao', '$horario_doacao', '$indicacao_aplicacao', '$marca', '$tipo_linha', '$acabamento', '$status')";

            if ($conexao->query($sql_insert)) {
                echo "Tinta adicionada com sucesso!";
                header("Location: usuario_dashboard.php");
                exit();
            } else {
                echo "Erro ao adicionar tinta: " . $conexao->error;
            }
        } else {
            echo "Erro no upload da imagem.";
        }
    } else {
        echo "⚠️ Você deve marcar a caixinha para confirmar que está ciente das restrições de cadastro.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Tinta</title>
</head>
<body>
    <h1>Adicionar Nova Tinta</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label for="cor">Cor:</label>
            <input type="text" name="cor" id="cor" required>
        </div>
        <div>
            <label for="quantidade_litros">Quantidade (litros):</label>
            <input type="number" name="quantidade_litros" id="quantidade_litros" required min="0.01" step="0.01">
        </div>
        <div>
            <label for="validade">Validade:</label>
            <input type="date" name="validade" id="validade" required>
        </div>
        <div>
            <label for="imagem">Imagem:</label>
            <input type="file" name="imagem" id="imagem" required>
        </div>
        <div>
            <label for="local_doacao">Local de Doação:</label>
            <select name="local_doacao" id="local_doacao" required>
                <option value="Fatec Jundiaí">Fatec Jundiaí</option>
                <option value="Outros postos de coleta">Outros postos de coleta</option>
            </select>
        </div>
        <div>
            <label for="dia_doacao">Dia de Doação:</label>
            <select name="dia_doacao" id="dia_doacao" required>
                <option value="Segunda-feira">Segunda-feira</option>
                <option value="Terça-feira">Terça-feira</option>
                <option value="Quarta-feira">Quarta-feira</option>
                <option value="Quinta-feira">Quinta-feira</option>
                <option value="Sexta-feira">Sexta-feira</option>
            </select>
        </div>
        <div>
            <label for="horario_doacao">Horário de Doação:</label>
            <select name="horario_doacao" id="horario_doacao" required>
                <option value="08:00 às 11:00">08:00 às 11:00</option>
                <option value="13:00 às 17:00">13:00 às 17:00</option>
            </select>
        </div>
        <div>
            <label for="indicacao_aplicacao">Indicação de Aplicação:</label>
            <select name="indicacao_aplicacao" id="indicacao_aplicacao" required>
                <option value="Alvenaria">Alvenaria</option>
                <option value="Madeira">Madeira</option>
                <option value="Metal">Metal</option>
            </select>
        </div>
        <div>
            <label for="tipo_linha">Tipo de Linha:</label>
            <select name="tipo_linha" id="tipo_linha" required>
                <option value="Premium">Premium</option>
                <option value="Standard">Standard</option>
                <option value="Econômica">Econômica</option>
            </select>
        </div>
        <div>
            <label for="acabamento">Acabamento:</label>
            <select name="acabamento" id="acabamento" required>
                <option value="Fosco">Fosco</option>
                <option value="Acetinado">Acetinado</option>
                <option value="Brilhante">Brilhante</option>
            </select>
        </div>
        <div>
            <input type="checkbox" name="ciente" id="ciente">
            <label for="ciente">Estou ciente que o banco de tintas não aceita tintas à base de óleo, solventes ou vernizes.</label>
        </div>
        <button type="submit" name="adicionar_tinta">Adicionar Tinta</button>
    </form>
</body>
<ul>
        <li><a href="usuario_dashboard.php">Início</a></li>
        <li><a href="addtinta.php">Doar tinta</a></li>
        <li><a href="curiosidades.php">Curiosidades</a></li>
        <li><a href="sobre.php">Sobre</a></li>
    </ul>
</html>
