# syntax=docker/dockerfile:1

# Same base as fibo_golang/Dockerfile: the `builder` variant ships the exact
# PHP build (headers, libs, php-config) FrankenPHP links against, plus a
# matching Go toolchain. Building the C and Rust extensions against this same
# php-config (instead of the host's system php) keeps everyone on one PHP
# build/ABI, so the three benchmarks become comparable.
FROM dunglas/frankenphp:1.12.7-builder-php8.5-trixie
COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy

RUN apt-get update && apt-get install -y --no-install-recommends \
    autoconf automake libtool pkg-config libclang-dev clang \
    && rm -rf /var/lib/apt/lists/*

ENV RUSTUP_HOME=/usr/local/rustup \
    CARGO_HOME=/usr/local/cargo \
    PATH=/usr/local/cargo/bin:/usr/local/go/bin:$PATH
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y --default-toolchain stable

WORKDIR /app
