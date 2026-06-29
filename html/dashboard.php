<?php
session_start();
require_once '../php/conexao.php';

// Se não estiver logado, retorna a tela de login. 
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

try {
    
    $sql = "SELECT r.*, u.nome AS usuario_nome, s.nome AS sala_nome 
            FROM reservas r 
            JOIN usuarios u ON r.id_usuario = u.id 
            JOIN salas s ON r.id_sala = s.id 
            ORDER BY r.data_reserva ASC, r.hora_inicio ASC";
    
    $stmt = $pdo->query($sql);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar reservas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Agendamento de Salas</title>
</head>
<body>
    <header>
        <div class="divHeader">
            <h1>Agendamento de Salas CIPS</h1>
        </div>
    </header>
            
    <main>
        <div class="container">
            <h2>Nova Reserva</h2>
            <form action="../php/reservar.php" method="POST">
                
                <label for="sala" class="formFont">Sala:</label>
                <select id="sala" name="sala" required>
                    <option value="">Selecione uma sala</option>
                    <option value="1">Espaço Relax</option>
                    <option value="2">Mídiateca</option>
                    <option value="3">Sala de espera</option>
                    <option value="4">Sala de jogos 1</option>
                    <option value="5">Sala de jogos 2</option>
                    <option value="6">Lab. de informática</option>
                    <option value="7">Parque</option>
                    <option value="8">Campo</option>
                    <option value="9">Auditório Espaço Vida</option>
                    <option value="10">Auditório Nelson Elias</option>
                    <option value="11">Lab. Prático</option>
                    <option value="12">Sala tecnológica</option>
                </select>
                <br><br>
                <label for="data" class="formFont">Data:</label>
                <input type="date" id="data" name="data"required>
                <br><br>

                <input type="checkbox" id="dia_todo" name="dia_todo" value="1" onchange="selecionaHoras()">
                <label for="dia_todo" class="formFont">Agendar o dia todo</label>
                <br><br>

                <label for="hora_inicio" class="formFont">Hora Início:</label><br>
                <input type="time" id="hora_inicio" name="hora_inicio" min="07:00" max="23:00" step="300" required>
                <br><br>

                <label for="hora_fim" class="formFont   ">Hora Fim:</label><br>
                <input type="time" id="hora_fim" name="hora_fim" min="07:00" max="23:00" step="300" required>
                <br><br>

                <button type="submit" class="button">Reservar</button>
            </form>
        </div>
            

        <div class="container">
            <h2>Salas Agendadas</h2>
            <div class="lista-reservas" style="text-align: left; width: 100%;">
                
                <?php if (empty($reservas)): ?>
                    <p style="text-align: center;">Nenhuma sala agendada no momento.</p>
                <?php else: ?>
                    <?php foreach ($reservas as $reserva): ?>
                        <div class="item-reserva" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">
                            <p><strong>Usuário:</strong> <?= htmlspecialchars($reserva['usuario_nome']) ?></p>
                            <p><strong>Sala:</strong> <?= htmlspecialchars($reserva['id_sala']) ?></p> 
                            <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($reserva['data_reserva'])) ?></p>
                            <p><strong>Horário:</strong> <?= substr($reserva['hora_inicio'], 0, 5) ?> às <?= substr($reserva['hora_fim'], 0, 5) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
        <div class="container">
            <h2>Horários de Aulas</h2> 
        </div>
    </main>
   

    <footer>
        <div class="divFooter">
            <p>Desenvolvido por Renan</p>
        </div>    
    </footer>

    <script src="../javascript/script.js"></script>
</body>
</html>