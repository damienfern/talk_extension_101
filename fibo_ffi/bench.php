<?php

/**
 * Usage: php -dextension=../fibo_c/fibonacci/modules/fibonacci.so bench.php [n]
 * Nécessite libfibonacci.so compilée (make) à côté de ce fichier.
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

$ffi = FFI::cdef(
    'long long fib(long long n);',
    __DIR__ . '/libfibonacci.so'
);
$ffiMs = bench(fn (int $n) => $ffi->fib($n), $n);
printf("FFI       : fib(%d)  = %-10d en %8.2f ms\n", $n, $ffi->fib($n), $ffiMs);
printf("Speedup   : x%.1f (vs PHP pur)\n\n", $phpMs / $ffiMs);

if (!extension_loaded('fibonacci')) {
    fwrite(STDERR, "Extension 'fibonacci' non chargée — lancer avec -dextension=.../modules/fibonacci.so pour comparer aussi avec l'extension.\n");
    exit(0);
}

$extMs = bench('fib', $n);
printf("Extension : fib(%d)  = %-10d en %8.2f ms\n", $n, fib($n), $extMs);
printf("Speedup   : x%.1f (vs PHP pur)\n", $phpMs / $extMs);
