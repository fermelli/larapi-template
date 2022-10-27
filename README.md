# Plantilla Larapi

Basado en [laravel-api-starter](https://github.com/gentritabazi01/laravel-api-starter) de [Gentrit Abazi](https://github.com/gentritabazi01).

## Dependencias

### LARAPI

[one2tek/larapi](https://github.com/one2tek/larapi)

#### Documentación

[Larapi - Build fast API-s in Laravel.](https://one2tek.github.io/larapi/#/)

![Larapi](pic.png?raw=true "Larapi")

#### Estructura de carpetas

Deben agregarse nuevos directorios en el directorio `api` para cada recurso que se quiera gestionar, por ejemplo para `usuarios`:

```
api
├─ Usuarios
│  ├─ Controllers
|  ├─ Exceptions
|  ├─ Models
|  ├─ Repositories
|  ├─ Requests
|  ├─ Services
|  └─ routes.php
├─ Productos
|  ├─ ...
...
```

Otros directorios pueden ser: `Console`, `Events`, `Listeners`, `Observers`, `Policies`, `Providers`, etc.

## Dependencias de desarrollo

### PHP Codesniffer

Se utiliza [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) como herramienta de linter a traves del paquete [mreduar/laravel-phpcs](https://github.com/mreduar/laravel-phpcs) para seguir estilos de codificacion apropiados para PHP y Laravel.

#### Instalación

```bash
composer require mreduar/laravel-phpcs --dev
```

#### Configuraciones

Las configuraciones se encuentran en el archivo: [phpcs.xml](./phpcs.xml)

#### Ejecución

Y se ejecutan las verificaciones del código con:

```bash
./vendor/bin/phpcs
```

El mismo que genera un reporte de los errores y advertencias para codificación no adecuadas.

Se pueden realizar correcciones automaticamente ejecutando:

```bash
./vendor/bin/phpcbf
```

#### GIT Hook

Adicionalmente se cuenta con un hook para el pre-commit (antes del commit) que verifica que se siguen la reglas establecidas.

````bash
php artisan vendor:publish --provider="Mreduar\LaravelPhpcs\LaravelPhpcsServiceProvider" --tag="hook"```
````

## Idioma

Tiene las traducciones para el idioma español.
