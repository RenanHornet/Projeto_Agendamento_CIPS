<?php 
session_start();

//limpa as variáveis de sessão
$_SESSION = array();

//destrói a sessão do servidor
session_destroy();

//redireciona de volta à tela de login
header("Location: ../html/index.html");
exit();
?>