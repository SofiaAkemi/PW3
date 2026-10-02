<h1><b>PW3</b></h1>

<h2><b>Sobre o projeto</b></h2>

Breve descrição do projeto e de seu objetivo.

## Funcionalidades
- Funcionalidade 1
- Funcionalidade 2
- Funcionalidade 3
## Tecnologias utilizadas
- Tecnologia 1
- Tecnologia 2
- Tecnologia 3
## Estrutura do projeto
Breve explicação sobre as principais pastas e arquivos.
## Como executar
Instruções básicas necessárias para executar o projeto.
## Autor
Nome do aluno

O **Gald** é um **Trabalho de Conclusão de Curso** (TCC) desenvolvido por nós alunos do curso de Desenvolvimento de Sistemas da **ETEC Prof.ª Ilza Nascimento Pintus**, em São José dos Campos.

O projeto consiste em um sistema web voltado para a **gestão de armários escolares e livros didáticos**, buscando modernizar e automatizar processos que atualmente são realizados de forma manual.

## 📚 Como é dividido

Ele é dividido em dois módulos:
- **Livros:** Área onde alunos podem solicitar empréstimos, ver os seus livros emprestados, acompanhar datas de devolução e onde os administradores podem cadastrar livros, alterá-los, gerenciar volumes individuais, etc.
- **Armários**: Uma plataforma que possibilita a visão do mapa de armários para os interessados junto à reserva e pagamento dos armários automaticamente, além da adição e remoção de novas áreas e armários. 

## 📸 Capturas de Tela

Aqui estão algumas das telas do nosso projeto:

<p align="center">
    <img src="readme_imgs/gald_login.png" width="45%">
    <img src="readme_imgs/gald_ferramentas.png" width="45%">
</p>

<p align="center">
    <img src="readme_imgs/gald_livros.png" width="45%">
    <img src="readme_imgs/gald_armarios.png" width="45%">
</p>
<p align="center">
    <i>(Imagens da prototipação)</i>
</p>


## 🛠️ Ferramentas usadas

| Categoria | Ferramentas |
|------------|------------|
| Back-end | PHP |
| Front-end | HTML5, CSS3, JavaScript |
| Design | Figma |
| Ambiente de Desenvolvimento | XAMPP |
| Gerenciamento de Projeto | Jira |

## 🕹️ Como testar o projeto (XAMPP)

1. Clone o repositório:

```bash
git clone https://github.com/M0C-Dev/TCC-Gald-2026
```

2. Copie ou mova a pasta do projeto para o diretório `htdocs` do XAMPP:

```text
C:\xampp\htdocs\TCC-Gald-2026
```

3. Abra o **XAMPP Control Panel** e inicie os serviços **Apache** e **MySQL**.

4. Abra o navegador e acesse:

```text
http://localhost/TCC-Gald-2026
```

5. Importe o arquivo `.sql` na pasta `database` no projeto a partir do **phpMyAdmin** ou **SQLWorkbench** e configure as credenciais de acesso conforme necessário.

## 📂 Estrutura do Projeto

O projeto está organizado em pastas para facilitar a manutenção e o desenvolvimento! A estrutura se dá da seguinte forma:

```text
TCC-Gald-2026/
│
├── assets/
├── includes/
├── pages/
├── database/
├── docs/
├── uploads/
│
├── index.php
├── login.php
├── logout.php
└── README.md
```

| Pasta      | Descrição                                                                   |
| ---------- | --------------------------------------------------------------------------- |
| `assets`   | Arquivos estáticos como CSS, JavaScript e imagens.                          |
| `includes` | Arquivos reutilizáveis, como header, footer, navbar e etc. |
| `pages`    | Páginas do sistema organizadas por módulos.                                  |
| `database` | Scripts e arquivos relacionados ao banco de dados.                          |
| `docs`     | Documentação, imagens e arquivos utilizados no README.                      |
| `uploads`  | Arquivos enviados pelos usuários como contratos.                                           |

As páginas do sistema são organizadas por **módulos**.

```text
pages/
├── geral/
├── livros/
│   ├── aluno/
│   └── admin/
└── armarios/
    ├── aluno/
    └── admin/
```

| Pasta            | Descrição                                                                           |
| ---------------- | ----------------------------------------------------------------------------------- |
| `geral`          | Telas compartilhadas do sistema, como login, seleção de módulos e etc. |
| `livros/aluno`   | Funcionalidades de livros disponíveis aos alunos.                                 |
| `livros/admin`   | Funcionalidades administrativas do módulo de livros.                                |
| `armarios/aluno` | Funcionalidades de armários disponíveis aos alunos.                               |
| `armarios/admin` | Funcionalidades administrativas do módulo de armários.                              |

## 😛 Sobre a equipe

Somos uma equipe de 4 estudantes composta por:
| Nome | Cargo |
|--------|--------|
| Sofia Akemi | Product Owner |
| Vinícius Oliveira | Scrum Master |
| Samuel Marques | Desenvolvedor |
| Ygor Santana | Desenvolvedor |

## 📋 Metodologia

O desenvolvimento do projeto segue a metodologia ágil Scrum, com gerenciamento de tarefas realizado através do Jira.

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos como Trabalho de Conclusão de Curso da **ETEC Prof.ª Ilza Nascimento Pintus**.

## 📈 Status

🚧 Projeto atualmente em **desenvolvimento**.
