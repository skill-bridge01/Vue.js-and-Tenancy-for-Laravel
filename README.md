<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# 1. Laravel Valet Setup
- MacOS
https://github.com/laravel/valet
- Windows
https://github.com/cretueusebiu/valet-windows

This is the installation guide for windows. You can get the instruction for MacOS [here](https://github.com/laravel/valet)

Valet is a Laravel development environment for Windows. No Vagrant, no /etc/hosts file. You can even share your sites publicly using local tunnels. Yeah, we like it too.

Laravel Valet configures your Windows to always run Nginx in the background when your machine starts. Then, using Acrylic DNS, Valet proxies all requests on the *.test domain to point to sites installed on your local machine.

Before installation, 
 - make sure that no other programs such as Apache or Nginx are binding to your local machine's port 80.
 - make sure to open your preferred terminal (Windows Terminal, CMD, Git Bash, PowerShell, etc.) as Administrator.
 - make sure you have installed Composer and PHP

Installation: 
- Install valet with Composer via 
```bash
composer global require cretueusebiu/valet-windows
```
- Run the `valet install` command. This will configure and install Valet and register Valet's daemon to launch when your system starts.
- If you're installing on Windows 10/11, you may need to manually configure Windows to use the [Acrylic DNS](https://mayakron.altervista.org/support/acrylic/Windows10Configuration.htm) proxy.

Valet will automatically start its daemon each time your machine boots. There is no need to run `valet start` or `valet install` ever again once the initial Valet installation is complete.

# 2. Multi-tenancy Setup
## a. Installation
We are going to use `stancl/tenancy` [package](https://tenancyforlaravel.com/).

```bash 
composer require stancl/tenancy
```

Then, run the `tenancy:install` command:
```bash
php artisan tenancy:install
```

This will create a few files: migrations, config file, route file and a service provider.
<br>
Let's run the migrations: `php artisan migrate`

## b. Configuration
Register the service provider in `config/app.php`. Make sure it's on the same position as in the code snippet below:

```bash
/*
 * Application Service Providers...
 */
App\Providers\AppServiceProvider::class,
App\Providers\AuthServiceProvider::class,
// App\Providers\BroadcastServiceProvider::class,
App\Providers\EventServiceProvider::class,
App\Providers\RouteServiceProvider::class,
App\Providers\TenancyServiceProvider::class, // <-- here
```

## c. Creating a tenant model

Now you need to create a Tenant model. The package comes with a default Tenant model that has many features, but it attempts to be mostly unopinionated and as such, we need to create a custom model to use domains & databases. Create the file app/Models/Tenant.php like this:

```bash
<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
}
```
> Please note: if you have the models anywhere else, you should adjust the code and commands of this tutorial accordingly.

Now we need to tell the package to use this custom model. Open the `config/tenancy.php` file and modify the line below:
```bash
'tenant_model' => \App\Models\Tenant::class,
```

## d. Events

The defaults will work out of the box here, but a short explanation will be useful. The `TenancyServiceProvider` file in your `app/Providers` directory maps tenancy events to listeners. By default, when a tenant is created, it runs a `JobPipeline` (a smart thing that's part of this package) which makes sure that the `CreateDatabase`, `MigrateDatabase` and optionally other jobs (e.g. `SeedDatabase`) are ran sequentially.

In other words, it creates & migrates the tenant's database after he's created — and it does this in the correct order, because normal event-listener mapping would execute the listeners in some stupid order that would result in things like the database being migrated before it's created, or seeded before it's migrated.

## e. Central routes

We'll make a small change to the `app/Providers/RouteServiceProvider.php` file. Specifically, we'll make sure that central routes are registered on central domains only.

```bash
protected function mapWebRoutes()
{
    foreach ($this->centralDomains() as $domain) {
        Route::middleware('web')
            ->domain($domain)
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}

protected function mapApiRoutes()
{
    foreach ($this->centralDomains() as $domain) {
        Route::prefix('api')
            ->domain($domain)
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}

protected function centralDomains(): array
{
    return config('tenancy.central_domains');
}
```
Call these methods manually from your `RouteServiceProvider`'s `boot()` method, instead of the `$this->routes()` calls.
```bash
public function boot()
{
    $this->configureRateLimiting();

    $this->routes(function () {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    });
}
```

## f. Central Domains
Now we need to actually specify the central domains. A central domain is a domain that serves your `"central app"` content, e.g. the landing page where tenants sign up. Open the `config/tenancy.php` file and add them in:

```bash
'central_domains' => [
    // You can add env('APP_DOMAIN_NAME') variable
    // Add the ones that you use. I use this one with Laravel Valet.
    env('APP_DOMAIN_NAME'),
],
```
If you're using Laravel Sail, no changes are needed, default values are good to go:
```bash
'central_domains' => [
    '127.0.0.1',
    'localhost',
],
```

## g. Tenant Routes
Your tenant routes will look like this by default:
```bash
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});
```
> These routes will only be accessible on tenant (non-central) domains — the `PreventAccessFromCentralDomains` middleware enforces that.

Let's make a small change to dump all the users in the database, so that we can actually see multi-tenancy working. Open the file `routes/tenant.php` and apply the modification below:
```bash
Route::get('/', function () {
    dd(\App\Models\User::all());
    return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
});
```
## h. Migrations
To have users in tenant databases, let's move the users table migration (the file `database/migrations/2014_10_12_000000_create_users_table.php` or similar) to `database/migrations/tenant`. This will prevent the table from being created in the central database, and it will be instead created in the tenant database when a tenant is created — thanks to our event setup.

## i. Creating tenants
For testing purposes, we'll create a tenant in `tinker`
```bash
$ php artisan tinker
>>> $tenant1 = App\Models\Tenant::create(['id' => 'foo']);
>>> $tenant1->domains()->create(['domain' => 'foo.localhost']);
>>>
>>> $tenant2 = App\Models\Tenant::create(['id' => 'bar']);
>>> $tenant2->domains()->create(['domain' => 'bar.localhost']);
```
Now we'll create a user inside each tenant's database:

```bash
App\Models\Tenant::all()->runForEach(function () {
    App\Models\User::factory()->create();
});
```
Those things above will migrate all migrations in `database/migrations/Tenant` directory automatically and create multiple databases for each domain.

After that, run the application , then you visit `foo.sass.test(:8000)` / `foo.localhost(:8000)` or `bar.sass.test(:8000)` / `bar.localhost(:8000)`.
We should see a dump of the different users table where we see some users per domain.

[screenshot - 1](https://drive.google.com/file/d/1dfOZCiAqGLggO8uII51o2JvtxNx4MB7g/view?usp=drive_link)
<br>
[screenshot - 2](https://drive.google.com/file/d/1HTc86y_uh9MD3JdjDjUdmWaCOwbAGSt-/view?usp=drive_link)
<br>
[database - 1](https://drive.google.com/file/d/1wberTzVt1SjwKbwg7XEter9CHcwPZIkw/view?usp=drive_link)

# 3. Project Running
You need to drop all databases include central, tenant databases first.
## a. Database Migration
We need to migrate migrations of the central database.
You can find migration files from the root sub directory of the migration folder - `database/migrations/`.
Run the following command 
```bash
php artisan migrate
```
This will ask you to create a database manually or automatically.
This command will create some central tables such as `Users`, `Pieces`, and so on.
After that you need to seed database by running `php artisan db:seed`.
It will generate some dummy data for `Pieces`, `Services` which will be used from the tenant databases.
## b. Tenant creation
Before create tenants, you need to confirm `env('APP_DOMAIN_NAME')` variable in your `.env` file if it is exist.
For example, it is `sass.test`.
Next run your project by `php artisan serve` & `npm run dev` (you can use xampp or others), please open `sass.test:8000/register` on your browser.
You can register site users and each username will be a new tenant.
Whenever you create a new user, a new corresponding tenant will be created.
For example, we create a new user with the username - `foo`, then it will create a new tenant database `tenantfoo`.
After the creation, you can open `foo.sass.test:8000/login` and sign in with your credentials.
After sign in, you can see pieces, services list that will be populated from the central database.
After new some invoices, customers creation, you can see separated `customers`, `invoices`, and `service_piece_invoices` tables.
Those tables will interact with the central database tables such as `pieces`, `service_piece`, `services` isolated.

So you can add your `pieces`, `services` on your `Nova` admin, and then they will be shared between all tenant databases. 
> Note: According to the Tenacy library guide, we can't make cross relationships between the central tables and tenant database tables. So we make virtual relationships. 



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
