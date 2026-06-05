<?php

/**
 * Usage: ./fibo_frankenphp php-cli bench.php [n]
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

if (!function_exists('fib')) {
    fwrite(STDERR, "Fonction 'fib' introuvable — lancer avec ./fibo_frankenphp php-cli bench.php\n");
    exit(1);
}

$extMs = bench('fib', $n);
printf("Extension : fib(%d)  = %-10d en %8.2f ms\n", $n, fib($n), $extMs);

printf("Speedup   : x%.1f\n", $phpMs / $extMs);
