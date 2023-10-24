<?php
require 'config.php';

$servername = $config['db_host'];
$username = $config['db_user'];
$password = $config['db_pass'];
$dbname = $config['db_name'];

$message = null;
$tokenRetrieved = false; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Ocorreu um erro. Por favor, tente novamente mais tarde.");
}

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
                $message = "Copie este código para realizar o resgate: ";
                $tokenRetrieved = true;

                $stmt = $conn->prepare("DELETE FROM jogadores WHERE login = :login");
                $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $message = "Login não encontrado.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
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
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body style="text-align: center;">
<div id="loader">
    <img src="logo.png" alt="Logo" id="loading-logo">
</div>

<div class="mainContent">
<?php if (!$tokenRetrieved): ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateForm()">
        Digite seu login para resgatar o bônus: <input type="text" name="login" required>
        <br><br>
        <input type="submit" name="submit" value="QUERO MEU BÔNUS">
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
    <?php endif; ?>

    <?php
    if ($message !== null) { // Alteração aqui para verificar se $message é diferente de null
        echo "<div class='messageContainer'>";
        echo "<p><b>" . htmlspecialchars($message) . "</b></p>";
        if ($tokenRetrieved) {
            echo "<div class='tokenBox'><input type='text' value='" . $token . "' id='userToken' readonly>";
            echo "<button onclick='copyToken()'>Copiar Token</button></div>";
            echo "<br><br>";
            echo "<button class='resgateBtn' onclick=\"window.location.href='https://www.jogodeouro.bet/dashboard/promotions?openDialogToken=true'\">CLIQUE AQUI PARA RESGATAR</button>";
        }
        echo "</div>";
    }
    ?>
</div>

<script>
    function copyToken() {
        var tokenInput = document.getElementById('userToken');
        tokenInput.select();
        document.execCommand("copy");
        alert("Token copiado com sucesso!");
    }
</script>

<script>
    var loader = document.getElementById('loader');
    var content = document.getElementById('content');
    
    window.addEventListener('load', function() {
        loader.classList.add('fadeOut');
        setTimeout(function() {
            loader.style.display = 'none';
            content.style.display = 'block';
        }, 1000);
    });
</script>

</body>
</html>