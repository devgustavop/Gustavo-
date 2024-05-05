<?php
session_start();

// Verifica se o usuário e a senha estão corretos (esse é um exemplo muito simples, é recomendável usar criptografia de senha na prática)
if ($_POST['username'] === 'user' && $_POST['password'] === 'pass') {
    $_SESSION['username'] = $_POST['username'];
    header('Location: home.php');
    exit;
} else {
    echo "Credenciais inválidas. <a href='index.php'>Tente novamente</a>.";
}
?>
