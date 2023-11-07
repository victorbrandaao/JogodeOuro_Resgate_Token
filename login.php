<?php
session_start();

$servername = "#";
$username = "#";
$password = "#";
$dbname = "#";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Aqui você deve verificar as credenciais no banco de dados
    // Por exemplo:
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?"); // Assuma que você tem uma tabela 'users' com campos 'username' e 'password'
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        echo "Credenciais inválidas!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Jogo de Ouro</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos que são consistentes com a página admin.php */
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

        form {
            background-color: #002900;
            padding: 30px;
            border-radius: 15px;
            max-width: 400px;
            width: 100%;
            margin: 20px 0;
        }

        label, input {
            margin-bottom: 25px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #EFEFEF;
            border-radius: 10px;
            background-color: transparent;
            color: #EFEFEF;
            font-size: 18px;
        }

        input[type="submit"] {
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

        input[type="submit"]:hover {
            background-color: #CDCDCD;
        }
    </style>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Usuário:</label><br>
        <input type="text" name="username" required><br>
        <label for="password">Senha:</label><br>
        <input type="password" name="password" required><br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
