<h1><b>CRUD MUNDO</b></h1>

<h2><b>Sobre o projeto</b></h2>

Um projeto que permite o gerenciamento de informações geográficas, envolvendo o cadastro, consulta e associação de continentes, países, cidades e seus governantes.

<h2><b>Tenologias utilizadas</b></h2>

| **Categoria** | **Tecnologia** |
|------------|------------|
| Backend | PHP |
| Frontend | HTML5, CSS3, JavaScript |
| IDE | Visual Studio Code|
| SGBD | MySql |
| Ambiente de Desenvolvimento | XAMPP |
| Plataforma de Hospedagem | GitHub |

<h2><b>Funcionalidades do projeto</b></h2>

- Cadastro de países + Associação a cidades
- Cadastro de países + Seus governantes
- Cadastro de cidades + Seus governantes
- Editar os dados de continentes, países, cidades e governantes
- Exclusão de registros

<h2><b>Estrutura do projeto</b></h2>

```text
PW3
│
├── CRUD Mundo/
  │
  ├── backend/
     ├── database.php/
  ├── bancodedados/
     ├── bd_mundo.sql/
  ├── telas/
     ├── alterar_senha.php/
     ├── cidades.php/
     ├── continentes.php/
     ├── dashboard.php/
     ├── governantes.php/
     ├── paises.php/
  │
  ├── login.php
  ├── logout.php
  ├── style.css
  └── README.md
```

<h2><b>Para testar o projeto (XAMPP)</b></h2>

1. Clone o repositório:

```bash
git clone https://github.com/SofiaAkemi/PW3
```

2. Copie ou mova a pasta do projeto para o diretório `htdocs` do XAMPP:

```text
C:\xampp\htdocs\PW3
```

3. Abra o **XAMPP Control Panel** e inicie os serviços **Apache** e **MySQL**.

4. Abra o navegador e acesse:

```text
http://localhost/PW3/CRUD%20MundoS
```

5. Importe o arquivo `.sql` na pasta `bancodedados` no projeto a partir do **phpMyAdmin** ou **MySQL Workbench** e configure as credenciais de acesso conforme necessário.

<h2><b>Autora</b></h2>

**Sofia Akemi Arakaki Kucinskis.**
