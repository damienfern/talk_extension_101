<?php

/**
 * Compares PHP pur / extension C / extension Rust in un seul et même
 * process PHP (donc un seul et même php-config/opcache/JIT), pour que les
 * trois chiffres soient réellement comparables entre eux.
 *
 * Usage: PHP_INI_SCAN_DIR=/app/bench_docker/ini.d ./frankenphp_dynamic php-cli bench_c_rust.php [n]
 */

function fibo_php(int $n): int
{
    return match ($n) {
        0 => 0,
        1 => 1,
        default => fibo_php($n - 1) + fibo_php($n - 2),
    };
}

function bench(callable $fn, int $n, int $repeats = 3): float
{
    $best = INF;
    for ($i = 0; $i < $repeats; $i++) {
        $start = hrtime(true);
        $fn($n);
        $elapsed = (hrtime(true) - $start) / 1e6; // ms
        $best = min($best, $elapsed);
    }
    return $best;
}

$n = (int) ($argv[1] ?? 30);

$phpMs = bench('fibo_php', $n);
printf("PHP pur   : fibo(%d)      = %-10d en %8.2f ms\n", $n, fibo_php($n), $phpMs);

if (function_exists('fib')) {
    $cMs = bench('fib', $n);
    printf("C         : fib(%d)       = %-10d en %8.2f ms  (x%.1f)\n", $n, fib($n), $cMs, $phpMs / $cMs);
} else {
    fwrite(STDERR, "Extension C non chargée (fonction 'fib' absente)\n");
}

if (function_exists('fibonacci')) {
    $rustMs = bench('fibonacci', $n);
    printf("Rust      : fibonacci(%d) = %-10d en %8.2f ms  (x%.1f)\n", $n, fibonacci($n), $rustMs, $phpMs / $rustMs);
} else {
    fwrite(STDERR, "Extension Rust non chargée (fonction 'fibonacci' absente)\n");
}
