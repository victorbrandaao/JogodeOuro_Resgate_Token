<?php
    session_start(); // Inicie a sessão aqui

    // Verifique se o usuário está logado
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: login.php'); // Redireciona para a página de login
        exit;
    }
    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "jogcom_felix";
    $password = "@JOGOouro100%";
    $dbname = "jogcom_betoken";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Adiciona logins e tokens no banco de dados
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $logins = explode("\n", trim($_POST['logins']));
        $tokens = explode("\n", trim($_POST['tokens']));

        $stmt = $conn->prepare("INSERT INTO jogadores (login, token) VALUES (?, ?)");
        
        // Verificar se a consulta foi preparada corretamente
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
    

    // Recupera todos os logins e tokens do banco de dados
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
    display: block; /* Adicione esta linha */
    margin-left: auto; /* Adicione esta linha */
    margin-right: auto; /* Adicione esta linha */
    background-color: red;
    color: white;
    position: relative;
    padding-left: 30px;
}

.delete-btn:before {
    content: '⚠️';
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}
.logout-link {
        color: #DAA520; /* Cor ouro */
        text-decoration: none; /* Remove o sublinhado padrão dos links */
        font-weight: bold; /* Torna o texto em negrito */
    }

    .logout-link:hover {
        text-decoration: underline; /* Sublinha o link ao passar o mouse */
    }
        </style>
    </head>
    <body>
    <div style="text-align:right; padding: 20px;">
    Olá, <?php echo isset($_SESSION['users']) ? htmlspecialchars($_SESSION['users']) : 'Usuário'; ?>!
    <a href="?logout=true" class="logout-link">Encerrar Sessão</a>
</div>
        <form action="admin.php" method="post">
            <label for="logins">Adicionar Logins:</label><br>
            <textarea name="logins" rows="10" cols="50"></textarea><br><br>
            
            <label for="tokens">Adicionar Tokens:</label><br>
            <textarea name="tokens" rows="10" cols="50"></textarea><br><br>

            <input type="submit" value="Enviar">
        </form>
        <div class="button-container">
    <button onclick="toggleData()">Mostrar/Ocultar Logins e Tokens</button>
    <form action="admin.php" method="post" onsubmit="return confirm('Tem certeza de que deseja excluir todos os dados? Esta ação é irreversível.');">
        <button type="submit" name="deleteAllData" class="delete-btn">Excluir Todos os Dados</button>
    </form>
</div>
        <div id="dataDiv" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>Token</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['login']); ?></td>
                            <td><?php echo htmlspecialchars($row['token']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
            function toggleData() {
                var dataDiv = document.getElementById('dataDiv');
                if (dataDiv.style.display === 'none' || dataDiv.style.display === '') {
                    dataDiv.style.display = 'block';
                } else {
                    dataDiv.style.display = 'none';
                }
            }
        </script>
    </body>
    </html>