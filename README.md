<p align="center">
    <img src="https://luxid.dev/lion5.svg" width="120" alt="Luxid Logo">
</p>

<p align="center">
    <strong>Luxid Framework</strong><br>
    The starter project for building Luxid applications.
</p>

<p align="center">
    ⚠️ <strong>Pre-release:</strong> APIs are unstable and subject to change.
</p>

---

## About Luxid

**Luxid** is a lightweight PHP framework built around a pattern called **SEA**:

> **Screen (views) → Entities (models) → Actions (controllers)**

An **Action** groups the handlers for one slice of the domain and declares its
own routes, so a feature's dispatch table sits beside its behaviour rather than
in a central file. Each handler is an *activity*.

What you get out of the box:

- Explicit routing where **every route must state whether it is public**
- Action-based request handling
- [Nova](https://github.com/luxid/nova) server-rendered reactive components
- [Rocket](https://github.com/luxid/rocket) attribute-driven ORM with migrations
- The `juice` CLI for scaffolding, migrations and inspection

## Requirements

- PHP 8.1 or newer
- MySQL (or MariaDB) if your application uses a database
- Composer

## Getting started

### 1. Create the project

```bash
composer create-project luxid/framework my-app
cd my-app
```

### 2. Configure the environment

Edit `.env`:

```env
DB_DSN=mysql:host=127.0.0.1;port=3306;dbname=luxid
DB_USER=root
DB_PASSWORD=
```

The connection is opened lazily, so routes that never query the database work
before you configure one.

Check your setup at any time:

```bash
php juice env:check
php juice db:status
```

### 3. Create the database and run migrations

```bash
php juice db:create
php juice db:migrate
php juice seed          # optional sample data
```

### 4. Start the server

```bash
php juice start
```

The application is served at <http://localhost:8000>. Use `--host` and `--port`
to change that.

## Project layout

```
app/
  Actions/      request handlers, one class per slice of the domain
  Entities/     Rocket entities
  Providers/    application service providers
config/         configuration and the console kernel
migrations/     schema migrations
nova/
  components/   reusable Nova components
  pages/        page components
  layouts/      layouts pages are wrapped in
  nova.json     compiler and layout configuration
routes/
  api.php       JSON routes
  web.php       HTML routes
seeds/          database seeders
web/            document root — point your server here
```

Point your web server at `web/`, not the project root.

## Writing an action

```php
namespace App\Actions;

use Luxid\Nodes\Response;
use Luxid\Routing\Routes;

class TodoAction extends LuxidAction
{
    public static function routes(): Routes
    {
        return Routes::new()
            ->prefix('api')
            ->add('/todos', get('index'))
            ->add('/todos/{id}', get('show'))
            ->secure();
    }

    public function index(): string
    {
        return Response::success(Todo::findAll());
    }

    public function show(string $id): string
    {
        return Response::success(Todo::find((int) $id));
    }
}
```

Register it in `routes/api.php`:

```php
use App\Actions\TodoAction;

TodoAction::routes()->register(TodoAction::class);
```

### Every route states its security

| Declaration | Effect |
|---|---|
| `->secure()` | Every activity requires authentication |
| `->open(['login'])` | Every activity requires authentication except those named |
| `->public()` | No activity requires authentication |

Registering a collection without one of these throws at boot, so an unprotected
endpoint is a startup error rather than something you find out about later.

Inspect what is registered:

```bash
php juice routes
```

## Nova components

Components live in `nova/`, are registered at boot, and render server-side:

```php
component('pages/Counter', function ($c) {
    $c->state(fn () => ['count' => 0]);

    // Only the keys named here are sent to the browser.
    expose(['count']);

    $c->actions([
        'increment' => function (array &$state): void {
            ++$state['count'];
        },
    ]);

    $c->view(function ($state) { ?>
        <button @click="increment">Clicked @echo($state->count) times</button>
    <?php });
});
```

Action calls are POSTed back to the page and require a CSRF token. The default
layout already renders `\Luxid\Nova\Csrf::metaTag()`; keep it if you write your
own.

## The `juice` CLI

```bash
php juice start                          # development server
php juice routes                         # inspect registered routes
php juice make:action Todo
php juice make:entity Todo
php juice make:migration create_todos_table
php juice make:nova:page Dashboard
php juice db:migrate
php juice db:rollback
php juice seed
php juice env:check
php juice db:status
```

Run `php juice` with no arguments for the full list.

## Going to production

`php juice optimize` prepares the application and reports what is still costing
you. The short version:

```ini
opcache.enable=1
opcache.validate_timestamps=0
opcache.preload=/path/to/your-app/preload.php
opcache.preload_user=www-data
```

That alone takes a cold request boot from ~700µs to ~105µs. For another ~5x,
run under FrankenPHP worker mode with the bundled `web/worker.php` and
`Caddyfile`.

See **[PERFORMANCE.md](PERFORMANCE.md)** for the measurements, the worker-safety
rules, and why Swoole is not the right fit here.

## Learning Luxid

Documentation lives at [luxid.dev](https://www.luxid.dev), and the
[tutorials repository](https://github.com/luxid/tutorials) walks through five
worked examples from a minimal API to server-side rendering.

## Contributing

- Follow PSR-12
- Include tests with behavioural changes
- Describe the problem a pull request solves, not just the change

## Security

Report vulnerabilities privately to **jhay@luxid.dev**.

## License

MIT.
