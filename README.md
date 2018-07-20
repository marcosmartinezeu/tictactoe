TIC TAC TOE
=====================

Tic Tac Toe game

# Installation 

### Requirements

- PHP 7.1
- Docker Compose
- Composer
- Bower

Clone this repository using SSH

```bash
$ git clone ssh://git@vps161376.ovh.net:8022/mmartinez/tictactoe.git
```

Install the backend dependencies using composer

```bash
$ composer install
```

Install the frontend dependencies using bower

```bash
$ bower install
```

# Run

### Using Docker Compose

Use Docker Compose to build this application.

```bash
$ docker-compose up --build -d
```

Now just to load `http://localhost:7000/api/tic-tac-toe/play`

# Test

To run the tests just access the path of project and run:

```bash
$ php vendor/bin/simple-phpunit --bootstrap vendor/autoload.php tests
```

