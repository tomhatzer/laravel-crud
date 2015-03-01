laravel5-CRUD
============

a simple powerful artisan command to create crud for laravel in less than 5 mintues
----

This code is based on the awesome work of vahidstar ( https://github.com/vahidstar/laravel-crud )
----

> copy the two files in Console/Commands dir to your laravel : app/Console/Commands/

execute the following commands on commandline

```sh
php artisan make:console CrudMake --command=crud:make
php artisan make:console CrudMock --command=crud:mock
```
>  this registers artisan command to laravel

afterwards customize the following array in app/Console/Commands/Kernel.php like this

```php
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
        'App\Console\Commands\CrudMake',
        'App\Console\Commands\CrudMock',
        'App\Console\Commands\Inspire',
	];
```

then you should create your mock in order to build your crud.

```sh
php artisan crud:mock user
```

> this command creates a mock for user Model

> you can find it in app/mocks/MockUser.php 

in order to create Multiple mocks you can run this command.

```sh
php artisan crud:mock user,company,category
```

> mocks must be seprated with ","

so you have the mock , let's take a look at it


```php
<?php
class MockUser {

    public static $schema = array(
        "id"               => "digit|index|show",
        "username"         => "text|index|create|edit|show",
        "password"         => "password|create|hash",
        "password_confirm" => "password|create|deny",
        "email"            => "email|create|edit",
        "profile_picture"  => "file|create|edit",
        "address"          => "textarea|create|edit"
    );

    public static $resourceName = "UserController";
    public static $viewFolderName = "user";
    public static $modelName = "User" ;
    public static $validation = true;
}

```
in $schema property you should define your table's field

each field should have it's own type 

you can use one of these types below

```php
["text", "hidden", "digit", "textarea", "password", "file", "email"]
```

there are more other options for our fields

if you want to show your fields in a specific page for example in index page [list page] you must use index.

other possible options

```php
["index", "edit", "show", "create"]
```
notice that for more security we should not demonstrate some sensetive fiedls in everywhere for example password field.

let us take a look at this example 

```php
<?php
class MockUser {

    public static $schema = array(
        "password"         => "password|create|hash",
        "password_confirm  => "password|deny"
    );
}

```

in this example password field will be demonstrated just in create page // when a user is create

other options for fields are 

```php
["hash", "deny"]
```

hash : you use hash for your field when you want to have your fields encoded for storing into database 

deny : you use deny when you don't want to stroe the field into database  for example password confirm does not need to be stored


there is a $validation property in each mock file which says that each form must ( true ) or must not (false) be validated.

notice that you should have a static peroperty called $rules in your Model which defines your validation.


last point is that when you use file type for fields it just create form for this and it does not take care of uploding files


in order to create your crud , run this command 

```sh
php artisan crud:make

```

> this command looks for files in app/mocks/* and get their config and creates Controller and View files




