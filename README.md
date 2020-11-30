<h1>Flight Cluster</h1>

<p>Api que agrupa os voos por ida e volta.</p>

<br/>

### Tabela de conteúdos
   * [Instalação](#Instalação)
   * [Informações Importantes](#Informações-Importantes)
   * [Features](#Features)
   * [Tecnologias Utilizadas](#Tecnologias-Utilizadas)
   * [Github Actions e Heroku](#Github-Actions-e-Heroku)
   * [Autor][#Autor]
<br/>

# Instalação  

## Pré-requisitos

**É necessário ter o [Docker](https://docs.docker.com/get-docker/) e o [Docker Compose](https://docs.docker.com/compose/install/) instalados.**

<br/>


```bash
# Clone este repositório
$ git clone <https://github.com/marcossaore/flight_cluster>

# Acesse a pasta do projeto no terminal/cmd
$ cd flight_cluster

# Inicie as instâncias com o Docker Compose
$ docker-compose up -d

# Instale as depedências do projeto
$ docker-compose exec app composer install

# O servidor iniciará  - acesse <http://localhost/api/flights>
```
</br>

# Informações Importantes

**O docker compose está configurado para os serviços `app`, `nginx`, `githook_installer` e `phpunit`.**

1. app: É o serviço que corresponde a aplicação laravel.

2. nginx: É o serviço responsável em ser o servidor do serviço `app`

3. githook_installer: É um hack que cria um link simbólico para utilizar o docker-compose nos eventos do [Git Hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) você pode ver mais sobre [aqui](https://hackernoon.com/using-git-hooks-in-a-dockerized-environment-55372c40815f).

    * pre-commit: executa o `php-cs-fixer`.
    * pre-push: executa os testes unitários.

4. phpunit: É o serviço encarregado de executar o PHPUnit.

> Se você estiver usando o VSCode, para facilitar a nossa vida de desenvolvedor em [settings.json](./.vscode/settings.json) há uma configuração feita para o plugin [PHPUnit](https://marketplace.visualstudio.com/items?itemName=emallin.phpunit) e executa-o no container `phpunit`. Além disso, há uma lista de plugins recomendados em [extensions.json](./.vscode/extensions.json) que usei ao desenvolver este projeto. Assim que abrir o projeto no VSCode você será questionado se deseja instalar os plugins recomendados, caso não apareça, Clique no ícone de
`extensions` no `menu lateral`.  

<br/>

# Features

- [x] Voos Agrupados por ida e volta

<br/>

# Tecnologias Utilizadas

- [Laravel](https://laravel.com/)
- [Docker](https://www.docker.com/)

<br/>

# Github Actions e Heroku

o projeto possui deploy automatizado com o Github Actions e Heroku e pode ser testada [aqui](https://flightcluster.herokuapp.com/api/flights).

<br/>

### Autor
---

 <img style="border-radius: 50%;" src="https://avatars0.githubusercontent.com/u/13766539?s=460&u=4eef503e9da89bf83c44950bb5f3eb5fdaf98b0f&v=4" width="100px;" alt=""/>
 <br />
 <sub><b>Marcos Soares</b></sub>

[![Twitter Badge](https://img.shields.io/badge/-@marcossoares-1ca0f1?style=flat-square&labelColor=1ca0f1&logo=twitter&logoColor=white&link=https://twitter.com/marcoss17802528)](https://twitter.com/marcoss17802528) [![Linkedin Badge](https://img.shields.io/badge/-MarcosSoares-blue?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/marcos-soares-a2205b96/)](https://www.linkedin.com/in/marcos-soares-a2205b96/) 
