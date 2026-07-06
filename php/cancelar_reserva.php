<?php 
session_start();
require_once "conexao.php";

if(!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.html");
    exit();
}

//verifica se o ID da reserva foi enviado pelo URL (via método POST)
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id_reserva = $_POST ['id'];
    $id_usuario = $_SESSION['usuario_id'];
    
    try{
        //só exclui se o id da reserva e o do usuário forem iguais
        $sql = "DELETE FROM reservas WHERE id = :id_reserva AND id_usuario = :id_usuario";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        //verifica e alfuma linha realmente foi apagada no banco 
        if ($stmt->rowCount() > 0){
            echo"<script>alert('Agendamento cancelado com sucesso!'); window.location.href = '../html/dashboard.php';</script>";
        } else {
            echo"<script>alert('Erro: Você não tem permissão para cancelar este agendamento'); window.location.href = '../html/dashboard.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro ao excluir agendamento: " . $e->getMessage();
    }
} else {
    //se nenhum ID foi passado, joga de volta para o painel
    header("Location: ../html/dashboard.php");
    exit();
}
?>