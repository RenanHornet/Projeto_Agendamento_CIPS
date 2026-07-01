<?php
session_start();
require_once '../php/conexao.php';

// Se não estiver logado, retorna a tela de login. 
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

//pega a data atual do SO
$data_filtro = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

//calcula o dia anterior e o próximo dia
$data_anterior = date('Y-m-d', strtotime($data_filtro . '-1 day'));
$data_proxima = date('Y-m-d', strtotime($data_filtro . '+1 day'));

try {
    
    $sql = "SELECT r.*, u.nome AS usuario_nome, s.nome AS sala_nome 
    FROM reservas r 
    JOIN usuarios u ON r.id_usuario = u.id 
    JOIN salas s ON r.id_sala = s.id 
    WHERE r.data_reserva = :data_filtro
    ORDER BY r.hora_inicio ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':data_filtro' => $data_filtro]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar reservas: " . $e->getMessage());
}

try {
    
    $sql_grafico = "SELECT s.nome AS sala_nome, COUNT(r.id) AS total_reservas 
    FROM reservas r 
    JOIN salas s ON r.id_sala = s.id 
    GROUP BY r.id_sala
    ORDER BY total_reservas DESC";
    
    $stmt_grafico = $pdo->query($sql_grafico);
    $dados_grafico = $stmt_grafico->fetchAll(PDO::FETCH_ASSOC);

    $nomes_salas = [];
    $quantidades = [];

    foreach ($dados_grafico as $linha) {
        $nomes_salas[] = $linha['sala_nome'];
        $quantidades[] = $linha['total_reservas'];
    }
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
            <div class="imagemLogo">
                <img src="../images/logo_cips.png" class="imagemLogo" alt="Logo CIPS">
            </div>
            <h1>Agendamento de salas </h1>
            <a href="../php/logout.php" class= "btnSair">Sair ➔</a>
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
            
        <div class="listaReservas">
            <h2>Salas Agendadas</h2>
            
            <div class="navegacaoData">
                <a href="dashboard.php?data=<?= $data_anterior ?>" class="btnSeta">&lt; Anterior</a>
                <span class="dataAtual">
                    📅 <?= date('d/m/Y', strtotime($data_filtro)) ?> 
                    <?= ($data_filtro == date('Y-m-d')) ? '<strong>(Hoje)</strong>' : '' ?>
                </span>
                <a href="dashboard.php?data=<?= $data_proxima ?>" class="btnSeta">Próximo &gt;</a>
            </div> 

            <div class="scrollInterno">
                <?php if (empty($reservas)): ?>
                    <p style="text-align: center;">Nenhuma sala agendada no momento.</p>
                <?php else: ?>
                    <?php foreach ($reservas as $reserva): ?>
                        <div class="itemReserva">
                            <p><strong>Usuário:</strong> <?= htmlspecialchars($reserva['usuario_nome']) ?></p>
                            <p><strong>Sala:</strong> <?= htmlspecialchars($reserva['sala_nome']) ?></p> 
                            <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($reserva['data_reserva'])) ?></p>
                            <p><strong>Horário:</strong> <?= substr($reserva['hora_inicio'], 0, 5) ?> às <?= substr($reserva['hora_fim'], 0, 5) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <h2>Indicadores de Uso</h2>
            <h3>Salas mais requisitadas:</h3>
            <div style="position: relative; width: 100%; height: 300px;">
                <canvas id= "graficoSalas"></canvas>
            </div>
        </div>
    </main>
   

    <footer>
        <div class="divFooter">
            <p>Desenvolvido por Renan</p>
        </div>    
    </footer>

    <script src="../javascript/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Passa os arrays do PHP diretamente para variáveis do JavaScript
        const labelsSalas = <?php echo json_encode($nomes_salas); ?>;
        const dadosReservas = <?php echo json_encode($quantidades); ?>;
        // Configura e renderiza o gráfico de Pizza
        const ctx = document.getElementById('graficoSalas').getContext('2d');
        new Chart(ctx, {
            type: 'pie', // 🔥 MUDANÇA AQUI: Alterado de 'bar' para 'pie'
            data: {
                labels: labelsSalas,
                datasets: [{
                    label: 'Total de Agendamentos',
                    data: dadosReservas,
                    // 💡 Dica extra para o gráfico de pizza abaixo:
                    backgroundColor: [
                        'rgba(3, 12, 143, 0.7)',  /* Azul CIPS */
                        'rgba(255, 159, 64, 0.7)', /* Laranja */
                        'rgba(75, 192, 192, 0.7)', /* Verde Água */
                        'rgba(153, 102, 255, 0.7)',/* Roxo */
                        'rgba(255, 99, 132, 0.7)'  /* Rosa/Vermelho */
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>