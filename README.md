
# Meu Campeonato

## Sobre o Projeto
"Meu Campeonato" é uma aplicação desenvolvida para simular campeonatos de futebol. O projeto implementa um sistema eliminatório que começa nas quartas de final, onde oito times competem até a final para determinar o campeão.

## Tecnologias Utilizadas
- **Backend:** PHP (Laravel)
- **Frontend**: Templates do Laravel(Blade)
- **Banco de Dados:** SQLite
- **Script para Placar dos Jogos:** Python

## Requisitos do Sistema
- Composer
- Php
- Docker

## Funcionalidades
- Inserção de oito times participantes do campeonato.
- Simulação de um campeonato eliminatório começando nas quartas de final.
- Chaveamento automático dos jogos.
- Simulação de resultados das partidas com um script Python.
- Determinação do vencedor do campeonato.
- Ranking top 3.
- Registro e recuperação de informações de campeonatos anteriores.
- Criterio de desempate com penaltis.
- Opção para deletar campeonatos anteriores.

## Passos para Execução do Projeto

### 1. Clonar o Repositório.
```bash
git clone https://github.com/Luis020-hub/meu-campeonato.git
```

### 2. Executar o composer.
- Abra seu terminal do editor.
```bash
cd meu-campeonato
```
```bash
Composer install
```
Logo em seguida será instalado todas as depêndencias do projeto.

### 3. Buildar a aplicação.
``` bash
docker-compose build
```

### 4. Executar a aplicação.
```bash
docker-compose up
```

### 5. Acessando Meu Campeonato.
- Você pode acessar os links:
- http://127.0.0.1:8000/ ou http://localhost:8000/
- Para ver o projeto em execução.

### 6. Testes
Para executar os testes:
```bash
php artisan test
```
e
```bash
php artisan test --filter=test_em_especifico
```

### Rotas da Aplicação
#### GET /
- Exibe a página inicial da aplicação.

#### GET /getToken
- Retorna o token CSRF.

#### POST
- Simula um novo campeonato

#### GET /historic
- Exibe a lista de campeonatos simulados anteriormente.

#### GET /historic/{id}
- Exibe os detalhes de um campeonato em específico.

#### DELETE /historic/{id}
- Exclui um campenato em específico.

## Estrutura do Projeto
- O projeto usa a estrutura padrão do Laravel com pequenas alterações.
