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

run `php artisan` to get a list of every available `artisan` command

### Routes and Views

routes are in the `/routes/web.php` file
views are in the `/resources/views` folder

### Passing data into views

you can pass data from our route handler to the view

### Blade

blade syntax eg. :`{{ $type }}` escape any special characters like `htmlspecialchars()`

if statement:

```
@if($price > 15)
    <p>this pizza is expensive</p>
@elseif($price < 5)
    <p>this pizza is cheap</p>
@else
    <p>this pizza is normally priced</p>
@endif
```

unless statement:

```
@unless($base === "cheesy crust")
    <p>you don't have a cheesy crust</p>
@endunless
```

php directive (to execute vanilla php in blade):

```
@php
    $name = "shaun";
    echo $name;
@endphp
```

for loop:

```
@for($i = 0; $i < count($pizzas); $i++)
    <p> {{ $pizzas[$i]['type']  }} </p>
@endfor
```

foreach loop:

```
@foreach($pizzas as $pizza)
    <div>
    {{ $loop->index }} {{ $pizza['type'] }} - {{ $pizza['base'] }}
    </div>
@endforeach
```

within a loop we have access to `$loop->first`, `$loop->last`, `$loop->index`

### Layout files

use `@yield('content')` in the layout file where we want to inject our content and the following in the blade files extending our layout:

```
@extends('layouts.layout')

@section('content')
<div>
...our actual content
</div>
@endsection
```

### External images and css

just put the files in the `/public` folder, then to link `/public/styles/main.css` in our layouts, just link to `/styles/main.css`

### Query Parameters

we can access the query params "name" for the address `http://localhost:8000/pizzas?name=mario` in the controller for the "pizzas" route at `request('name')`

### Route Parameters (wildcards)

you can access the route parameter in the following url `http://localhost:8000/pizzas/1` by creating a specific route for it:

```
Route::get('/pizzas/{id}', function ($id) {
    // use the $id to query the db for a record
    return view('details', []);
});

```

### Controllers

controllers are located in the `/app/Http/Controllers` folder.
we run `php artisan make:controller PizzaController` in the terminal to make a new controller called PizzaController.

Functions in the controllers are called "actions".
We reference the actions in the routes file the following way, `Route::get('/pizzas', 'PizzaController@index');`

### Connection to MySQL

nb: we can alias the XAMPP mysql by adding the following to .zshrc: `alias mysql='/opt/lampp/bin/mysql -u root'`

create an empty db with phpmyadmin or the terminal by launching mysql and running `create database pizzahouse;` in MariaDB prompt.

in the `.env` file, set our `DB_` keys to the appropriate values and restart the server `php artisan serve`

### Migrations

migrations allows us to programmatically from our code define the structure of a table in our database through migration files in `/database/migrations`

in a migration file, the `up()` function is responsible for creating the table and defining its structure and the the `down()` function is responsible for rolling back the `up()` method

to make a new table `pizzas` in our db, create a migration file in the terminal with `php artisan make:migration create_pizzas_table`, add in our own extra columns and then run the migration with `php artisan migrate`

`php artisan migrate:status` will give us the status of our migrations

`php artisan migrate:rollback` will rollback the latest migration we did

`php artisan migrate:fresh` will drop all the tables in the db (created by a migraiton or not!) and will re-run al migrations
`php artisan migrate:refresh` reset and re-run all migrations (by running the `down()` method in each migration files and then the `up()` method)

to add a new column to our `pizzas` table, either modify the existing migration then rollback and migrate anew; or better, create a new migration `php artisan make:migration add_price_to_pizzas_table`

### Eloquent Models

(we're manually inserting a few pizzas in our tables)

Eloquent is Laravel's ORM

create a model with `php artisan make:model Pizza`, it'll be added in the `app` folder root  
the model will automaticallt connect to the `pizzas` table based on its name; this behavior could be overridden by adding a variable `protected $table = "some_name"` to the Pizza class

in our `PizzaController`, make use of our new model to grab the data in the DB and send it to the view, `$pizzas = Pizza::all();` the `all()` method comes with the model  
`$pizzas = Pizza::where('type', 'hawaiian')->get();`, `$pizzas = Pizza::orderBy('name', 'desc')->get();`, are alternative ways of fetching our db records. `$pizzas = Pizza::latest()->get()` will grab all records in created_at order
