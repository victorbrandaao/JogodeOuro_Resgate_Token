<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKEN PREMIADO - JOGODEOURO.BET</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #004d00; /* Cor de fundo ajustada para um verde escuro */
        }

        .centered {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 400px; /* Largura fixa do container */
            background-color: #333; /* Cor de fundo do container */
            border-radius: 10px; /* Bordas arredondadas */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5); /* Sombra ao redor do container */
            padding: 20px; /* Espaçamento interno do container */
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #ffffff; /* Cor do texto ajustada para branco */
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px; /* Bordas arredondadas */
            margin-bottom: 20px; /* Espaçamento abaixo dos campos */
        }

        input[type="submit"], button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px; /* Bordas arredondadas */
        }

        button {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="centered">
        <?php
        if (!isset($_POST['login'])) {
            echo '<h1>Digite seu login para resgatar seu bônus!</h1>';
            echo '<form method="post" action="">';
            echo '<input type="text" name="login" placeholder="Seu login" required>';
            echo '<br><br>';
            echo '<input type="submit" name="submit" value="RESGATAR MEU PRÊMIO">';
            echo '</form>';
        } else {
            $servername = "localhost";
            $username = "jogcom_felix";
            $password = "@JOGOouro100%";
            $dbname = "jogcom_betoken";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $login = $_POST['login'];

                // Execute uma consulta para verificar se o login existe no banco de dados (tabela jogadores)
                $stmt = $conn->prepare("SELECT token FROM jogadores WHERE login = :login");
                $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $token = htmlspecialchars($row['token']);
                    echo '<h1>VOCÊ ACABOU DE GANHAR UMA BANCA DE R$ 20,00!</h1>';
                    echo '<input type="text" id="token" value="' . $token . '" readonly>';
                    echo '<br><br>';
                    echo '<button onclick="copyToken()">COPIAR TOKEN</button>';
                    echo '<br><br>';
                    echo '<button onclick="redirectResgate()">RESGATAR AGORA MESMO</button>';
                } else {
                    echo '<p>Login não encontrado.</p>';
                }
            } catch (PDOException $e) {
                echo "Erro de conexão: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
        ?>
    </div>

    <script>
        function copyToken() {
            var tokenInput = document.getElementById('token');
            tokenInput.select();
            document.execCommand("copy");
            alert("Token copiado com sucesso!");
        }

        function redirectResgate() {
            window.open('https://www.jogodeouro.bet/dashboard/promotions?openDialogToken=true', '_blank');
        }
    </script>
</body>
</html>
