# 🏫 CIPS - Sistema de Agendamento de Salas

O **CIPS - Agendamento de Salas** é uma aplicação web completa, leve e responsiva desenvolvida para gerenciar a reserva de espaços e salas de uma instituição de ensino/atendimento. O projeto foca em entregar uma experiência de usuário (UX) fluida, controle estrito de segurança no backend e indicadores visuais para tomada de decisão.

---

## 🚀 Funcionalidades Principais

* **🔒 Autenticação e Segurança:** Controle de acesso seguro por meio de sessões PHP (`session_start`). Senhas criptografadas no banco de dados e proteção contra acessos diretos a arquivos restritos.
* **📅 Dashboard Dinâmico:** Painel de controle com navegação cronológica (anterior/próximo dia) para visualização rápida das salas ocupadas em tempo real.
* **📊 Indicadores de Uso (BI):** Gráfico de pizza/rosca interativo integrado via **Chart.js**, exibindo em tempo real as salas mais requisitadas para auxiliar a diretoria no planejamento de espaços.
* **📱 Interface 100% Responsiva:** Layout customizado via CSS Grid/Flexbox estruturado do zero, adaptando-se perfeitamente de monitores desktop a telas de smartphones (Mobile-First mindset no cabeçalho e formulários).
* **♻️ Rolagem Inteligente:** Containers internos isolados para listagem de dados, impedindo que grandes volumes de agendamentos quebrem o design da página.

---

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.x (Arquitetura limpa com manipulação de sessões nativas)
* **Banco de Dados:** MySQL / MariaDB utilizando a extensão **PDO** (proteção nativa contra SQL Injection)
* **Frontend:** HTML5, CSS3 (Customizado, sem frameworks pesados como Bootstrap)
* **Gráficos e Inteligência:** JavaScript Puro (Vanilla JS) + **Chart.js** (via CDN)

---

## 🎨 Destaques de Engenharia de Software & UX

1. **Layout Fluido (Box-Sizing Reset):** Uso de técnicas modernas de CSS como `box-sizing: border-box` para garantir consistência milimétrica no alinhamento de componentes em qualquer resolução.
2. **Gerenciamento de Estado de Sessão:** Sistema de Logout (`logout.php`) robusto que limpa as variáveis globais e destrói os tokens de sessão de forma segura no servidor.

---

📄 Licença
Este projeto é de uso exclusivo e acadêmico/institucional para o CIPS.

Developed by RenanHornet🚀

## ⚙️ Como Executar o Projeto Localmente

---

### Pré-requisitos
* Servidor local Apache com PHP instalado (Recomendado: **XAMPP**, **WampServer** ou **Laragon**).
* Banco de Dados MySQL.

### Passo a Passo
1. Clone o repositório para a pasta do seu servidor local (ex: `htdocs` ou `www`):
   ```bash
   git clone [https://github.com/seu-usuario/cips-agendamento-salas.git](https://github.com/seu-usuario/cips-agendamento-salas.git)
