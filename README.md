# Pizza Place App

## Présentation du Projet

Projet créé le 27 Octobre 2020, dans le but de rafraichir ma connaissance de Laravel.

Le projet est basé sur le cours de Laravel 6 de [Shaun Pelling](https://github.com/iamshaunjp)

## Contributeurs

- [**Kevin Labtani**](https://github.com/kevin-labtani)

## Langages et technologies

- PHP
  - Laravel
- MariaDB
- Apache

## Project Notes

### Setup

create project with `composer create-project --prefer-dist laravel/laravel pizzahouse "6.*"`

serve with `php artisan serve`

### Routes and Views

routes are in the `/routes/web.php` file
views are in the `/resources/views` folder

### Passing data into views

you can send data through the route to the view

blade syntax eg.:`{{ $type }}` escape any special characters like `htmlspecialchars()`
