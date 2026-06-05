# fibonacci Extension

Auto-generated PHP extension from Go code.

## Functions

### fib

```php
fib(int $number): int
```

**Parameters:**

- `number` (int)

**Returns:** int


## Commands

```bash
CGO_ENABLED=1 XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s' -tags=nobadger,nomysql,nopgx,nowatcher" CGO_CFLAGS="-D_GNU_SOURCE $(php-config --includes)" CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" xcaddy build     --output fibo_frankenphp    --with github.com/dunglas/frankenphp=github.com/dunglas/frankenphp@v1.12.7     --with github.com/dunglas/frankenphp/caddy=github.com/dunglas/frankenphp/caddy@v1.12.7      --with example.com/fibo_golang=.
./frankenphp php-cli -r 'var_dump(fib(7));'
```

