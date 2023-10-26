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
    <!-- (resto do cabeçalho) -->
</head>
<body>
    <!-- ... (restante do código HTML) -->

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

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/653a70eea84dd54dc48584ac/1hdm4i9qe';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

</body>
</html>
