# syntax=docker/dockerfile:1

# The plain dunglas/frankenphp runtime image only ships shared libs for the
# extensions PHP was built with (libxml2, openssl, sqlite3, curl, oniguruma,
# argon2, readline, ncurses...), not their -dev linker packages, so xcaddy
# builds fail at link time with "cannot find -lxml2" etc. The `builder`
# variant already has all of that plus a matching Go toolchain — it's what
# FrankenPHP itself uses to produce custom binaries, so use it directly
# instead of re-installing every -dev package by hand.
FROM dunglas/frankenphp:1.12.7-builder-php8.5-trixie
COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy
