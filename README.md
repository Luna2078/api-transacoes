# api-transacoes

A api roda na porta 8000, ficando por exemplo localhost:8000/api/v1/users.
Possuindo no total 8 endpoints, sendo eles: <br>
<br> Na parte de cadastro de usuário:

- Cadastrar um usuário - POST /api/v1/users
- Listar um usuário com o ID - GET /api/v1/users/{user_id}
- Atualizar um usuário - PUT /api/v1/users/{user_id}
- Deletar um usuário - DELETE /api/v1/users/{user_id}

<br> Na parte de transações:

- Cadastrar uma transação - POST /api/v1/transactions
- Listar uma transação com o ID - GET /api/v1/transactions/{transaction_id}
- Pedir reembolso de uma transação - POST /api/v1/transactions/refund/{transaction_id}

<br> E uma rota para os logs sendo: GET /api/v1/logs

## Requisitos
- Docker
- Make

## Body para cadastro de usuário:
O body do cadastro e do update de usuário é o mesmo, sendo:
```json
{
  "name": "string",
  "email": "string",
  "cpf_cnpj": "string",
  "balance": "float",
  "type": "int",
  "password": "string"
}
```
## Body para cadastro de transação:
```json
{
  "payer": "int",
  "payee": "int",
  "value": "float"
}
```
## Docker:
Para executar o processo execute o comando: make deploy
<br>O projeto irá rodar na porta 8080