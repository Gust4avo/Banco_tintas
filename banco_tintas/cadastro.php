<?php
include("conexao.php");

if (isset($_POST['submit'])) {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $endereco = $conexao->real_escape_string($_POST['endereco']);
    $cep = $conexao->real_escape_string($_POST['cep']);  // Pegando o CEP
    $cidade = $conexao->real_escape_string($_POST['cidade']); // Pegando a cidade
    $rua = $conexao->real_escape_string($_POST['rua']); // Pegando a rua
    $numero = $conexao->real_escape_string($_POST['numero']); // Pegando o número da casa
    $idade = (int)$_POST['idade'];
    $genero = (int)$_POST['genero'];
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; 
    $nivelacesso = 1;

    // Verificando o CEP com a API ViaCEP
    $url = "https://viacep.com.br/ws/$cep/json/";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['erro']) && $data['erro'] == true) {
        echo "CEP inválido!";
    } else {
        // Caso o CEP seja válido, insere os dados do usuário no banco
        $sql_code = "INSERT INTO usuario (nome, endereco, cidade, cep, numero, idade, genero, senha, email, nivelacesso) 
                     VALUES ('$nome', '$endereco', '$cidade', '$cep', '$numero', $idade, $genero, '$senha', '$email', $nivelacesso)";
        
        if ($conexao->query($sql_code)) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro: " . $conexao->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>

<h2>Cadastro de Usuário</h2>
<form action="" method="post">
    <p><input name="nome" placeholder="Nome" type="text" required></p>
    <p><input name="endereco" placeholder="Endereço" type="text" required></p>
    
    <!-- Campo de CEP -->
    <p><input name="cep" placeholder="CEP" type="text" id="cep" required onblur="verificarCep()"></p>
    <span id="mensagemCep" style="color: red;"></span> <!-- Exibe a mensagem de erro do CEP -->

    <!-- Campos de Cidade e Rua, preenchidos automaticamente -->
    <p><input name="rua" id="rua" placeholder="Rua" type="text" required readonly></p>
    <p><input name="cidade" id="cidade" placeholder="Cidade" type="text" required readonly></p>

    <p><input name="numero" placeholder="Número da Casa" type="text" required></p>

    <p><input name="idade" placeholder="Idade" type="number" required></p>
    <p>
        <label>Gênero:</label>
        <select name="genero" required>
            <option value="0">Selecione</option>
            <option value="1">Masculino</option>
            <option value="2">Feminino</option>
            <option value="3">Outro</option>
        </select>
    </p>
    <p><input name="senha" placeholder="Senha" type="password" required></p>
    <p><input name="email" placeholder="E-mail" type="email" required></p>
    
    <p><input type="submit" name="submit" value="Cadastrar"></p>
</form>

<script>
    // Função para verificar o CEP utilizando a API ViaCEP
    function verificarCep() {
        const cep = document.getElementById('cep').value.replace(/\D/g, ''); // Remove caracteres não numéricos
        const mensagemCep = document.getElementById('mensagemCep');

        if (cep.length === 8) { // Verifica se o CEP tem 8 dígitos
            const url = `https://viacep.com.br/ws/${cep}/json/`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        mensagemCep.innerHTML = "CEP inválido!";
                        document.getElementById('rua').value = '';
                        document.getElementById('cidade').value = '';
                    } else {
                        mensagemCep.innerHTML = ""; // Limpa a mensagem de erro
                        // Preenche os campos de rua e cidade automaticamente
                        document.getElementById('rua').value = data.logradouro;
                        document.getElementById('cidade').value = data.localidade;
                    }
                })
                .catch(error => {
                    mensagemCep.innerHTML = "Erro ao consultar o CEP.";
                });
        } else {
            mensagemCep.innerHTML = "CEP inválido!";
            document.getElementById('rua').value = '';
            document.getElementById('cidade').value = '';
        }
    }
</script>

</body>
</html>
