<?php 
session_start();
require_once 'conexao.php';

//só processa dados via post
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = trim($_POST['nome']); //trim limpa os espaços extras
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    //impede que os campos sejam enviados vazios
    if (empty($nome) || empty($email) || empty($senha)) {
        echo "<script> alert('Por favor, preencha todos os campos.'); window.history.back(); </script>";
        exit();
    }

    try {
        //verifica se o email já está cadastrado
        $sql_busca = "SELECT id FROM usuarios WHERE email = :email";
        $stmt_busca = $pdo->prepare($sql_busca);
        $stmt_busca->execute([':email' => $email]);
        
        if ($stmt_busca->rowCount() > 0){
            echo "<script> alert('Email já cadastrado'); window.history.back(); </script>";
            exit();
        }

        //hash de senha
        $senha_cripto = password_hash($senha, PASSWORD_DEFAULT);

        //isere novo usuário no banco de dados
        $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha_cripto //já com hash
        ]);

        echo "<script> alert('Cadastro realizado com sucesso!'); window.location.href = '../html/index.html'; </script>";
        exit();

    } catch (PDOException $e) {
        die("Erro ao realizar o cadastro: ".$e->getMessage());
    }
} else {
    // se tentar diretamente, volta para o formulário
    header("Location: ../html/cadastro.html");
    exit();
}
?>