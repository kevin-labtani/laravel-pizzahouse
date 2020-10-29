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

### Naming Conventions

Pizza Model

| Request | route          | controller & action     | view   |
| :------ | :------------- | :---------------------- | :----- |
| GET     | /pizzas        | PizzaController@index   | index  |
| GET     | /pizzas/{id}   | PizzaController@show    | show   |
| GET     | /pizzas/create | PizzaController@create  | create |
| POST    | /pizzas        | PizzaController@store   | ----   |
| DELETE  | /pizzas/{id}   | PizzaController@destroy | ----   |

### Getting a Single Record

useing our model in the `PizzaController`, we can use `$pizza = Pizza::findorfail($id);` in the `show` function, findorfail will show the 404 page in case the \$id isn't found.

### POST Requests

the route for the POST pizza action is `Route::post('/pizzas', 'PizzaController@store');`, and we create the `store()` method in the `PizzaController`  
nb: we need to add the `@csrf` blade directive in the `create` view in order for the redirect action in the `store()` method to work

### Save Data to DB

to save the data, create a new instance of the Pizza Model, set the properties and save that new Pizza.

```php
$pizza = new Pizza();

$pizza->name = request('name');
$pizza->type = request('type');
$pizza->base = request('base');

$pizza->save();

```

we can send a message on redirect with `return redirect('/')->with('msg', 'Thanks for your order!');`, this is session data, accessible in the view with `session('msg')`

### Arrays & JSON

we want to add a column _topping_ to the pizza table that'll we an array stored as a jSON string in our db.

we create a migration to add the column, then adapt our `create` view to add inputs for toppings, nb. we name them all `name="toppings[]"` to tell laravel that toppings are an array

in order to transform the JSON into an array when we get the data back from the db, in our `Pizza` model we add a cast property:

```php
protected $casts = [
    'toppings' => 'array'
];
```

### Deleting Records

the route for the POST pizza action is `Route::delete('/pizzas/{id}', 'PizzaController@destroy');`, and we create the `destroy()` method in the `PizzaController`

```php
public function destroy($id)
{
    $pizza = Pizza::findOrFail($id);
    $pizza->delete();
}
```

we need blades directives in our `show` view where we have the delete button, so that laravel knows to look for the delete request handler:

```php
<form action="/pizzas/{{ $pizzas->id }}" method="POST">
    @csrf
    @method('DELETE')
    <button>Complete Order</button>
</form>
```

### Using SASS

we'll need node; run `npm install` to install required packages.  
in `webpack.mix.js` we have the startpoints for the resources that need compiling, we had a `main.scss`  
use `npm run dev` to compile the sass into css  
use `npm run watch` to autocompile the sass into css as we make changes

### Auth setup

we want to lock the pizzas and order details page behind a login system

laravel comes with it's own auth system so alot of the back end is already setup for us, let's write the frontend! we'll install a ui package with `composer require laravel/ui`, well also use vue, run `php artisan ui vue --auth` to boilerplate a lot of the scaffolding for us

we now have a lot of generated views in the `views/auth` folder as well as new auth routes in the `web.php` file and a `HomeController` that uses auth middleware for the new `/home` route  

### Authentication Views

we switch our views to use the new layout autogenerated by laravel, with the exception of the `welcome` view  

