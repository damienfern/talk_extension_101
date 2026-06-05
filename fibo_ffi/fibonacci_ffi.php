<?php

/* Chargement de la lib partagée compilée depuis libfibonacci.c (voir Makefile). */
$ffi = FFI::cdef(
    'long long fib(long long n);',
    __DIR__ . '/libfibonacci.so'
);

var_dump($ffi->fib(30));
