<h1 align="center">Almost Medium</h1>
<p align="center">
  <a href="https://circleci.com/gh/jefflssantos/almost-medium/tree/master"><img src="https://circleci.com/gh/jefflssantos/almost-medium/tree/master.svg?style=svg" alt="Build status"></a>
</p>

## Intro
It's a simple API to create articles where registered users can list, create, edit (yours) and delete (yours) articles

## Any dependencies?

[Docker compose](https://docs.docker.com/compose/install/)


## Running

### Laradock
in `./laradock` folder
```sh
$ cp env-example .env
$ docker-compose up -d nginx mysql redis workspace
```
`nginx` config file path
```sh
./laradock/nginx/sites/default.conf
```

### Laravel
in `./laradock` folder
```sh
$ docker-compose exec workspace bash
```
in `workspace` container
```sh
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate --seed
```

in the browser, go to [http://localhost](http://localhost)

## API

### HTTP request headers
- **Content-Type**: application/json
- **Accept**: application/json

### Auth
| Field         | Type         | Description                                     |
| --------------|--------------|------------------------------------------------ |
| email         | string       | The article’s title                             |
| password      | string       | The article’s content                           |

| HTTP                    | Description                 | Example                |
| ------------------------|-----------------------------|----------------------- |
| **POST** /auth/register | Register a new user         | */api/auth/register*   |
| **POST** /auth/login    | Retrieve the user's token   | */api/auth/login*      |

### Authentication
```
Authorization : Bearer USER_TOKEN
```

### Articles
| Field         | Type         | Description                                     |
| --------------|--------------|------------------------------------------------ |
| title         | string       | The article’s title                             |
| body          | string       | The article’s content                           |

| HTTP                      | Description               | Example                |
| --------------------------|---------------------------|----------------------- |
| **GET**  /articles        | Show all articles         | */api/articles*        |
| **POST** /articles        | Create an article         | */api/articles*        |
| **GET** /articles/{id}    | Show an article by id     | */api/articles/1*      |
| **PUT** /articles/{id}    | Update an article by id   | */api/articles/1*      |
| **DELETE** /articles/{id} | Delete an article by id   | */api/articles/1*      |

## Tests
in `./laradock` folder
```sh
$ docker-compose exec workspace composer test
```
