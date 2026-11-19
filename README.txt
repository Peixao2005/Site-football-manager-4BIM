#  Football Manager

O **Football Manager** é um site para organizar campeonatos de futebol.

Nele é possível cadastrar times, jogadores e partidas, além de ver a tabela de classificação automaticamente.

##  Integrantes

- Murillo Martins de Paiva  
- Leonardo Silva Bonani  
- Tiago Matos Maia  
- Nicolas São Félix Nakhle  

**Turma:** 2°C²  

##  Funcionalidades

- Cadastro e login de usuários  
- Cadastro de times  
- Cadastro de jogadores  
- Registro de partidas e placares  
- Tabela de classificação automática  
- Cálculo de pontos, vitórias, empates e derrotas  

##  Tecnologias

- HTML  
- CSS  
- PHP  
- MySQL  

##  Como executar

1. Instale o XAMPP.
2. Inicie o Apache e o MySQL.
3. Coloque a pasta do projeto em `C:\xampp\htdocs`.
4. Importe o arquivo `database.sql` no phpMyAdmin.
5. Abra `http://localhost/football_manager/`.

##  Segurança

As senhas são protegidas com hash e os formulários usam token CSRF.

##  Classificação

- Vitória: 3 pontos  
- Empate: 1 ponto  
- Derrota: 0 ponto  

A tabela mostra pontos, jogos, vitórias, empates, derrotas, gols e saldo.
