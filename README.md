# CredEasy API

❓ A API da CredEasy facilita o acesso a todos os recursos do sistema.

## API Endpoints

O URL base para a API é: `https://credeasy.com/api/`

## Erros

Caso ocorra um erro durante o processamento da sua requisição, a API irá retornar a mensagem de erro junto com o código HTTP apropriado, como no exemplo:
```json
    {
        "status": "Erro",
        "message": "O recurso não foi encontrado",
        "data": null
    }
```
---

## Cliente

Ao requerir um cliente, a resposta retornada (em caso de sucesso) será assim:
```json
{
    "status": "Sucesso",
    "message": "",
    "data": {
        "cliente": {
            "id": 1,
            "cpf": "000.000.000-00",
            "email": "cliente@email.com",
            "numero_celular": "0000000000",
            "nome": "Foo",
            "endereco": "Brasil",
            "profissao": "Desenvolvedor",
            "tipo": "COMUM",
            "renda": 10000
        },
    }
}
```

Já caso seja requerido os empréstimos do cliente:
```json
{
    "status": "Sucesso",
    "message": "",
    "data": {
        "emprestimos": [
            {
                "id": 1,
                "nome": "Bar",
                "valor": "1000",
                "valor_final": "1200",
                "taxa_juros": 1,
                "qtd_parcelas": 6,
                "status": "SOLICITADO",
                "data_solicitacao": "2022-08-24 17:13:10",
                "data_quitacao": null,
                "cliente_id": 1
            }
        ]
    }
}
```

### Cliente autenticado
> #### <span style="color: orange;"> GET </span> `/clientes/@eu`
#
### Cliente
> #### <span style="color: orange;"> GET </span> `/clientes/{cliente.id}`
#
### Empréstimos do cliente autenticado
> #### <span style="color: orange;"> GET </span> `/clientes/@eu/emprestimos`
#
### Empréstimos do cliente
> #### <span style="color: orange;"> GET </span> `/clientes/{cliente.id}/emprestimos`
#
### Todos clientes
> #### <span style="color: orange;"> GET </span> `/clientes`
#
### Registrar novo cliente
> #### <span style="color: salmon;"> POST </span> `/clientes`
> *Não retorna o cliente, a autenticação deve ser feita separadamente*
#
---
## Empréstimos

Ao acessar um empréstimo a resposta será assim: 
```json
{
    "status": "Sucesso",
    "message": "",
    "data": {
        "emprestimo": {
            "id": 1,
            "nome": "Bar",
            "valor": "1000",
            "valor_final": "1200",
            "taxa_juros": 1,
            "qtd_parcelas": 6,
            "status": "SOLICITADO",
            "data_solicitacao": "2022-08-24 17:13:10",
            "data_quitacao": null,
            "cliente_id": 1
        }
    }
}
```
#
Ou ao acessar uma lista de empréstimos:
```json
{
    "status": "Sucesso",
    "message": "",
    "data": {
        "emprestimos": [
            {
                "id": 1,
                "nome": "Bar",
                "valor": "1000",
                "valor_final": "1200",
                "taxa_juros": 1,
                "qtd_parcelas": 6,
                "status": "SOLICITADO",
                "data_solicitacao": "2022-08-24 17:13:10",
                "data_quitacao": null,
                "cliente_id": 1
            }
        ]
    }
}
```
#
### Empréstimo
> #### <span style="color: orange;"> GET </span> `/emprestimos/{emprestimo.id}`
#
### Todos empréstimos
> #### <span style="color: orange;"> GET </span> `/emprestimos`
#
### Parcelas do empréstimo
> #### <span style="color: orange;"> GET </span> `/emprestimos/{emprestimo.id}/parcelas`
#
### Registrar um novo empréstimo
> #### <span style="color: salmon;"> POST </span> `/emprestimos`
> *Retorna uma mensagem de sucesso*
> *Retorna o objeto do empréstimo criado*
#
---
## Parcelas
Objeto de resposta ao acessar uma parcela:
```json
{
    "status": "Sucesso",
    "message": "",
    "data": {
        "parcela": {
            "id": 1,
            "valor": 1000,
            "numero": 1,
            "taxa_multa": 1,
            "valor_pago": null,
            "data_vencimento": "2022-08-24 17:13:10",
            "data_pagamento": null,
            "status": "ABERTA",
            "emprestimo_id": 1
        }
    }
}
```
### Parcela
> #### <span style="color: orange;"> GET </span> `/parcelas/{parcela.id}`
### Pagar uma parcela
> #### <span style="color: orange;"> PATCH </span> `/parcelas/{parcela.id}`
> **No corpo da requisição devem constar os seguintes campos:**
> - taxa_juros (10 - 20)
> - status (true / false)
>
> *Retorna uma mensagem de sucesso*
> *Retorna o objeto da parcela paga*
#
---
## Autenticação
Para realizar a autenticação no sistema da CredEasy você deve fazer a seguinte requisição:
> #### <span style="color: salmon;"> POST </span> `/login`
> *Devem ser enviados os campos: email, senha*
#
Caso os dados enviados estejam corretos a resposta será a seguinte:
```json
{
    "token": "token_aqui",
    "id": 1
    // O ID é o ID do cliente
}
```
Já caso os dados não estejam corretos será enviada uma resposta com o status 401.
#
### Fazendo requisições autenticadas
Para fazer com que as requisições sejam autenticadas você deve adicionar o header `Authorization` e como valor dele adicionar `Bearer token_aqui` 
