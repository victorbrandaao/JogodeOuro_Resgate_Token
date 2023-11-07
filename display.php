<?php
$servername = "localhost";
$username = "#";
$password = "#";
$dbname = "#";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());  // Log the error
    die("Ocorreu um erro. Por favor, tente novamente mais tarde.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);

    if(empty($login)) {
        $message = "Login não pode estar vazio.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT token FROM jogadores WHERE login = :login");
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $token = htmlspecialchars($row['token']);
                $message = "Seu token é: " . $token;

                $stmt = $conn->prepare("DELETE FROM jogadores WHERE login = :login");
                $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $message = "Login não encontrado.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());  // Log the error
            $message = "Ocorreu um erro. Por favor, tente novamente mais tarde.";
        }
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Ouro - Resgate de Token</title>
    <link rel="icon" type="image/png" href="https://d1s3ak279u1qfe.cloudfront.net/domains/jogodeourobet/img/icons/favicon-32x32.png">
    <link rel="stylesheet" type="text/css" href="styles_index.css">
</head>
<body>
<div id="loader">
    <img src="https://d1s3ak279u1qfe.cloudfront.net/domains/jogodeourobet/img/logo.png" alt="Logo" id="loading-logo">
</div>

<div id="content" style="display:none;">
    <div id="topbar">
        <img src="https://d1s3ak279u1qfe.cloudfront.net/domains/jogodeourobet/img/logo.png" alt="Logo" onclick="backToHome()">
        <div>
            <button id="loginBtn" class="button" onclick="window.location.href='https://jogodeouro.bet/'">ENTRAR</button>
        </div>
    </div>
    <h2>Insira seu login para resgatar seu token:</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateForm()">
        Login: <input type="text" name="login" required>
        <br><br>
        <input type="submit" name="submit" value="Resgatar Token">
    </form>

    <script>
        function validateForm() {
            var login = document.forms[0]["login"].value;
            if (login == "") {
                alert("Login não pode estar vazio");
                return false;
            }
            return true;
        }
    </script>

    <?php
    if (!empty($message)) {
        echo "<p><b>" . htmlspecialchars($message) . "</b></p>";
    }
    ?>
</div>

<script>
    var loader = document.getElementById('loader');
    var content = document.getElementById('content');
    
    window.addEventListener('load', function() {
        loader.classList.add('fadeOut');
        setTimeout(function() {
            loader.style.display = 'none';  // Esconda o loader
            content.style.display = 'block';  // Mostre o conteúdo
        }, 1000);  // Delay de 1 segundo para garantir uma transição suave
    });
</script>

</body>
</html>
