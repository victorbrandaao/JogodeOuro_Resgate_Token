VERSÃO 1.0

Jogo de Ouro - Resgate de Token 🏆

Bem-vindo ao repositório do sistema de resgate de tokens do Jogo de Ouro! Este sistema permite que os jogadores insiram seu login e resgatem um token associado a ele.

🚀 Começando

Para começar a usar este sistema em seu ambiente local, siga as instruções abaixo.

Pré-requisitos
PHP 7.x ou superior
MySQL ou MariaDB
Instalação
1. Clone este repositório:

```

git clone https://github.com/victorbrandaao/JogodeOuro_Resgate_Token.git

```

2. Atualize as credenciais do banco de dados no arquivo principal.
3. Crie um banco de dados e importe qualquer esquema necessário.
4. Acesse o sistema via navegador!

🛠️ Funcionalidades

Conexão Segura com o Banco de Dados: Utilizando PDO para proteger contra ataques de injeção SQL.
Validação de Entrada: Antes de qualquer operação no banco de dados, o sistema valida as entradas para garantir a integridade dos dados.
Mensagens de Feedback: O sistema fornece feedback claro para o usuário após cada ação.

🔒 Segurança

Este sistema utiliza práticas recomendadas para garantir a segurança dos dados:

Filtragem de Entrada: Todos os dados de entrada são filtrados e validados.
Tratamento de Erros: Mensagens de erro do banco de dados não são exibidas diretamente ao usuário, evitando exposição de detalhes sensíveis.
Prevenção de Injeção SQL: Utilizando consultas preparadas do PDO para evitar injeção SQL.
🤝 Contribuição

Contribuições são muito bem-vindas! Se você tem sugestões ou melhorias, sinta-se à vontade para abrir uma issue ou enviar um pull request.

📝 Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo LICENSE.md para mais detalhes.


