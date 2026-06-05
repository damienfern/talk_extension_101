/* fibonacci extension for PHP */

#ifdef HAVE_CONFIG_H
# include <config.h>
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_fibonacci.h"
#include "fibonacci_arginfo.h"

static long long fibo_recursive(zend_long n) {
	if (n <= 0) return 0;
	if (n == 1) return 1;
	return fibo_recursive(n - 1)
	      + fibo_recursive(n - 2);
}

ZEND_FUNCTION(fib)
{
	zend_long n;
	zend_long result;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_LONG(n)
	ZEND_PARSE_PARAMETERS_END();

	result = fibo_recursive(n);

	RETURN_LONG(result);
}

PHP_RINIT_FUNCTION(fibonacci)
{
#if defined(ZTS) && defined(COMPILE_DL_FIBONACCI)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif

	return SUCCESS;
}

PHP_MINFO_FUNCTION(fibonacci)
{
	php_info_print_table_start();
	php_info_print_table_row(2, "fibonacci support", "enabled");
	php_info_print_table_row(2, "fibonacci version", PHP_FIBONACCI_VERSION);
	php_info_print_table_end();
}

zend_module_entry fibonacci_module_entry = {
	STANDARD_MODULE_HEADER,
	"fibonacci",					/* Extension name */
	ext_functions,					/* zend_function_entry */
	NULL,							/* PHP_MINIT - Module initialization */
	NULL,							/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(fibonacci),			/* PHP_RINIT - Request initialization */
	NULL,							/* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(fibonacci),			/* PHP_MINFO - Module info */
	PHP_FIBONACCI_VERSION,		/* Version */
	STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_FIBONACCI
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(fibonacci)
#endif
