# Bench comparable (C / Rust / Go) via Docker

Objectif : mesurer les trois extensions `fibo_*` contre **une seule et même**
baseline PHP, pour que les speedups soient comparables entre eux (ce qui
n'est pas le cas des benchs "à l'arrache" de `fibo_golang/bench.php` et
`fibo_rust/bench.php`, qui tournent sur deux binaires PHP différents).

## Pourquoi ça ne marchait pas directement

`fibo_frankenphp` embarque un PHP **ZTS**. Charger un `.so` compilé contre le
`php` système (NTS) plante avec `undefined symbol: executor_globals` — l'ABI
d'une extension doit matcher exactement le build PHP qui la charge.

## Approche

On builde tout dans l'image `dunglas/frankenphp:*-builder-*` (celle que
xcaddy utilise déjà pour lier `fibo_frankenphp`), donc contre le même
`php-config`/`libphp` :

- `fibo_c` : `phpize && ./configure && make` → `.so` dynamique
- `fibo_rust` : `cargo build --release` (ext-php-rs lit `php-config` du conteneur) → `.so` dynamique
- deux binaires FrankenPHP buildés avec le **même** appel xcaddy :
  - `frankenphp_dynamic` (vanilla) charge le `.so` C et le `.so` Rust dynamiquement via `PHP_INI_SCAN_DIR`
  - `frankenphp_go` embarque `fibo_golang` statiquement (comme d'habitude)

Les deux binaires partagent donc la même baseline "PHP pur".

## Usage

```bash
cd bench_docker
docker compose up -d
docker compose exec bench bash bench_docker/run-bench.sh 30
```

(Le script re-build C/Rust/Go à chaque run — relancer avec un `n` différent
en argument pour changer la taille de `fibo(n)`.)
