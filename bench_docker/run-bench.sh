#!/usr/bin/env bash
# Run inside the `bench` container (docker compose exec bench bash run-bench.sh).
#
# Builds C, Rust and Go extensions against the *same* PHP build (the one
# baked into this image's php-config, identical to the one FrankenPHP embeds)
# and benchmarks them under two FrankenPHP binaries built with the exact same
# xcaddy invocation:
#   - frankenphp_dynamic: vanilla FrankenPHP, loads the C and Rust .so dynamically
#   - frankenphp_go: FrankenPHP with the Go extension statically embedded
set -euo pipefail

cd /app
N="${1:-30}"

echo "== [1/4] Build extension C =="
(cd fibo_c/fibonacci && phpize && ./configure --quiet && make -s)

echo "== [2/4] Build extension Rust (release) =="
(cd fibo_rust && cargo build --release --quiet)

echo "== [3/4] Build frankenphp_dynamic (sans Go embed) =="
CGO_ENABLED=1 XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s'" \
  CGO_CFLAGS="-D_GNU_SOURCE $(php-config --includes)" \
  CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" \
  xcaddy build \
    --output bench_docker/frankenphp_dynamic \
    --with github.com/dunglas/frankenphp=github.com/dunglas/frankenphp@v1.12.7 \
    --with github.com/dunglas/frankenphp/caddy=github.com/dunglas/frankenphp/caddy@v1.12.7

echo "== [4/4] Build frankenphp_go (avec Go embed) =="
CGO_ENABLED=1 XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s' -tags=nobadger,nomysql,nopgx,nowatcher" \
  CGO_CFLAGS="-D_GNU_SOURCE $(php-config --includes)" \
  CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" \
  xcaddy build \
    --output bench_docker/frankenphp_go \
    --with github.com/dunglas/frankenphp=github.com/dunglas/frankenphp@v1.12.7 \
    --with github.com/dunglas/frankenphp/caddy=github.com/dunglas/frankenphp/caddy@v1.12.7 \
    --with example.com/fibo_golang=./fibo_golang

mkdir -p bench_docker/ini.d
cat > bench_docker/ini.d/fibonacci_c.ini <<EOF
extension=$(realpath fibo_c/fibonacci/modules/fibonacci.so)
EOF
cat > bench_docker/ini.d/fibonacci_rust.ini <<EOF
extension=$(realpath fibo_rust/target/release/libfibo_rust.so)
EOF

echo
echo "############ C vs Rust (chargées dynamiquement dans frankenphp_dynamic) ############"
PHP_INI_SCAN_DIR=/app/bench_docker/ini.d ./bench_docker/frankenphp_dynamic php-cli bench_docker/bench_c_rust.php "$N"

echo
echo "############ Go (embarqué statiquement dans frankenphp_go) ############"
./bench_docker/frankenphp_go php-cli fibo_golang/bench.php "$N"
