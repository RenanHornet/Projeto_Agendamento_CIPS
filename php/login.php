<?php
//elimina a sessão para salvar dados de usuário logado
session_start();

// importao o arquivo de conexão com o banco de dados
require_once 'conexao.php';

//verifica se o método do formulario é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch();

        
        if ($usuario) {
            
            if (password_verify($senha, $usuario['senha'])) {
                
                
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                
                header("Location: ../html/dashboard.php"); 
                exit();

            } else {
                echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('E-mail não cadastrado!'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "Erro no login: " . $e->getMessage();
    }
}
?>