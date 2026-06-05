package fibonacci

// export_php:function fib(int $number): int
func fib_golang_gen(number int64) int64 {
	if number <= 0 {
		return 0
	}
	if number == 1 {
		return 1
	}
	return fib_golang_gen(number-1) + fib_golang_gen(number-2)
}
