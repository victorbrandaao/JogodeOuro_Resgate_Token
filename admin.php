<?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $servername = "localhost";
    $username = "jogcom_felix";
    $password = "@JOGOouro100%";
    $dbname = "jogcom_betoken";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $logins = explode("\n", trim($_POST['logins']));
        $tokens = explode("\n", trim($_POST['tokens']));

        $stmt = $conn->prepare("INSERT INTO jogadores (login, token) VALUES (?, ?)");
        
        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        foreach ($logins as $index => $login) {
            $token = isset($tokens[$index]) ? $tokens[$index] : '';
            $stmt->bind_param("ss", $login, $token);
            if (!$stmt->execute()) {
                die("Erro ao inserir dados: " . $stmt->error);
            }
        }

        echo "Dados inseridos com sucesso!";
        $stmt->close();
    }

    if (isset($_POST['deleteAllData'])) {
        $conn->query("DELETE FROM jogadores");
        echo "Todos os dados foram excluídos com sucesso!";
    }

    $result = $conn->query("SELECT login, token FROM jogadores");
    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Ouro - Bet Tokens Adminstrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            background-color: #0f3909;
            padding: 40px;
            color: #EFEFEF;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        div, form {
            background-color: #002900;
            padding: 30px;
            border-radius: 15px;
            max-width: 800px;
            width: 100%;
            margin: 20px 0;
        }

        label, textarea, input, button {
            margin-bottom: 25px;
        }

        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #EFEFEF;
            border-radius: 10px;
            background-color: transparent;
            color: #EFEFEF;
            font-size: 18px;
            resize: vertical;
        }

        input[type="submit"], button {
            padding: 15px 20px;
            border-radius: 10px;
            border: none;
            background-color: #EFEFEF;
            color: #002900;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #CDCDCD;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #EFEFEF;
        }

        th {
            background-color: #0f3909;
        }

        td:hover {
            background-color: #0f3909;
            color: yellow;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .delete-btn {
            background-color: red;
            color: white;
            padding-left: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .delete-btn:before {
            content: '⚠️';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .logout-link {
            color: #DAA520;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div style="text-align:right; padding: 20px;">
        Olá, <?php echo isset($_SESSION['users']) ? htmlspecialchars($_SESSION['users']) : 'usuário'; ?>!
        <a href="?logout=true" class="logout-link"><i class="fas fa-sign-out-alt"></i> Encerrar Sessão</a>
    </div>
    <form action="admin.php" method="post">
        <label for="logins"><i class="fas fa-user-plus"></i> Adicionar Logins:</label><br>
        <textarea name="logins" rows="10" cols="50" placeholder="Insira os logins aqui..."></textarea><br><br>
        
        <label for="tokens"><i class="fas fa-key"></i> Adicionar Tokens:</label><br>
        <textarea name="tokens" rows="10" cols="50" placeholder="Insira os tokens aqui..."></textarea><br><br>

        <input type="submit" value="Adicionar">
    </form>
    <div>
        <h2><i class="fas fa-list"></i> Lista de Logins e Tokens</h2>
        <table>
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Token</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['login']); ?></td>
                        <td><?php echo htmlspecialchars($row['token']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="button-container">
            <form action="admin.php" method="post">
                <button class="delete-btn" type="submit" name="deleteAllData" onclick="return confirm('Tem certeza de que deseja deletar todos os dados?');"><i class="fas fa-trash-alt"></i> Excluir Todos</button>
            </form>
        </div>
    </div>
</body>
</html>
