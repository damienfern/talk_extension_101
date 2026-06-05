/* fibonacci extension for PHP */

#ifndef PHP_FIBONACCI_H
# define PHP_FIBONACCI_H

extern zend_module_entry fibonacci_module_entry;
# define phpext_fibonacci_ptr &fibonacci_module_entry

# define PHP_FIBONACCI_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_FIBONACCI)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_FIBONACCI_H */
