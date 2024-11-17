<?php
include("conexao.php");
session_start(); // Inicia a sessão

if (isset($_POST['email']) && strlen($_POST['email']) > 0) {
    // Limpa e armazena os dados do formulário
    $_SESSION['email'] = $conexao->real_escape_string($_POST['email']);
    $_SESSION['senha'] = $_POST['senha']; // Senha em texto simples

    // Consulta o banco de dados para verificar o usuário
    $sql_code = "SELECT senha, codigo, nivelacesso FROM usuario WHERE email = '$_SESSION[email]'";
    $sql_query = $conexao->query($sql_code) or die($conexao->error);

    $dado = $sql_query->fetch_assoc();
    $total = $sql_query->num_rows;

    $erro = array();

    if ($total == 0) {
        $erro[] = "Esse email não pertence a nenhum usuário.";
    } else {
        // Verifica se a senha fornecida corresponde à senha armazenada no banco
        if ($_SESSION['senha'] == $dado['senha']) {
            $_SESSION['id'] = $dado['codigo'];  // Salva o ID do usuário na sessão
            $_SESSION['nivelacesso'] = $dado['nivelacesso']; // Armazena o nível de acesso na sessão

            // Redireciona conforme o nível de acesso
            if ($_SESSION['nivelacesso'] == 2) {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: usuario_dashboard.php");
            }
            exit();
        } else {
            $erro[] = "Senha incorreta.";
        }
    }
}

if (isset($erro) && count($erro) > 0) {
    foreach ($erro as $msg) {
        echo "<p>$msg</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Banco de Tintas</title>
</head>
<body>
    <form action="" method="post">
        <p><a href="cadastro.php"><button type="button">Cadastrar</button></a></p>
        <p><input value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" name="email" placeholder="E-mail" type="text" required></p>
        <p><input value="" name="senha" placeholder="Senha" type="password" required></p>
        <p><a href="#">Esqueceu a senha?</a></p>
        <p><input type="submit" value="Entrar"></p>
    </form>
</body>
</html>
