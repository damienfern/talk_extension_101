---
# try also 'default' to start simple
theme: seriph
# 'auto' = suit le thème système, toggle bouton disponible en bas
colorSchema: 'auto'
# some information about your slides (markdown enabled)
title: PHP Extension 101 
# apply UnoCSS classes to the current slide
class: text-center no-subtitle-style
# https://sli.dev/features/drawing
drawings:
  persist: false
# slide transition: https://sli.dev/guide/animations.html#slide-transitions
transition: slide-left
# enable Comark Syntax: https://comark.dev/syntax/markdown
comark: true
# duration of the presentation
duration: 35min
---

# PHP Extension 101

Invisibles mais indispensables

<div class="abs-tl m-6 text-m handwritten">
  {{
    new Date().toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'})
 }}
</div>

<div class="abs-bl m-6 text-xl">
  Pr FERNANDES
</div>

<div class="abs-tr m-6 flex items-center gap-3">
  <img src="/apip-logo.svg" class="h-12" />
  <div class="flex items-center gap-1">
    <div class="w-px self-stretch" style="background: #2fc1c1;"></div>
    <span class="text-lg tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg); color: #2fc1c1;">2026</span>
  </div>
</div>

<div class="abs-br m-6 text-xl">
  <a href="https://github.com/damienfern" target="_blank" class="slidev-icon-btn">
    <carbon:logo-github />
  </a>
</div>


<!-- 
Intro de moi

PUIS 

Rappel que c'est vieux les extensions

- **1994** — Rasmus Lerdorf crée les *Personal Home Page Tools*, un jeu de scripts CGI en C (rebaptisés **PHP/FI** en 1996)
But : conserver la trace des utilisateurs venant consulter son CV sur son site, grâce à l'accès à une base de données.
- **1997** — Zeev Suraski & Andi Gutmans (futurs fondateurs de Zend) réécrivent le parseur en collaboration avec Rasmus → **PHP 3**, avec sa nouvelle API d'extensions
- Le langage reste volontairement **minimal**
- Tout le reste — regex, images, bases de données, JSON, compression... — passe par des **extensions** -->

---

# Pourquoi des extensions ?

<v-clicks>

- **Performance** : du code C natif et compilé, pas d'interprétation
- **Accès bas niveau** : appels système
- **Encapsulation de lib existantes** : librairies C existantes (libcurl, libgd, OpenSSL...)
- **Fonctionnalités impossibles en PHP** : tout ce qui touche au moteur Zend, la persistence entre requêtes...

</v-clicks>

<!--
Q anticipée : "pourquoi pas juste FFI (ext-ffi) plutôt qu'écrire une extension complète ?"
R courte : FFI appelle du C existant, mais les valeurs restent des zval gérés par le GC PHP à chaque appel (donc pas de gain de perf) ; pas d'accès aux structures internes du moteur Zend ; overhead de marshalling à chaque appel FFI. FFI = pratique pour du binding rapide, une extension = nécessaire pour de la vraie perf ou du bas niveau.
-->

---

# Les extensions fournies avec PHP

<v-clicks>

- Le [code source de PHP](https://github.com/php/php-src/tree/master/ext) (`php-src`) embarque déjà des **dizaines d'extensions** 

</v-clicks>

<div class="grid grid-cols-2 gap-4 mt-4">

<v-click>
<div>

### Toujours actives

- `standard`
- `date`
- `reflection`
- `SPL`
- ...etc.

</div>
</v-click>

<v-click>
<div>

### Présentes dans les sources, pas toujours dans le build

- `mysqli`, `pdo_mysql`
- `curl`
- `mbstring`
- `opcache`
- ...etc.

<!-- <span class="text-xs opacity-70">Selon le gestionnaire de paquets, il faut parfois installer un paquet séparé (ex: <code>apt install php-mysqli</code>) avant de pouvoir l'activer</span> -->

</div>
</v-click>

</div>

<v-click>

<div class="mt-6 text-sm opacity-80">
👉 <code>php -m</code> pour lister les extensions actives
</div>

</v-click>

---

# Et les autres

<div class="grid grid-cols-2 gap-4 mt-4 items-center">

<div>

<v-clicks>

- Certaines extensions ne sont **pas fournies** avec PHP
- Le dépôt historique : **PECL** (*PHP Extension Community Library*)

</v-clicks>

</div>

<v-click>

```bash
pecl install redis
```

</v-click>

</div>

<br/>

<v-click>

### Quelques exemples courants

| Extension  | Usage                            |
|------------|----------------------------------|
| `imagick`  | Traitement d'image (ImageMagick) |
| `apcu`     | Cache mémoire local              |
| `xdebug`   | Debug & profiling                |

</v-click>

<!-- 
Xdebug = extension au moteur Zend et pas PHP
Depuis le 20 septembre 2025, PECL est déprécié et une alternative plus moderne est recommandé 👀
 -->

---
layout: center
---

# Et si on créait notre propre extension ?

<!-- C'est en forgeant qu'on devient forgeron donc go la tenter même si ça a peu d'intérêt -->


---
layout: center
---

# Calculer la suite de Fibonacci

---

# Rappel sur la suite de Fibonacci

<br/>

$$F(0) = 0, \quad F(1) = 1, \quad F(n) = F(n-1) + F(n-2)$$

<v-click>

<table class="">
  <thead>
    <tr>
      <th>n</th>
      <th>0</th>
      <th>1</th>
      <th>2</th>
      <th>3</th>
      <th>4</th>
      <th>5</th>
      <th>6</th>
      <th><span v-mark.box.green="2">7</span></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="font-bold">F(n)</td>
      <td>0</td>
      <td>1</td>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td><span v-mark.box.orange="3">5</span></td>
      <td><span v-mark.box.orange="3">8</span></td>
      <td><span v-mark.circle.red="4">13</span></td>
    </tr>
  </tbody>
</table>

</v-click>

<div v-click="3" class="mt-20 text-3xl text-center opacity-80">
  F(5) + F(6) = <strong>5 + 8 = 13</strong> = F(7)
</div>


---
layout: center
---

# Par où on commence ?

<div class="h-full flex flex-col gap-4">

<v-click>

```bash
git clone https://github.com/php/php-src.git
```
</v-click>

<v-click>

```bash
php php-src/ext/ext_skel.php --ext fibonacci --vendor damienfern --dir fibo_c
```
</v-click>
</div>

---

<!-- Les fichiers générés, leur utilité et leur contenu -->

# Les fichiers générés

<span></span>
<v-click>

Quatre fichiers à retenir, du build jusqu'au code de l'extension :

</v-click>

<div class="grid grid-cols-2 grid-rows-2 gap-4 mt-4">

<v-click>

<div class="p-4 h-full" style="border: 2px solid var(--box-build); border-radius:8px; background: color-mix(in srgb, var(--box-build) 8%, transparent);">
  <div class="text-lg font-bold mb-2" style="color: var(--box-build)">🔧 Build</div>
  <code style="color: var(--box-build)">config.m4</code>
</div>

</v-click>

<v-click>

<div class="p-4 h-full" style="border: 2px solid var(--box-decl-c); border-radius:8px; background: color-mix(in srgb, var(--box-decl-c) 8%, transparent);">
  <div class="text-lg font-bold mb-2" style="color: var(--box-decl-c)">📄 Déclaration C</div>
  <code style="color: var(--box-decl-c)">php_fibonacci.h</code>
</div>

</v-click>

<v-click>

<div class="p-4 h-full" style="border: 2px solid var(--box-decl-php); border-radius:8px; background: color-mix(in srgb, var(--box-decl-php) 8%, transparent);">
  <div class="text-lg font-bold mb-2" style="color: var(--box-decl-php)">🐘 Déclaration PHP</div>
  <code style="color: var(--box-decl-php)">fibonacci.stub.php</code> (et <code style="color: var(--box-decl-php)">fibonacci_arginfo.h</code>)
</div>

</v-click>

<v-click>

<div class="p-4 h-full" style="border: 2px solid var(--box-exec); border-radius:8px; background: color-mix(in srgb, var(--box-exec) 8%, transparent);">
  <div class="text-lg font-bold mb-2" style="color: var(--box-exec)">⚙️ Exécution</div>
  <code style="color: var(--box-exec)">fibonacci.c</code>
</div>

</v-click>

</div>

<!-- Avant, voyons comment on charge une extension dans PHP -->

---

# Chargement statique

<div class="grid grid-cols-2 gap-4 mt-4 items-center">

<div>

- Intégrée directement dans le binaire `php`
- `--enable-fibonacci` lors du build du **core PHP**
- Disponible sans configuration au démarrage
- Impossible avec `phpize` (build externe)

</div>

<div>

<img src="/static-loading.svg" class="max-h-full max-w-full object-contain" />

</div>

</div>

---

# Chargement dynamique

<div class="grid grid-cols-2 gap-4 mt-4 items-center">

<div>

- Produit un fichier `.so` (Unix) / `.dll` (Windows)
- Toujours le cas avec `phpize`
- Dans le core PHP : `--enable-fibonacci=shared`
- Activation : `extension=fibonacci` dans `php.ini` ou `-d extension=fibonacci`

</div>

<div>

<img src="/dynamic-loading.svg" class="max-h-full max-w-full object-contain" />

</div>

</div>

<v-click>

<div class="mt-8 flex justify-center">
  <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full border-2 text-lg" style="border-color: var(--chalk-accent); background: color-mix(in srgb, var(--chalk-accent) 12%, transparent);">
    <span class="text-2xl">🎯</span>
    <span>Pour notre exemple&nbsp;: <strong style="color: var(--chalk-accent)">chargement dynamique</strong></span>
  </div>
</div>

</v-click>

<!-- Pourquoi dynamique : faciliter de partager vos extensions. -->

---

# Build : `config.m4`

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small">

```bash {*|1|3-7|9|10-13} {lines: true}
dnl Autotools config.m4 for PHP extension fibonacci

PHP_ARG_ENABLE([fibonacci],
  [whether to enable fibonacci support],
  [AS_HELP_STRING([--enable-fibonacci],
    [Enable fibonacci support])],
  [no])

AS_VAR_IF([PHP_FIBONACCI], [no],, [
  PHP_NEW_EXTENSION([fibonacci],
    [fibonacci.c],
    [$ext_shared],,
    [-DZEND_ENABLE_STATIC_TSRMLS_CACHE=1])
])
```

</div>

<!-- 
<div>

2 outputs :
- une option pour le script configure <br/> `--enable-fibonacci`
- une variable `PHP_FIBONACCI` interne

</div> -->


<!--
But : indiquer au compilateur comment compiler notre extension.
Le fichier généré contient énormément de commentaires dnl d'exemples (pkg-config, lib externe...) qu'on ignore ici : on se concentre sur un chargement dynamique simple.
-->

<!--
AC_DEFINE crée une constante de préprocesseur C HAVE_FIBONACCI=1
$ext_shared = statique ou dynamique. "shared" si dynamique, vide si statique
-DZEND_ENABLE_STATIC_TSRMLS_CACHE = flag pour activer le cache pour gérer les variables globales en environnement multi-thread

Table PHP_NEW_EXTENSION (au cas où question) :
| Argument | Valeur | Rôle |
|---|---|---|
| `extname` | `fibonacci` | Nom de l'extension |
| `sources` | `fibonacci.c` | Fichier(s) source C à compiler pour cette extension |
| `shared` | `$ext_shared` | Variable qui vaut `shared` si on compile en module dynamique (`.so`), sinon la lie statiquement dans le binaire PHP |
| `sapi_class` | *(vide)* | Non utilisé ici — servirait à restreindre le build à certains SAPI (`cli`, etc.) |
| `extra-cflags` | `-DZEND_ENABLE_STATIC_TSRMLS_CACHE=1` | Flag de compilation supplémentaire passé au compilateur |
-->

---

# `php_fibonacci.h`

Le header de l'extension

<style>
.mm-small {
  --slidev-code-font-size: 15px;
  --slidev-code-line-height: 17px;
}
</style>

<div class="h-full flex flex-col gap-4">
<div>

**<u>Rappel :</u>** un fichier header en C = un fichier de déclarations (prototypes de fonctions, constantes etc.)

<img src="/php-header-bridge.svg" class="mt-4 w-full mx-auto" />

</div>
</div>

---

# `php_fibonacci.h`

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small">

```c {*|1-2|4-5|7|9-11} {lines:true}
#ifndef PHP_FIBONACCI_H
# define PHP_FIBONACCI_H

extern zend_module_entry fibonacci_module_entry;
# define phpext_fibonacci_ptr &fibonacci_module_entry

# define PHP_FIBONACCI_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_FIBONACCI)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_FIBONACCI_H */
```

</div>

<!-- PHP_FIBONACCI_H -> protègent contre la double inclusion -->
<!-- Pour notre suite de fibonacci, on garde tel quel car on expose que l'enregistrement de notre extension -->

---

# `fibonacci.stub.php` 

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}

.mm-2small {
  --slidev-code-font-size: 14px;
  --slidev-code-line-height: 18px;
}
</style>


<div v-click class="mm-small">

```php {*} {lines:true}
<?php

function fib(int $number): int {}
```

</div>

<div v-click class="mm-2small">

```c {*} {lines:true}
/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 1d5450bccf1804edb168e273e8811992a6ccaf02 */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_fib, 0, 1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, number, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_FUNCTION(fib);

static const zend_function_entry ext_functions[] = {
	ZEND_FE(fib, arginfo_fib)
	ZEND_FE_END
};
```
</div>

<!--
c'est à partir de ce fichier que `gen_stub.php` génère l'arginfo (`fibonacci_arginfo.h`). ext_skel générait par défaut un stub avec 2 fonctions d'exemple (`test1`/`test2`) — remplacé ici directement par `fib()`.
-->

---

# `fibonacci.c`, le code source de l'extension


<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small">

````md magic-move
```c
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

```
```c
// ...
#include "php.h"
#include "ext/standard/info.h"
#include "php_fibonacci.h"
#include "fibonacci_arginfo.h"
// ...
```
```c {*}
// ...

PHP_MINFO_FUNCTION(fibonacci)
{
  php_info_print_table_start();
  php_info_print_table_row(2, "fibonacci support", "enabled");
  php_info_print_table_end();
}
// ...
```
```c {*}
// ...
PHP_MINFO_FUNCTION(fibonacci)
{
  php_info_print_table_start();
  php_info_print_table_row(2, "fibonacci support", "enabled");
  php_info_print_table_row(2, "fibonacci version", PHP_FIBONACCI_VERSION);
  php_info_print_table_end();
}
// ...
```
```c {*|4|5|10|11|6-9}
// ...
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
// ...
```
````

<!-- ext/standard/info.h : header qui expose les fonctions des infos via phpinfo() PHP_MINFO_FUNCTION -->


</div>

---

<!--
Pourquoi rinit, minit ? Comment fonctionne PHP en fait ?

Share nothing architecture
-->

# Pourquoi MINIT, RINIT ?

<v-clicks>

### Démonstration par l'exemple : PHP-FPM


<img src="/fpm-startup-flow.svg" alt="Démarrage du master -> Spawn des workers -> Réception des requêtes FastCGI de Nginx -> Dispatch vers un worker libre" class="max-h-full max-w-full object-contain" />

</v-clicks>

---

# Pourquoi MINIT, RINIT ?

### Worker php-fpm

<img src="/worker-lifecycle.svg" alt="Cycle de vie MINIT -> Master dispatch -> RINIT -> Run Code -> RSHUTDOWN (boucle) -> MSHUTDOWN" class="max-h-full max-w-full object-contain" />

<!--  
Utilise le crayon de slidev

MINIT : 
  - Chargement des extensions et du moteur.
  - Définition des globales :  les constantes, classes et fonctions pour toute la durée de vie du processus
  - Alloue les ressources globales
  - A l'init du worker

RINIT : 
  - Fréquence : À chaque requête 
  - Action : Prépare variables et superglobales.
  - Mémoire : Alloue un bloc via le ZMM (notamment via la mem_limit).
  - But : Isolation totale ("Share Nothing").

RSHUTDOWN:
  INVERSE de RINIT, on supprime tout ce que Rinit et le code a pu crée (destruction bloc ZMM) -> principe du Share Nothing

La flèche pointillée RSHUTDOWN -> RINIT représente la boucle : à chaque nouvelle requête sur le même worker, on repart de RINIT sans repasser par MINIT. MINIT/MSHUTDOWN n'encadrent qu'une seule fois tout le cycle de vie du worker.
-->


<!-- Source : conférence de Pascal Martin sur le fonctionnement interne de PHP -->

---

# FrankenPHP, pour changer la manière de penser

<v-switch>

<template #1>

### PHP-FPM classique (share nothing total)

<img src="/worker-lifecycle.svg" alt="MINIT -> Master dispatch -> RINIT -> Bootstrap -> Run Code -> RSHUTDOWN (boucle) -> MSHUTDOWN" class="max-h-full max-w-full object-contain" />

</template>

<template #2>

### FrankenPHP mode worker (état applicatif persistant)

<img src="/frankenphp-worker-cycle.svg" alt="MINIT -> RINIT (1x) -> Bootstrap (1x) -> frankenphp_handle_request -> Run Code (état persistant), répété, puis RSHUTDOWN -> MSHUTDOWN" class="max-h-full max-w-full object-contain" />

</template>

</v-switch>


<!-- 
Côté FPM : chaque requête repart d'un heap ZMM vide (vert = cycle complet RINIT/RSHUTDOWN, une fois par requête). Le Bootstrap (violet) — autoload, container, routing — est lui aussi rejoué intégralement à chaque requête, à l'intérieur de cette boucle : c'est le coût qu'on repaye en permanence.
Côté worker : RINIT et RSHUTDOWN (vert) n'encadrent qu'un seul et unique cycle, celui du worker entier — le Bootstrap ne s'exécute qu'une fois, avant la boucle ; entre les requêtes HTTP, seules les superglobales sont rafraîchies (orange) et le container/les statics restent vivants (rouge) jusqu'au recyclage. 
-->

---

## Rendre son extension compatible avec FrankenPHP

<div class="grid grid-cols-2 gap-8 mt-4 items-center">

<v-clicks>

- **Compatible Thread (ZTS)** : https://www.phpinternalsbook.com/php7/extensions_design/globals_management.html
- Se rappeler que **RINIT/RSHUTDOWN ≠ 1 requête** en mode worker

</v-clicks>

<div class="p-8" style="background: #fff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);">
  <img src="/franken-worker.png" class="max-w-full" />
</div>

</div>

 <!-- 

 - extension IMAP non compatible car non ZTS, imagick nécessite des tweaks 
 une extension qui suppose "RINIT = début de requête, RSHUTDOWN = fin de requête" pour son propre état accumule ou perd des données entre requêtes
  →  `Blackfire` : traces/spans ouverts en RINIT et fermés en RSHUTDOWN, donc potentiellement étalés sur tout le worker au lieu d'une requête 
  
  -->

<!--
Q anticipée : "si mon extension a un bug (segfault, mauvais accès mémoire en C), est-ce que ça plante tout le worker/serveur, pas juste ma requête ?"
R courte : oui. En mode worker (FrankenPHP, PHP-FS...), le process C tourne dans le même worker pendant toute sa durée de vie ; un segfault ou une corruption mémoire dans l'extension crashe (ou corrompt l'état de) tout le worker, donc toutes les requêtes suivantes qu'il traite — pas juste la requête en cours. C'est pour ça qu'une extension bien testée (valgrind, ASan, fuzzing) est encore plus critique en mode worker qu'en PHP-FPM classique, où un crash "ne" tue qu'un process relancé à la requête suivante.
-->

---

# `fibonacci.c`, le code source de l'extension

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small grid grid-cols-2 gap-4">

```c {*|2|4-5|7-9|11|13}
// ...
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
// ...
```

<v-click>

```c
// ...
static long long fibo_recursive(zend_long n) {
  if (n <= 0) return 0;
  if (n == 1) return 1;
  return fibo_recursive(n - 1)
        + fibo_recursive(n - 2);
}
// ...
```

</v-click>
</div>

---
layout: center
---

# Comment on peut savoir quels sont les macros disponibles et lesquels utiliser ?


<div class="flex justify-center gap-6">
<v-clicks>

<img src="/gifs/copy_neighbor.gif" style="width: 300px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />
<img src="/gifs/gen_ai.gif" style="width: 300px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />
<img src="/gifs/both.gif" style="width: 300px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />

</v-clicks>
</div>

<v-click>

<div class="mt-6 opacity-80 text-center">
👑 le <a href="https://www.phpinternalsbook.com/" target="_blank">PHP Internals Book</a> 👑
</div>

</v-click>

<!--
- LLM
- Copiez la copie du voisins. PHP étant développé pour être étendu, une bonne partie de son code est dans le dossier `ext/` et peut servir de base pour comprendre comment fonctionne le moteur Zend et tout l'eco système
- LES DEUX !!!


-->


---

<!-- Build & launch -->

# Build & launch

<div class="h-full flex flex-col gap-4">

<v-click>

```bash
phpize && ./configure && make
```
</v-click>

<v-click>

```bash
php -dextension=modules/fibonacci.so -r 'var_dump(fib(30));'
```
</v-click>

</div>

---
layout: center
class: text-center
---

# It works !

<img src="/it-works.png" style="width: 1260px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />

<!-- Benchmark reproductible : php -dextension=modules/fibonacci.so fibo_c/fibonacci/bench.php 30 (best-of-3, hrtime).
Attention en cas de question sur le chiffre : mesuré sur un build PHP DEBUG/NTS, opcache.enable_cli=0 (JIT désactivé par défaut en CLI) -> c'est le pire cas côté PHP pur. Sur un build de prod avec JIT, l'écart serait nettement plus faible. -->

---
title: dancing time
---

<style>
@keyframes dance-pop-in {
  from { opacity: 0; transform: scale(0.5) rotate(var(--r)); }
  to { opacity: 1; transform: scale(1) rotate(var(--r)); }
}
.dance-gif {
  position: absolute;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,.3);
  opacity: 0;
  animation: dance-pop-in 0.25s ease-out forwards;
  animation-delay: var(--d);
}
</style>

<img class="dance-gif" src="/gifs/dance1.gif" style="top:4%; left:2%; width:260px; --r:-15deg; --d:0s;" />

<img class="dance-gif" src="/gifs/dance2.gif" style="top:55%; left:60%; width:230px; --r:8deg; --d:0.5s;" />

<img class="dance-gif" src="/gifs/dance3.gif" style="top:0%; left:42%; width:280px; --r:5deg; --d:1s;" />

<img class="dance-gif" src="/gifs/dance4.gif" style="top:60%; left:4%; width:250px; --r:-6deg; --d:1.5s;" />

<img class="dance-gif" src="/gifs/dance5.gif" style="top:26%; left:65%; width:220px; --r:14deg; --d:2s;" />

<img class="dance-gif" src="/gifs/dance6.gif" style="top:36%; left:0%; width:270px; --r:-9deg; --d:2.5s;" />

<img class="dance-gif" src="/gifs/dance7.gif" style="top:6%; left:64%; width:260px; --r:-4deg; --d:3s;" />

<img class="dance-gif" src="/gifs/dance8.gif" style="top:58%; left:30%; width:240px; --r:11deg; --d:3.5s;" />

<img class="dance-gif" src="/gifs/dance9.gif" style="top:20%; left:18%; width:200px; --r:-18deg; --d:4s;" />

---
layout: center
---

<img src="/gifs/breathe.gif" alt="someone breathing" style="width:400px; box-shadow:0 8px 24px rgba(0,0,0,.4); border-radius:8px;" />

<!-- 
On respire, ça fait beaucoup d'un coup
ET TOI BOIS  -->

---
title: c po ma tasse de the
---

<!-- Mais le C c'est pas ma tasse de thé...

Ma dernière expérience de C, c'est le livre du Zéro (cf photo) il y a plus de 20 ans ! -->

<div class="relative" style="height: 450px;">
  <img v-click src="/site-du-z-livre.png" alt="Site du zero livre C" style="position:absolute; top:70px; left:20px; width:260px; box-shadow:0 8px 24px rgba(0,0,0,.4); border-radius:8px;" />
  <img v-click src="/code-blocks-screenshot.png" alt="Code Blocks Screenshot" style="position:absolute; top:0; left:350px; width:520px; box-shadow:0 8px 24px rgba(0,0,0,.4); border-radius:8px;" />
</div>

---
layout: center
---

# Est-ce qu'on peut développer son extension avec autre chose que le C ? 

---

# Développer en Go

<!-- Un commentaire au-dessus d'une fonction Go, et FrankenPHP se charge du reste -->

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small grid grid-cols-2 gap-4 items-start mt-4">

```go {*|4|5-13}
// fibonacci.go
package fibonacci

//export_php:function fib(int $number): int
func fib_golang_gen(number int64) int64 {
	if number <= 0 {
		return 0
	}
	if number == 1 {
		return 1
	}
	return fib_golang_gen(number-1) + fib_golang_gen(number-2)
}
```

<div class="flex flex-col gap-4">

<v-click>

```bash
# génère le pont C, l'arginfo et le .stub.php
GEN_STUB_SCRIPT="php-src/build/gen_stub.php" frankenphp extension-init fibonacci.go
```

</v-click>

</div>

</div>

<!--
Comparer avec le C : plus de config.m4, plus de php_fibonacci.h à écrire à la main, plus de MINIT/RINIT/MINFO ni de zend_module_entry.

`frankenphp extension-init` génère automatiquement fibonacci_generated.go (le pont CGO), fibonacci_arginfo.h et fibonacci.stub.php à partir de ce seul commentaire `//export_php:function`.

Types simples (int, string, bool, array...) mappés automatiquement Go <-> PHP (table de correspondance sur frankenphp.dev/docs/extensions). Possible aussi de déclarer des classes (`//export_php:class`), des méthodes, des constantes, un namespace.

Build final avec xcaddy comme n'importe quel module Caddy/FrankenPHP : le binaire `frankenphp` produit embarque directement l'extension, pas de .so à charger dynamiquement.

Démo dans fibo_golang/ du repo (Dockerfile basé sur l'image builder de FrankenPHP, docker-compose pour le confort).
-->

---

# Les fichiers générés (Go)

<span></span>

<!-- `frankenphp extension-init fibonacci.go` génère tout ça à partir du seul commentaire `//export_php:function` -->

<style>
.mm-2small {
  --slidev-code-font-size: 18px;
  --slidev-code-line-height: 22px;
}
.gen-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.gen-grid > div {
  min-width: 0;
}
</style>

<div class="mm-2small gen-grid mt-2">

<v-click>

```php
// fibonacci.stub.php
<?php

/** @generate-class-entries */

function fib(int $number): int {}
```

</v-click>

<v-click>

```c
// fibonacci_arginfo.h
ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_fib, 0, 1, IS_LONG, 0)
  ZEND_ARG_TYPE_INFO(0, number, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_FUNCTION(fib);

static const zend_function_entry ext_functions[] = {
	ZEND_FE(fib, arginfo_fib)
	ZEND_FE_END
};
```

</v-click>

</div>

<!--
On retrouve exactement les mêmes briques que côté C, mais générées : `fibonacci.stub.php` (source de vérité, comme en C) et `fibonacci_arginfo.h` (identique aux macros ZEND_ARG vues plus tôt) — tout ça à partir du seul commentaire `//export_php:function` au-dessus de la fonction Go.
-->

---

# Les fichiers générés (Go), suite

<span></span>

<!-- Il manquait le pont CGO et le fibonacci.c généré : les voici -->

<style>
.mm-2small {
  --slidev-code-font-size: 15px;
  --slidev-code-line-height: 18px;
}
.gen-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.gen-grid > div {
  min-width: 0;
}
</style>

<div class="mm-2small gen-grid mt-2">

<v-click>

```go {*|6|4-6|8-12|14-17}
// fibonacci_generated.go (le pont CGO)
package fibonacci

// #include <stdlib.h>
// #include "fibonacci.h"
import "C"

func init() {
	frankenphp.RegisterExtension(
		unsafe.Pointer(&C.fibonacci_module_entry)
  )
}

//export go_fib
func go_fib(number int64) int64 {
	return fib_golang_gen(number)
}
```

</v-click>

<v-click>

```c
// fibonacci.c
#include "fibonacci.h"
#include "fibonacci_arginfo.h"
#include "_cgo_export.h"

zend_module_entry fibonacci_module_entry = {
    STANDARD_MODULE_HEADER, "fibonacci",
    ext_functions, PHP_MINIT(fibonacci),
    NULL, NULL, NULL, NULL,
    "1.0.0", STANDARD_MODULE_PROPERTIES};

PHP_FUNCTION(fib)
{
    zend_long number = 0;
    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_LONG(number)
    ZEND_PARSE_PARAMETERS_END();
    RETURN_LONG(go_fib((long) number));
}
```

</v-click>

</div>

<!--
`fibonacci_generated.go` est le pont CGO annoncé dans les notes de la slide précédente : `import "C"` + le commentaire `#include "fibonacci.h"` juste au-dessus en font un fichier cgo. `init()` appelle `frankenphp.RegisterExtension` avec le `zend_module_entry` généré — c'est l'équivalent Go du `ZEND_GET_MODULE`/`MINIT` qu'on écrivait à la main en C, sauf qu'ici c'est FrankenPHP qui s'enregistre lui-même au chargement du binaire. `//export go_fib` expose la fonction Go au monde C avec la convention d'appel C, via le header généré par cgo `_cgo_export.h`.

`fibonacci.c` généré est le pendant exact du `fibonacci.c` écrit à la main plus tôt : même `zend_module_entry`, même `PHP_FUNCTION(fib)` avec `ZEND_PARSE_PARAMETERS_START`/`Z_PARAM_LONG` — sauf qu'au lieu de contenir la logique métier, il se contente d'appeler `go_fib()`, le symbole exporté par le fichier Go ci-contre.

Un dernier fichier généré, `fibonacci.h`, ne contient qu'une déclaration `extern zend_module_entry fibonacci_module_entry;` — le header minimal inclus par les deux fichiers ci-dessus.

Au total 5 fichiers générés par la seule commande `frankenphp extension-init` : `fibonacci.stub.php`, `fibonacci_arginfo.h`, `fibonacci.h`, `fibonacci.c`, `fibonacci_generated.go`.
-->

---

# Build & launch 

<style>
.mm-build-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 20px;
}
</style>

<div class="mm-build-small">

<v-click>

```bash
CGO_ENABLED=1 XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s' -tags=nobadger,nomysql,nopgx,nowatcher" CGO_CFLAGS="-D_GNU_SOURCE $(php-config --includes)" CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" \
  xcaddy build \
  --output fibo_frankenphp \
  --with github.com/dunglas/frankenphp=github.com/dunglas/frankenphp@v1.12.7 \
  --with github.com/dunglas/frankenphp/caddy=github.com/dunglas/frankenphp/caddy@v1.12.7 \
  --with example.com/fibo_golang=.
```

</v-click>

<v-click>

```bash
./fibo_frankenphp php-cli -r 'var_dump(fib(7));'
```

</v-click>

</div>

---
layout: center
class: text-center
---

# 🎉 🎊 🥳

<img src="/go-fibo-launch.png" style="width: 1260px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3); margin-bottom: 50px;" />

<!-- FrankenPHP (embedding statique) -->

<v-click>

# 🦀 ?

</v-click>

<!--
Vu la hype autour de Rust, est-ce que c'est possible de le faire avec ? 
-->

---

# Créer le projet Rust


<div class="h-full flex flex-col gap-4">

```bash
cargo new --lib fibo_rust
```


```toml
# Cargo.toml
[lib]
crate-type = ["cdylib"]

[dependencies]
ext-php-rs = "*"
```

[ext-php-rs](https://ext-php.rs/)

</div>

<!--
`crate-type = ["cdylib"]` : on ne veut pas un binaire Rust classique mais une lib partagée avec une ABI C — le format que PHP sait charger.

Chargement en dynamique contrairement à Frankenphp qui charge en statique 

-->

---

# En Rust

<!-- Une macro au-dessus d'une fonction Rust, et [ext-php-rs](https://ext-php.rs/) se charge du reste -->

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small mt-4">

```rust {*|4|5-11|13-16}
// src/lib.rs
use ext_php_rs::prelude::*;

#[php_function]
pub fn fibonacci(n: u32) -> u64 {
    match n {
        0 => 0,
        1 => 1,
        _ => fibonacci(n - 1) + fibonacci(n - 2),
    }
}

#[php_module]
pub fn get_module(module: ModuleBuilder) -> ModuleBuilder {
    module.function(wrap_function!(fibonacci))
}
```

</div>

<!--
`#[php_function]` transforme `fibonacci` en fonction PHP appelable ; `wrap_function!` construit son arginfo à partir de la signature Rust (`n: u32` → paramètre, `u64` → retour).

`#[php_module]` déclare le point d'entrée de l'extension (équivalent du `zend_module_entry` en C) : on y enregistre chaque fonction exposée.

Conversion des types PHP <-> Rust automatique via les traits `IntoZval` / `FromZval` (implémentables aussi pour des types custom).

Contrairement au Go, pas d'étape de génération séparée : ce sont des macros procédurales, expansées directement par le compilateur au moment du `cargo build` — voir la slide suivante pour ce qu'elles génèrent concrètement.
-->

---

# Sous la capuche avec `cargo expand`

<span></span>

<!-- Pas de fichier généré ici : `cargo expand` montre juste ce que les macros écrivent à la compilation -->

<style>
.mm-2small {
  --slidev-code-font-size: 14px;
  --slidev-code-line-height: 17px;
}
.gen-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.gen-grid > div {
  min-width: 0;
}
</style>

<div class="mm-2small gen-grid mt-2">

<v-click>

```rust
// expansion de #[php_function]
extern "C" fn handler(
    ex: &mut ExecuteData,
    retval: &mut Zval,
) {
    let num_args = ex.This.u2.num_args;
    if num_args != 1 {
        zend_wrong_parameters_count_error(1, 1);
        return;
    }
    let zval_0 = ex.zend_call_arg(0).unwrap();
    let n: u32 = <u32 as FromZvalMut>
        ::from_zval_mut(zval_0.dereference_mut())
        .unwrap();

    let result = fibonacci(n);
    result.set_zval(retval, false);
}
```

</v-click>

<v-click>

```rust
// expansion de #[php_module]
FunctionBuilder::new("fibonacci", handler)
    .arg(Arg::new("n", <u32 as FromZvalMut>::TYPE))
    .not_required()
    .returns(
        <u64 as IntoZval>::TYPE,
        false,
        <u64 as IntoZval>::NULLABLE,
    )

// ...

extern "C" fn get_module() -> *mut ModuleEntry {
    ModuleBuilder::new("fibo_rust", "0.1.0")
        .function(FUNCTION_ENTRY())
        .startup_function(ext_php_rs_startup)
        .try_into()
        // ...
}
```

</v-click>

</div>

<!--
Pas de `fibonacci_arginfo.h` ni de `fibonacci.c` à ouvrir : `cargo expand` (outil externe) permet de voir ce que le compilateur produit réellement à partir des macros, mais rien n'est écrit sur disque pendant un build normal.

Bloc de gauche : l'expansion de `#[php_function]` — équivalent du `PHP_FUNCTION(fib)` en C. On y retrouve la vérification du nombre d'arguments, la conversion `FromZvalMut` (équivalent de `Z_PARAM_LONG`), l'appel à la vraie fonction Rust, puis `IntoZval` pour renvoyer le résultat.

Bloc de droite : l'expansion de `#[php_module]` — le `FunctionBuilder` construit l'arginfo (type des paramètres/retour, équivalent de `ZEND_ARG_TYPE_INFO`), et `get_module()` construit le `ModuleEntry` (équivalent du `zend_module_entry` en C), avec les fonctions startup/shutdown gérées par ext-php-rs.

Message clé, identique à la slide Go : la macro ne fait rien de magique, elle écrit le même genre de boilerplate C/Zend qu'on a vu à la main — sauf qu'ici c'est fait par le compilateur Rust, en mémoire, à la compilation.
-->

---

# Build & launch

<div class="h-full flex flex-col gap-4">

<v-click>

```bash
cargo build
```

</v-click>

<v-click>

```bash
php -dextension=target/debug/libfibo_rust.so -r 'var_dump(fibonacci(7));'
```

</v-click>

</div>

---
layout: center
class: text-center
---

# 🎉 🎊 🥳

<img src="/rust-fibo-launch.png" style="width: 1260px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3); margin-bottom: 50px;" />

<!-- Benchmarks "à l'arrache" : ./fibo_frankenphp php-cli fibo_golang/bench.php 30 et php -dextension=target/release/libfibo_rust.so fibo_rust/bench.php 30 (best-of-3, hrtime).
Les deux runtimes PHP "purs" ne sont pas comparables entre eux ici (JIT/opcache/build différents entre le binaire fibo_frankenphp et le php CLI système) : ne pas comparer Go et Rust directement l'un à l'autre sur ces chiffres, seulement chaque extension à son propre PHP pur. Slide suivante : la version Docker qui corrige ça. -->
<!-- 
---

# Un bench vraiment comparable, avec Docker

<v-clicks>

- Constat : `fibo_frankenphp` embarque un PHP **ZTS**, compilé sans debug ; le `php` système est **NTS**, souvent un build debug → 2 baselines "PHP pur" non comparables
- Idée : builder C, Rust et Go dans **le même conteneur**, contre le **même** `php-config`/`libphp` → une seule baseline PHP pour tout le monde
- Image de base : `dunglas/frankenphp:builder` (celle qui sert déjà à builder `fibo_frankenphp` avec xcaddy)

</v-clicks>

<v-click>

```bash
docker compose exec bench bash bench_docker/run-bench.sh 30
```

</v-click> -->

<!--
Piège rencontré en le faisant : charger le .so Rust dans fibo_frankenphp échoue d'abord avec `undefined symbol: executor_globals` → mismatch ZTS/NTS, l'ABI d'une extension doit matcher exactement le build PHP qui la charge (thread-safety, php_api version...).

Solution : dans le conteneur, l'extension C (phpize) et l'extension Rust (cargo, via ext-php-rs qui lit php-config) sont buildées contre le php-config du conteneur — exactement celui que xcaddy utilise pour lier fibo_frankenphp. On charge ensuite C et Rust dynamiquement (PHP_INI_SCAN_DIR) dans un binaire "frankenphp_dynamic" (sans le module Go), et on build à côté un "frankenphp_go" (même image, même xcaddy, avec le module Go embarqué) pour la version statique. Les deux binaires partagent la même baseline PHP pur.
-->

---
layout: center
class: text-center
---

# Un petit benchmark

<div class="text-xl">

| | `fib`/`fibonacci`(30) | vs PHP pur (~45,9 ms) |
|---|---|---|
| **C** (dynamique) | 1,6 ms | **×28** |
| **Rust** (dynamique) | 3,6 ms | **×13** |
| **Go** (statique) | 3,6 ms | **×13** |

</div>

<!-- Bench réalisé dans bench_docker/ (best-of-3, hrtime, PHP 8.5 ZTS non-debug, opcache/JIT désactivés en CLI, mêmes flags xcaddy). Cette fois les trois colonnes sont comparables entre elles : même binaire PHP pur, même machine, même run.

Attention à l'interprétation côté public : ça ne dit pas "le C est objectivement 2x plus rapide que Go ici" en général, juste que pour CE micro-benchmark fibo(30) récursif, avec CES builds, l'écart d'overhead d'appel (marshalling zval, cgo pour Go, FFI-like binding pour Rust) se classe dans cet ordre. -->

<!-- 
---

# Quelles limitations avec les wrappers en Go et Rust ? 
-->

---

# Partager son extension avec PIE 🥧

<div class="grid grid-cols-2 gap-4 mt-4 items-center">

<div>

<v-clicks>

- **PIE** (*PHP Installer for Extensions*), le remplacant de PECL
- Basé sur **Composer**, sur l'infrastructure de Packagist

</v-clicks>

</div>

<v-click>
<div>

```bash
pie install damienfern/fibo
```
</div>

</v-click>

</div>

<br/>

<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<v-click>

<!-- ### Côté mainteneur : juste un peu de métadonnées -->

<div class="mm-small">

```json
{
    "name": "damienfern/fibo",
    "type": "php-ext",
    "php-ext": {
        "extension-name": "fibonacci"
    }
}
```

</div>

</v-click>

<v-click>

<div class="mt-4 text-l opacity-80">
Les extensions compatibles : <a href="https://packagist.org/extensions" target="_blank">packagist.org/extensions</a>
</div>

</v-click>

<!--
PIE = le remplaçant officiel de PECL, distribué en PHAR comme Composer. Il installe directement dans l'installation PHP visée (pas dans un projet comme le ferait Composer classique) : détection de la version de PHP, TS/NTS, gestion des toolchains de build (prompt d'install sur Linux/macOS, DLL précompilées sur Windows).

PIE follows the usual PHP extension build and install process, namely:

Le mainteneur n'a quasiment rien à faire de plus que pour un paquet Composer normal : déclarer type: php-ext (ou php-ext-zend pour une extension Zend), donner le nom de l'extension si différent du nom du package, pousser un tag semver, et soumettre le repo sur packagist.org. Des champs optionnels (build-path, configure-options, os-families, support-zts/nts...) permettent d'affiner si besoin.

Message clé : on retrouve exactement le même écosystème que pour publier une lib PHP classique - composer.json, tags git, Packagist - appliqué à la distribution de binaires natifs.
-->

---
layout: center
---

# Professeur, pourquoi je développerais une extension alors que je peux le faire en PHP ?

<v-click>

<img src="/gifs/victory.gif" style="position:absolute; top:10%; left:8%; width:260px; transform:rotate(-10deg); border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />

<img src="/gifs/moved.gif" style="position:absolute; top:45%; left:62%; width:260px; transform:rotate(8deg); border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" />

</v-click>

<!-- Je ne dis qu'il ne faut pas le faire. Je dis qu'il faut bien y réflechir selon vos ressources (humaines et compétences, OPS et devs)  -->

<!-- conclusion -->
---

# Ressources pour approfondir

<div class="grid grid-cols-2 gap-4 text-sm">

<div>

### Les extensions en profondeur
- [Writing PHP Extensions - Derick Rethans](https://youtu.be/WjbKYHzoKM0)
- [One extension, three engines - Derick Rethans - Forum PHP 2015](https://youtu.be/HVzR3vMQbiA)
- [FrankenPHP en dehors des sentiers battus - Kévin DUNGLAS - Forum PHP 2025](https://youtu.be/22Ozs_jLGco)
- [Une extension PHP rouillée - Pierre TONDEREAU - Forum PHP 2022](https://youtu.be/p39ys9TYjdA)
- *Biscuit en PHP : un petit besoin d'authz, un long détour par Rust* - Pierre Tondereau, Forum PHP 2026

</div>

<div>

### PHP Internals
- [L'aventure d'une requête HTTP - Pascal MARTIN - Forum PHP 2024](https://youtu.be/penIr9E0Qbo)
- [Comprenez comment PHP fonctionne, vos applications marcheront mieux - Pascal MARTIN - Forum PHP 2022](https://youtu.be/eWlsd9Vtszs)
- [Types et relation de sous typage en PHP - Gina Peter BANYARD - API Platform Conference 2025](https://www.youtube.com/watch?v=XU1sXb8qo6o)
- [FrankenPHP, dans les entrailles de l'interpréteur PHP - Kévin DUNGLAS - Forum PHP 2022](https://youtu.be/aAwBrz8zdbY)

</div>

</div>

---

# Ressources pour approfondir

<p></p><br/>

### PIE

- [API Platform Conference 2025 - Alexandre Daubois - PIE: The Next Big Thing](https://youtu.be/SagvyFI0EnA)
- [A slice of PIE: revolutionising PHP extension installation - James TITCUMB - Forum PHP 2025](https://youtu.be/UB6GAVQou_U)


<v-click>

## To be continued <span v-click="2">?</span> 

</v-click>

<!-- assumer explicitement à l'oral que c'est la slide sponsor, pour ne pas casser le fil narratif juste avant "Merci". -->

---
layout: none
---


<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Source+Serif+4:ital@1&display=swap');

.vivlio-slide {
  --v-bg: #fff;
  --v-text: #050505;
  --v-gray: #5c5c5c;
  --v-border: rgba(0, 0, 0, 0.15);
  position: absolute;
  inset: 0;
  background: var(--v-bg) !important;
  color: var(--v-text) !important;
  font-family: 'Space Grotesk', ui-sans-serif, sans-serif !important;
  display: grid;
  grid-template-columns: 1fr 1.4fr;
  align-items: stretch;
  box-sizing: border-box;
}

html.dark .vivlio-slide {
  --v-bg: #050505;
  --v-text: #fff;
  --v-gray: #8a8a8a;
  --v-border: rgba(255, 255, 255, 0.15);
}

.vivlio-slide .side {
  padding: 3.5rem 4rem;
  display: flex;
  flex-direction: column;
}

.vivlio-slide .left {
  justify-content: center;
  align-items: flex-start;
  gap: 1.2rem;
  border-right: 1px solid var(--v-border);
}

.vivlio-slide .left img.portrait {
  width: 260px;
  height: 260px;
  object-fit: cover;
  filter: grayscale(1) contrast(1.05);
}

.vivlio-slide .name {
  font-size: 2em;
  font-weight: 700;
  color: var(--v-text);
  margin: 0.5em 0 0.15em 0;
  line-height: 1.1;
}

.vivlio-slide .role {
  color: var(--v-gray);
  font-size: 1.1em;
  margin-bottom: 0.6em;
}

.vivlio-slide .right {
  justify-content: center;
  gap: 2rem;
}

.vivlio-slide .right img.brand {
  height: 28px;
}

.vivlio-slide .right img.brand.brand-dark {
  display: none;
}

html.dark .vivlio-slide .right img.brand.brand-light {
  display: none;
}

html.dark .vivlio-slide .right img.brand.brand-dark {
  display: block;
}

.vivlio-slide .stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.4rem 1.2rem;
}

.vivlio-slide .stat-num {
  font-size: 2.2em;
  font-weight: 700;
  color: var(--v-text);
  line-height: 1;
}

.vivlio-slide .stat-label {
  color: var(--v-gray);
  font-size: 0.8em;
  margin-top: 0.3em;
}

.vivlio-slide .clients-block {
  padding-top: 1.6rem;
  border-top: 1px solid var(--v-border);
}

.vivlio-slide .clients-block .label {
  color: var(--v-gray);
  font-size: 0.75em;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 0.9rem;
}

.vivlio-slide .clients-block .logos {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.2rem 1.6rem;
  align-items: center;
}

.vivlio-slide .clients-block .logos .chip {
  background: #fff;
  border: 1px solid var(--v-border);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 0.7rem;
  height: 48px;
}

.vivlio-slide .clients-block .logos .chip img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
</style>

<div class="vivlio-slide">

<div class="side left">

<img class="portrait" src="/damien-portrait.jpg" />

<div>
<div class="name">Damien Fernandes</div>
<div class="role">Développeur Senior chez <strong style="color: var(--v-text)">Vivlio</strong></div>
<a href="https://linktr.ee/damienfern" target="_blank" style="color: var(--v-text); text-decoration: underline;">https://linktr.ee/damienfern</a>
</div>

</div>

<div class="side right">

<img class="brand brand-light" src="/vivlio-logo.svg" />
<img class="brand brand-dark" src="/vivlio-logo-white.svg" />

<div class="stats-grid">
<div><div class="stat-num">2012</div><div class="stat-label">fondée à Lyon</div></div>
<div><div class="stat-num">4M</div><div class="stat-label">ebooks & audiobooks</div></div>
<div><div class="stat-num">500 000+</div><div class="stat-label">lecteurs en Europe</div></div>
<div><div class="stat-num">1 000+</div><div class="stat-label">points de vente</div></div>
</div>

<div class="clients-block">
<div class="label">Ils nous font confiance</div>
<div class="logos">
<div class="chip"><img src="/clients/cultura.png" /></div>
<div class="chip"><img src="/clients/leclerc.png" /></div>
<div class="chip"><img src="/clients/decitre.png" /></div>
<div class="chip"><img src="/clients/furet-du-nord.png" /></div>
<div class="chip"><img src="/clients/standaard-boekhandel.png" /></div>
<div class="chip"><img src="/clients/systeme-u.png" /></div>
<div class="chip"><img src="/clients/continente.png" /></div>
<div class="chip"><img src="/clients/casa-del-libro.png" /></div>
</div>
</div>

</div>

</div>

<!-- On accompagne les enseignes culturelles européennes avec une plateforme de lecture numérique clé en main, simple et accessible pour leurs lecteurs. -->

---
layout: center
class: text-center
---

# Merci ! 


<img src="/qr_code_slides.png" style="width: 220px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" class="mx-auto mt-8" />

https://damienfern.github.io/talk_extension_101

<PoweredBySlidev mt-10 />

---
layout: center
class: text-center
---

# Bonus

<img src="/trap-card.png" style="height: 400px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.3);" class="mx-auto mt-8" />

---

# Une alternative : FFI


- **FFI** (*Foreign Function Interface*, `ext-ffi` depuis PHP 7.4) : appeler une lib C existante **sans écrire d'extension**
- Pas de `phpize`, pas de compilation contre les headers PHP : juste une lib partagée (`.so`) posée sur le disque


<style>
.mm-small {
  --slidev-code-font-size: 16px;
  --slidev-code-line-height: 18px;
}
</style>

<div class="mm-small grid grid-cols-2 gap-4 mt-6 items-center">

<div>

```c
// libfibonacci.c — du C "nu"
long long fib(long long n) {
  if (n <= 0) return 0;
  if (n == 1) return 1;
  return fib(n - 1) + fib(n - 2);
}
```

```bash
gcc -O2 -shared -fPIC \
  -o libfibonacci.so libfibonacci.c
```

</div>

```php
<?php
$ffi = FFI::cdef(
    "long long fib(long long n);",
    __DIR__ . "/libfibonacci.so"
);

var_dump($ffi->fib(30));
```

</div>

<!-- <div class="mt-6 text-xl text-center">
  <code>fib(30)</code> : <strong>1,53 ms</strong> (FFI) vs <strong>1,66 ms</strong> (extension) vs <strong>329,80 ms</strong> (PHP pur) → <strong>×216</strong>
</div> -->

<!--
Exemple complet et testé dans le repo : fibo_ffi/ (libfibonacci.c, Makefile, fibonacci_ffi.php, bench.php).

Mécanisme : FFI::cdef ne génère rien à la compilation. À l'exécution, libffi construit une description générique de l'appel (un "CIF") et fait un ffi_call() : conversion zval PHP -> types C, puis saut dans la fonction. Une extension classique, elle, enregistre directement un pointeur de fonction C dans la table du moteur Zend — appel natif, sans cette indirection.

Coût réel : quelques dizaines à centaines de ns par appel FFI. Négligeable si la boucle/récursion reste entièrement en C (cas de fib ici, mesuré à ~1.6ms pour FFI ET extension une fois compilées avec les mêmes flags -O2). Deviendrait visible seulement si on faisait un appel FFI par itération depuis PHP (boucle serrée PHP -> C -> PHP -> C...).

Limites par rapport à une extension : pas d'accès aux structures internes du moteur Zend (zval, HashTable, objets...), pas de persistance entre requêtes (MINIT/RINIT), overhead de marshalling à chaque appel. FFI = bon pour binder rapidement une lib C existante (libcurl, libvips...), une extension reste nécessaire pour la vraie perf en boucle ou l'accès bas niveau au moteur.
-->

