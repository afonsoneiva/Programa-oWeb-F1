# **Introdução ao MVC**
_Última atualização do doc. 02.outubro.2026_

---

### Model - View - Controller
- [Conceitos básicos](#conceitos-básicos)
- [Vantagens do MVC](#vantagens-do-mvc)
- [MVC routing](#mvc-routing)
- [O diálogo entre camadas - _ficcionado_](#o-diálogo-entre-camadas---ficcionado)
- [Exemplo prático (simplificado)](#exemplo-prático-simplificado)
- [Estrutura de pastas do projeto](#estrutura-de-pastas-do-projeto)
- [Ficheiro `.htaccess`](#o-ficheiro-htaccess)
- [Configuração do Apache](#configuração-do-apache)
- [Configuração de nomes alternativos (alias) para pastas (opcional)](#configuração-de-nomes-alternativos-alias-para-pastas-opcional)
- [Resolução de problemas](#resolução-de-problemas)

> Introdução ao padrão Model-View-Controller (MVC), com explicação de conceitos introdutórios, vantagens e funcionamento interno. Inclui a descrição da estrutura de pastas, do papel do ficheiro `.htaccess` e da configuração do Apache necessária para o routing. Apresenta ainda uma secção de resolução de problemas comuns na configuração de aplicações MVC.

# Conceitos básicos
```HTML
            +------------+
      +-----+   MODEL    +<----+
      |     +------------+     |
      |                        |
      |                        |
      v                        |
+------------+          +------+-----+
|    VIEW    |          | CONTROLLER |
+-----+------+          +------------+
      |                        ^
      |                        |  
      |                        |
      |                        |
      +-----> UTILIZADOR+------+
```
O MVC é um padrão de desenvolvimento.

_Visto de outra forma, porque numa aplicação Web em PHP, o pedido do browser entra **sempre primeiro no Controller** (através do encaminhamento do `index.php`)._

```mermaid
graph TD
    U[Utilizador / Browser] -->|1. Pedido HTTP| C[Controller]
    C -->|2. Pede dados| M[Model]
    M -->|3. Retorna dados| C
    C -->|4. Passa dados| V[View]
    V -->|5. Resposta HTML| U
```

Genericamente podemos considerar o seguinte:

### **Model**
Representa os dados.

### **View**
Apresentação/representação dos dados.

### **Controller**
Comunica com a _View_ para a apresentação dos dados do _Model_.

---

Com maior detalhe pode considerar-se que a Vista (Interface do Utilizador) comunica com o Controlador, que por sua vez comunica com o Modelo para solicitar dados e atualizações.

- **Vista (Interface de Utilizador)** representa a camada de interface do utilizador, responsável por exibir dados e interagir com o utilizador.

- **Controlador** representa a camada de controlo, que atua como intermediário entre a vista (Interface de Utilizador) e o modelo. Responsável por receber as solicitações do utilizador, processa a lógica de negócio e atualiza a vista com base nos dados do modelo.

- **Modelo** representa a camada de modelo, responsável pela gestão dos dados e lógica de negócio da aplicação.



## MVC (numa aplicação web)
### **Model**
É a estrutura dos dados (por exemplo dados JSON, uma base de dados relacional, ...)

### **View**
É a UI (HTML, CSS, divs, tabelas, imagens, ...)

### **Controller**
Processa a interação do utilizador e manipula os dados provenientes do _Model_ para posterior output na _View_ (PHP, Java, C, ...) 

> Exemplo:
> 
> 1. Preenchimento de um formulário » Processamento da info. » Envio para a BD  
> (View » Controller » Model)
>
> 2. Dados na BD » Manipulação dos dados » Visualização dos dados numa grid  
> (Model » Controller » View)


# Vantagens do MVC
### Vantagens e posterior trabalho a realizar nas aulas:

> - Separar as camadas - têm diferentes funções/responsabilidades;  
> - Facilitar a manutenção da aplicação;  
> - Evitar que apenas um ficheiro seja responsável pelas três funções;  
> - Reaproveitar código (em implementações posteriores).

## Vantagens:
- **Segurança**   
O _controller_ funciona como uma espécie de filtro capaz de impedir que qualquer dado incorreto chegue até a camada modelo. 
- **Organização**   
Esta metodologia de programação permite que um novo programador, perante um projeto já desenvolvido (ou em desenvolvimento), tenha muito mais facilidade em entender o que foi desenvolvido Por outro lado, torna-se mais fácil fazer debug e encontrar os erros, de forma mais fácil e rápida.
- **Eficiência**   
Como a arquitetura de software é dividida em 3 camadas, permite que vários programadores trabalhem num mesmo projeto, possibilitando um desenvolvimento mais eficaz.
- **Tempo**   
Com a dinâmica facilitada pela colaboração entre os programadores, o projeto pode ser concluído com muito mais rapidez, tornando o projeto escalável.  
- **Transformação**   
As mudanças que forem necessárias também são mais simples. Uma alteração do visual de uma aplicação, por exemplo, apenas implica o trabalho do lado das _views_.

# MVC routing

O _MVC routing_ é o mecanismo responsável por mapear pedidos, efetuados num browser, com ações especificadas num _controller_. Quando o URL, de um pedido, corresponde a qualquer um dos padrões de rota registado na _tabela_ de rotas, o mecanismo de _routing_ (roteamento, encaminhamento) encaminha a solicitação para o _handler_ (manipulador) apropriado para essa solicitação.

Numa aplicação MVC, o _routing_ é tipicamente implementado através de uma tabela de _routing_, que define um conjunto de endereços (URLs) para controladores e ações específicas. A tabela de rotas é normalmente definida no código de inicialização da aplicação e pode ser configurada usando convenções ou especificando explicitamente as rotas.

Os URLs estão, normalmente, no formato `/[Controlador]/[Ação]/[Id]` correspondendo a um determinado controlador e a determinada ação.

# O diálogo entre camadas - _ficcionado_

> View:  
> _Controller, o utilizador quer visualizar o detalhe de um filme. Vou enviar-te o id do filme._

> Controller:  
> _Já te mando a resposta. Model podes ver se existe o filme com o id 5._

> Model:  
> _Já verifiquei que existe. Vou enviar-te os dados todos do filme._

> Controller:  
> _View, o utilizador pediu um filme que existe na nossa base de dados. Vou enviar-te a informação toda e tu mostra-a ao utilizador._

> View:  
_Obrigado Controller. Vou já mostrar ao utilizador a informação do filme que ele quer ver.  Até lhe faço um card com o filme :)_

# Exemplo prático (simplificado)

_Exemplo para a visualização dos detalhes de um filme:_

### 1. Model (`app/models/Movie.php`)
O Modelo obtém os dados (neste exemplo, os dados são simulados):

```php
class Movie {
    public static function getById($id) {
        // Numa aplicação real, seria resultado de uma consulta SQL à base de dados
        return [
            'id' => $id,
            'title' => 'Inception',
            'year' => 2010
        ]
    }
}
```
### 2. Controller (`app/controllers/Movie.php`)

O Controlador recebe o pedido, solicita os dados ao Modelo e carrega a Vista adequada:

```php
class Movie {
    public function get($id) {
        // Pede os dados ao Model
        $movieData = Movie::getById($id)
        
        // Inclui a View, disponibilizando a variável $movieData
        require_once '../app/views/movie/get.php'
    }
}
```

### 3. View (`app/views/movie/get.php`)

A Vista apenas recebe os dados e encarrega-se da apresentação ao utilizador:

```php
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= $movieData['title'] ?></title>
</head>
<body>
    <h1>Filme: <?= $movieData['title'] ?></h1>
    <p>Ano de Lançamento: <?= $movieData['year'] ?></p>
</body>
</html>
```

# Estrutura de pastas do projeto 
```
├── app
│   ├── models
│   │   └── Movies.php
│   │ 
│   ├── views
│   │   ├── home
│   │   │   └── index.php
│   │   └── movie
│   │       ├── index.php
│   │       └── get.php
│   │ 
│   ├── controllers
│   │   ├── Home.php
│   │   └── Movie.php
│   │ 
│   ├── core
│   │   ├── App.php
│   │   ├── Controller.php
│   │   └── Db.php
│   │ 
│   ├─ .htaccess
│   └─ load.php
│   
├─ .htaccess
├─ index.php
│   
│  (estrutura de pastas necessária para a colocação de css, js, ...)
└─ public/
    └─ css/, js/, img/
```

## Ficheiro **index.php**
* **(Front Controller):** Ponto único de entrada da aplicação (todas as rotas passam por este ficheiro). Recebe todos os pedidos HTTP encaminhados pelo `.htaccess`, carrega as configurações/autoloader (`load.php`) e instancia a classe principal (`App.php`).

## Pasta **Models**
- Classes para a interação com a base de dados.

## Pasta **Views**
- Responsáveis pela interação com o utilizador.

## Pasta **Controllers**
- Controladores da aplicação: recebe dados do utilizador, processa-os e, se necessário, chama uma view;
- Classes que herdam todos os métodos da classe "Controller".

## Pasta **Core**
- App.php: tratamento do url e decisão do controlador a utilizar;
- Controller.php: super-classe (herdada pelos controllers) responsável pela utilização de models e views;
- Db.php: conexão à base de dados.

## Ficheiro **.htaccess**
- configurações do servidor Apache para alterar acessos às pastas, da aplicação, no servidor;
- pode ser definido um ficheiro ".htaccess" global (na raiz da aplicação);
- podem ser definidos ficheiros ".htaccess", em cada pasta, para definição de regras individuais.

## Ficheiro **load.php**
- responsável pelo carregamento das classes da aplicação.  

# O ficheiro `.htaccess`

O ficheiro `.htaccess` (Hypertext Access) é um ficheiro de configuração utilizado pelos servidores web, como o Apache (no caso do projeto a implementar), para controlar várias funcionalidades e comportamentos do servidor sem a necessidade de modificar os ficheiros principais de configuração. Cada pasta de uma aplicação pode ter o seu próprio ficheiro `.htaccess`, o que permite configurar regras específicas para diferentes áreas/pastas da aplicação.

## Funções comuns do `.htaccess`
- **Redirecionamento de URLs**: Usado para alterar ou simplificar URLs.
- **Controlar o acesso**: Permite proteger pastas ou ficheiros específicos com autenticação.
- **Definir configurações de cache**: Otimizar o carregamento de recursos.
- **Personalizar páginas de erro**: Exibir páginas de erro personalizadas, como a famosa 404.

## Estrutura do exemplo

```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule . index.php [L]
```

1. **RewriteEngine On**: Esta linha ativa o motor de reescrita (mod_rewrite) do Apache. O mod_rewrite permite modificar URLs de entrada com base em regras definidas.

2. **RewriteCond %{REQUEST_FILENAME} !-f**: Esta condição verifica se o pedido (URL) não corresponde a um ficheiro real no servidor. O `!-f` significa "não é um ficheiro". Se for um ficheiro, o Apache irá servi-lo normalmente, sem aplicar as regras de reescrita.

3. **RewriteCond %{REQUEST_FILENAME} !-d**: Semelhante à condição anterior, esta linha verifica se o pedido não corresponde a uma pasta (diretório) existente. O `!-d` significa "não é um diretório".

4. **RewriteRule . index.php [L]**: Esta regra redireciona todos os pedidos que não sejam ficheiros ou pastas existentes para o ficheiro `index.php`. O ponto (`.`) indica qualquer padrão de URL, e o `[L]` significa que esta é a última regra a ser aplicada (Last Rule), evitando que outras regras sejam processadas após esta.

Na prática, com este `.htaccess`, qualquer pedido que não corresponda a um ficheiro ou pasta (diretório) existente será automaticamente redirecionado para o `index.php`. Este tipo de configuração é muito útil em frameworks ou aplicações que usam URL amigáveis, como aplicações PHP que implementam **routing**.


# Configuração do Apache  

No ficheiro:
```
httpd.conf
```

Na pasta do Apache (no caso do XAMPP) em:
```
C:\xampp\apache\conf
```

Fazer a edição do ficheiro, colocando o seguinte:
```
<Directory "C:/<caminho_completo_para_a_pasta_do_proj>/<nome_da_pasta_do_proj>">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

---
### Notas teóricas:
```
Options Indexes FollowSymLinks Includes ExecCGI
```
Diretivas de configuração do servidor Apache
- permite que o servidor, quando é feito o acesso a determinada pasta, procure um index.* para 
  ser executado;  
- FollowSymLinks permite que sejam feitos symbolic links (podemos comparar aos 
  atalhos do Windows): existem por questões de segurança do servidor; 
- includes server-side são permitidos;
- execução de CGI são permitidos.

---

```
AllowOverride All
```
- Indica ao Apache os tipos de diretivas que são permitidas nos ficheiros .htaccess
(no nosso caso todas as diretivas são permitidas).

---

```
Require all granted
```
- Nenhum endereço de IP é bloqueado no acesso ao serviço (site).

&nbsp;
## Apache   
# Configuração de nomes alternativos (alias) para pastas (opcional)

No ficheiro:
```
httpd-autoindex.conf
```

Na pasta do Apache (no caso do XAMPP) em:
```
C:\xampp\apache\conf\extra
```
---

**NOTA IMPORTANTE**:  
Para os utilizadores do **MAMP** no **macOS**, o processo de configuração de um _alias_ no Apache é diferente. O código abaixo, com as devidas particularidades do Sistema Operativo, deve ser adicionado no ficheiro `httpd.conf`

---

Fazer a edição do ficheiro, colocando o seguinte (por exemplo):
```
Alias /moviesapp/ "<caminho_completo_para_a_pasta_do_proj>/moviesapp/"
<Directory "<caminho_completo_para_a_pasta_do_proj>/moviesapp">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

Esta configuração, permite aceder ao projeto (à app) através do _alias_, independentemente da localização no disco rígido do computador (servidor), da seguinte forma:
```
http://localhost/moviesapp/
```

# Resolução de problemas

## _Blocked port_

Eventualmente, poderá ocorrer que o Apache e/ou o MySQL não arranquem sendo apontado o motivo de que pode ser que a porta (onde "corre" o servidor) está ocupada. Nesta situação há dois comandos, que podem ser executados, na **linha de comandos** para concluir o que está ativo em determinada porta. Por exemplo, na porta 3306 (tipicamente a do servidor da base de dados):

1. Verificar o que está ativo (o que está a ocupar determinada porta):  
`netstat -ano | findstr :3306`  
O resultado será algo semelhante a:  
`TCP    127.0.0.1:3306         127.0.0.1:60448        ESTABLISHED     24700`  
Isto no caso de estar algo a ocupar a porta. Caso contrário não aparece qualquer listagem, com o significado de que a porta não está ocupada (neste caso o problema poderá ser outro).
2. Verificar qual é a aplicação que está a ocupar a porta:  
``tasklist | findstr 24700``  
Sendo que o `24700` é o id do processo (identificado com o comando executado em 1. - é o número que aparece no final de cada linha da listagem).  
O resultado será algo semelhante a:  
`mysqld.exe                   24700 Console                    1     23?556 K`

No caso de se observar alguma aplicação que não seja a pretendida poderá, eventualmente, ser necessário parar o processo dessa mesma aplicação para que o servidor (Apache ou o MySQL) arranquem convenientemente.

## _Internal Server Error_

_Internal Server Error  
The server encountered an internal error or misconfiguration and was unable to complete your request. Please contact the server administrator at you@example.com to inform them of the time this error occurred, and the actions you performed just before this error. More information about this error may be available in the server error log._

1. Aceder ao ficheiro de configuração do Apache `httpd.conf`  
2. Retirar o comentário (linha iniciada por #) da seguinte linha:  
`LoadModule rewrite_module modules/mod_rewrite.so`
3. **Reiniciar o serviço do Apache** no painel do XAMPP/MAMP para aplicar as alterações.


---
_José Viana | josev@estg.ipvc.pt_