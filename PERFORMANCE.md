# Performance

Luxid is fast by default, but a stock PHP install throws most of that away.
This guide covers the three settings that matter, in order of payoff, and the
worker runtime that removes per-request boot entirely.

Every number here was measured on PHP 8.5 with the starter application.

---

## The short version

```bash
php juice optimize
```

That rebuilds the package manifest, dumps a classmap autoloader, and tells you
which opcache settings are still costing you. Then apply the php.ini block
below and reload PHP-FPM.

---

## 1. Opcache and preloading

**The single largest win available.** Without preloading, every PHP-FPM worker
compiles and links each class the first time it touches one. That is most of
what a cold request spends its time on — the kernel itself accounts for well
under 10µs of it.

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.preload=/path/to/your-app/preload.php
opcache.preload_user=www-data
```

| | Cold boot |
|---|---|
| No preload | ~700 µs |
| With `preload.php` | **~105 µs** |

The starter ships `preload.php` already. It compiles everything in Composer's
optimised classmap, skipping test and tooling packages.

**Production only.** With `validate_timestamps=0` and preloading enabled, PHP
will not notice code changes until you reload the service:

```bash
sudo systemctl reload php8.3-fpm
```

Leave both off in development.

## 2. An optimised autoloader

```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative
```

PSR-4 autoloading probes the filesystem for each class it has not seen. A
classmap turns that into a hash lookup. `juice optimize` does this for you, and
the starter's `composer.json` already sets `optimize-autoloader`.

Only use `--classmap-authoritative` if you generate no classes at runtime.

## 3. Worker mode

Everything above still pays to *boot* the application once per request:
autoloading, provider discovery, route registration, connecting to the
database. A worker runtime does that once for thousands of requests.

| | Throughput |
|---|---|
| Boot per request | ~34,500 req/s |
| Worker mode | **~168,000 req/s** |

Measured in-process, so it excludes PHP-FPM's process startup — the real-world
gap is wider.

### FrankenPHP

FrankenPHP is the right fit for Luxid: it *is* the PHP SAPI, so superglobals,
`header()`, `echo` and native sessions all keep working. Nothing in your
application changes.

```bash
# Install: https://frankenphp.dev
FRANKENPHP_CONFIG="worker ./web/worker.php" frankenphp php-server -r web/
```

Or with a Caddyfile:

```caddyfile
{
	frankenphp {
		worker {
			file ./web/worker.php
			num 8
		}
	}
}

localhost {
	root * web/
	encode zstd gzip
	php_server
}
```

The starter ships `web/worker.php`. `web/index.php` still serves classic
PHP-FPM requests, and both share `bootstrap/nova.php` so the component registry
is identical either way.

### Tuning the worker

```env
LUXID_WORKER_MAX_REQUESTS=1000
LUXID_WORKER_MEMORY_LIMIT=268435456
```

Workers are retired on a schedule rather than trusted to run forever, so a slow
leak anywhere in your application cannot accumulate indefinitely.

### Writing worker-safe code

A worker reuses the process, so anything static survives the request that set
it. That is exactly what makes workers fast — the metadata cache, the compiled
template cache and the component registry all stay warm — but request-scoped
state must be cleared.

Register anything per-request with the reset registry:

```php
use Luxid\Foundation\RequestScope;

RequestScope::onReset(static function (): void {
    MyPackage::clearCurrentUser();
}, 'my-package.request-state');
```

The engine and Nova register their own. Package providers should do the same in
`boot()`.

Rules of thumb:

- **Never** hold request data in a static — a user, a request id, a tenant
- **Do** hold expensive derived data in a static — parsed metadata, compiled
  templates, a route table
- Close every `ob_start()` on the error path as well as the happy path
- Do not call `exit`/`die` in request handling; it kills the worker

## 4. Compact JSON

API responses are compact in production and pretty printed only when
`debug` is on. Pretty printing costs about a quarter more CPU and roughly
triples the bytes on the wire — for whitespace no client reads.

```php
// config/config.php
'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
```

Override explicitly if you need to:

```php
'pretty_json' => false,
```

## 5. Database

The connection is opened lazily, so routes that never query never pay for it.

Two things worth knowing:

- Emulated prepares are off, so parameters are sent out of band and integers
  and nulls keep their type
- Identifiers cannot be bound by any driver, so `orderBy()` validates them.
  For a `?sort=` parameter use the allowlist helper rather than passing input
  through:

```php
use Rocket\Query\QueryFilter;

QueryFilter::sort($query, $request->query(), sortable: ['title', 'created_at']);
```

Prefer `hydrateMany()` — which `all()` uses — over hydrating rows one at a
time; it resolves the column map once for the whole result set.

## What not to reach for

**Swoole / OpenSwoole** is a poor fit for Luxid. Native sessions do not work
under it, `header()` and `echo` do not reach the client, and its coroutines run
concurrently inside one worker — so every static in the framework would be read
and written by several in-flight requests at once. That is a rearchitecture
around context-local storage, not a configuration change. Reach for it if you
need WebSockets or long-polling, and run it as a separate service.

**RoadRunner** works and is sequential like FrankenPHP, but it is PSR-7-centric
and Luxid has its own `Request`/`Response`, so you would write and maintain a
bridge. FrankenPHP needs none.

## Checklist

- [ ] `php juice optimize`
- [ ] `opcache.enable=1`, `validate_timestamps=0`, `preload=preload.php`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `APP_DEBUG=false`
- [ ] Document root is `web/`, not the project root
- [ ] Consider FrankenPHP worker mode
