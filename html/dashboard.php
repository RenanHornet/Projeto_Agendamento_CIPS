<?php
session_start();
require_once '../php/conexao.php';

// Se não estiver logado, retorna à tela de login. 
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

// Pega a data atual do SO
$data_filtro = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Calcula o dia anterior e o próximo dia
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
    <title>Agendamento de Salas - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>    
        <div class="divHeader">
            <div class="imagemLogo">
                <img src="../images/logo_cips.png" alt="Logo CIPS">
            </div>
            <h1>Agendamento de salas</h1>
            <a href="../php/logout.php" class="btnSair">
                <i class="bi bi-box-arrow-right me-1"></i> Sair
            </a>
        </div>
    </header>
            
    <main class="container-fluid px-3 px-md-4 py-4">
        <!-- Grid Responsivo Otimizado -->
        <div class="row g-4 align-items-stretch justify-content-center">
            
            <!-- COLUNA 1: Formulário de Nova Reserva -->
            <div class="col-12 col-md-6 col-xl-4 d-flex justify-content-center">
                <div class="container">
                    <h2 class="mb-4">Nova Reserva</h2>
                    <form action="../php/reservar.php" method="POST">
                        <div class="mb-3 text-start">
                            <label for="sala" class="formFont">Sala:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-door-open-fill textCips"></i></span>
                                <select id="sala" name="sala" class="form-select border-start-0" required>
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
                            </div>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="data" class="formFont">Data:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar-event textCips"></i></span>
                                <input type="date" id="data" name="data" class="form-control border-start-0" required>
                            </div>
                        </div>

                        <div class="form-check text-start mb-3">
                            <input class="form-check-input" type="checkbox" id="dia_todo" name="dia_todo" value="1" onchange="selecionaHoras()">
                            <label class="form-check-label formFont ms-1" for="dia_todo">Agendar o dia todo</label>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="hora_inicio" class="formFont">Hora Início:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-clock textCips"></i></span>
                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control border-start-0" min="07:00" max="23:00" step="300" required>
                            </div>
                        </div>

                        <div class="mb-4 text-start">
                            <label for="hora_fim" class="formFont">Hora Fim:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-clock-fill textCips"></i></span>
                                <input type="time" id="hora_fim" name="hora_fim" class="form-control border-start-0" min="07:00" max="23:00" step="300" required>
                            </div>
                        </div>

                        <button type="submit" class="button w-100">Reservar</button>
                    </form>
                </div>
            </div>
            
            <!-- COLUNA 2: Lista de Salas Agendadas -->
            <div class="col-12 col-md-6 col-xl-4 d-flex justify-content-center">
                <div class="listaReservas">
                    <h2 class="mb-3">Salas Agendadas</h2>

                    <div class="navegacaoData">
                        <a href="dashboard.php?data=<?= $data_anterior ?>" class="btnSeta" title="Dia Anterior">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        
                        <span class="dataAtual">
                            <i class="bi bi-calendar3 textCips me-1"></i><?= date('d/m/Y', strtotime($data_filtro)) ?> 
                            <?= ($data_filtro == date('Y-m-d')) ? '<strong class="ms-1">(Hoje)</strong>' : '' ?>
                        </span>
                        
                        <a href="dashboard.php?data=<?= $data_proxima ?>" class="btnSeta" title="Próximo Dia">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div> 

                    <div class="scrollInterno">
                        <?php if (empty($reservas)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x display-5 text-muted d-block mb-2"></i>
                                <p class="text-muted fw-medium mb-0">Nenhuma sala agendada para esta data.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($reservas as $reserva): ?>
                                <div class="itemReserva">
                                    <p class="mb-1"><strong>Usuário:</strong> <?= htmlspecialchars($reserva['usuario_nome']) ?></p>
                                    <p class="mb-1"><strong>Sala:</strong> <?= htmlspecialchars($reserva['sala_nome']) ?></p> 
                                    <p class="mb-1"><strong>Data:</strong> <?= date('d/m/Y', strtotime($reserva['data_reserva'])) ?></p>
                                    <p class="mb-2"><strong>Horário:</strong> <?= substr($reserva['hora_inicio'], 0, 5) ?> às <?= substr($reserva['hora_fim'], 0, 5) ?></p>
                                    
                                    <div class="text-end mt-2">
                                        <form action="../php/cancelar_reserva.php" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $reserva['id']?>">
                                            <button type="submit" class="btnCancelar border-0 px-3 py-1" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?');">
                                                <i class="bi bi-trash me-1"></i> Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- COLUNA 3: Indicadores de Uso -->
            <div class="col-12 col-md-6 col-xl-4 d-flex justify-content-center">
                <div class="container">
                    <h2>Indicadores de Uso</h2>
                    <h3 class="h6 text-muted mb-3">Salas mais requisitadas:</h3>
                    <div style="position: relative; width: 100%; height: 320px;">
                        <canvas id="graficoSalas"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <div class="divFooter">
            <p class="mb-0">Desenvolvido por Renan Albino Horne (<a href="https://github.com/RenanHornet" target="_blank" class="linkGit"><i class="bi bi-github"></i>GitHub</a>)</p>
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
            type: 'pie',
            data: {
                labels: labelsSalas,
                datasets: [{
                    label: 'Total de Agendamentos',
                    data: dadosReservas,
                    backgroundColor: [
                        'rgba(3, 12, 143, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(46, 204, 113, 0.8)',
                        'rgba(230, 126, 34, 0.8)',
                        'rgba(155, 89, 182, 0.8)',
                        'rgba(26, 188, 156, 0.8)',
                        'rgba(127, 140, 141, 0.8)'
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>