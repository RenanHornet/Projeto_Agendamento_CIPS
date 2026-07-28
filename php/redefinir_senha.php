<?php
session_start();
require_once 'conexao.php';

//verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    //verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        echo "<script>
                alert('As senhas digitadas não coincidem!');
                window.location.href = '../html/esqueci_senha.html';
              </script>";
        exit();
    }

    //verifica se o email existe no banco de dados
    try {
        $sql_checa_email = "SELECT id FROM usuarios WHERE email = :email";
        $stmt_checa = $pdo->prepare($sql_checa_email);
        $stmt_checa->execute([':email' => $email]);

        if ($stmt_checa->rowCount() === 0) {
            echo "<script>
                    alert('E-mail não encontrado no sistema!');
                    window.location.href = '../html/esqueci_senha.html';
                  </script>";
            exit();
        }
        //atualiza a senha no banco de dados com hash de senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql_update = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':senha' => $nova_senha_hash,
            ':email' => $email
        ]);

        echo "<script>
                alert('Senha alterada com sucesso! Faça login com a sua nova senha.');
                window.location.href = '../index.html';
              </script>";
        exit();

    } catch (PDOException $e) {
        die("Erro ao redefinir a senha: " . $e->getMessage());
    }
} else {
    header("Location: ../index.html");
    exit();
}
?>