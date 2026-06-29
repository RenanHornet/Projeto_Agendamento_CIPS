<?php
session_start();
require_once 'conexao.php'; 

//Se não houver sessão ativa, manda de volta pro login.html
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../html/login.html");
    exit();
}

// Garante que os dados vieram via método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_usuario = $_SESSION['usuario_id']; 
    $id_sala    = $_POST['sala'];       
    $data       = $_POST['data'];       
    
    // Verifica se o checkbox "dia_todo" foi marcado
    if (isset($_POST['dia_todo']) && $_POST['dia_todo'] == '1') {
        $inicio = '07:00:00';
        $fim    = '23:00:00';
    } else {
        $inicio = $_POST['hora_inicio'];
        $fim    = $_POST['hora_fim'];
    }

    try {
      
        $sql_busca = "SELECT * FROM reservas 
                      WHERE id_sala = :id_sala 
                      AND data_reserva = :data_reserva 
                      AND (
                          (hora_inicio < :hora_fim AND hora_fim > :hora_inicio)
                      )";
        
        $stmt_busca = $pdo->prepare($sql_busca);
        $stmt_busca->execute([
            ':id_sala'      => $id_sala,
            ':data_reserva' => $data,
            ':hora_inicio'  => $inicio,
            ':hora_fim'     => $fim
        ]);

        //Sala já reservada
        if ($stmt_busca->rowCount() > 0) {
            echo "<script>
                    alert('Erro: Esta sala já está reservada para este horário!');
                    window.history.back(); 
                  </script>";
            exit();
        }

     
        $sql_insert = "INSERT INTO reservas (id_sala, id_usuario, data_reserva, hora_inicio, hora_fim) 
                       VALUES (:id_sala, :id_usuario, :data_reserva, :hora_inicio, :hora_fim)";
        
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':id_sala'      => $id_sala,
            ':id_usuario'   => $id_usuario,
            ':data_reserva' => $data,
            ':hora_inicio'  => $inicio,
            ':hora_fim'     => $fim
        ]);

        
        echo "<script>
                alert('Reserva realizada com sucesso!');
                window.location.href = '../html/dashboard.php';
              </script>";

    } catch (PDOException $e) {
        die("Erro ao salvar a reserva: " . $e->getMessage());
    }
} else {
    header("Location: ../html/dashboard.php");
    exit();
}