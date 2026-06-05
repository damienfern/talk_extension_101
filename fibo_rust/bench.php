<?php

/**
 * Usage: php -dextension=target/release/libfibo_rust.so bench.php [n]
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
printf("PHP pur   : fibo(%d) = %-10d en %8.2f ms\n", $n, fibo_php($n), $phpMs);

if (!function_exists('fibonacci')) {
    fwrite(STDERR, "Fonction 'fibonacci' introuvable — lancer avec -dextension=target/release/libfibo_rust.so\n");
    exit(1);
}

$extMs = bench('fibonacci', $n);
printf("Extension : fibonacci(%d) = %-10d en %8.2f ms\n", $n, fibonacci($n), $extMs);

printf("Speedup   : x%.1f\n", $phpMs / $extMs);
