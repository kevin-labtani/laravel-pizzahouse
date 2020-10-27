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

you can pass data from our route handler to the view

### Blade

blade syntax eg.:`{{ $type }}` escape any special characters like `htmlspecialchars()`

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
