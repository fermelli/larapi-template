# Plantilla Larapi

Basado en [laravel-api-starter](https://github.com/gentritabazi01/laravel-api-starter) de [Gentrit Abazi](https://github.com/gentritabazi01).

## Instalación y Ejecución

Necesita tener instalado [GIT](https://git-scm.com/) y [Composer](https://getcomposer.org/).

Clone el repositorio

```bash
git clone https://github.com/fermelli/larapi-template.git
```

Instale las dependencias

```bash
composer install
```

## Dependencias

### LARAPI

[one2tek/larapi](https://github.com/one2tek/larapi)

#### Documentación

[Larapi - Build fast API-s in Laravel.](https://one2tek.github.io/larapi/#/)

![Larapi](pic.png?raw=true "Larapi")

#### Estructura de carpetas

Deben agregarse nuevos directorios en el directorio `api` para cada recurso que se quiera gestionar, por ejemplo para `usuarios`:

```txt
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

```bash
php artisan vendor:publish --provider="Mreduar\LaravelPhpcs\LaravelPhpcsServiceProvider" --tag="hook"
```

## Generación de archivos

Para facilitar el desarrollo se pueden utilizar comandos personalizados para generar archivos con la [Estructura de carpetas](#estructura-de-carpetas)

| Comando                                 | Explicación                                                  |
| --------------------------------------- | ------------------------------------------------------------ |
| make:controller-larapitemplate          | Create a new controller class for a Larapi Template          |
| make:exception-not-found-larapitemplate | Create a new exception-not-found class for a Larapi Template |
| make:model-larapitemplate               | Create a new model class for a Larapi Template               |
| make:repository-larapitemplate          | Create a new repository class for a Larapi Template          |
| make:request-create-larapitemplate      | Create a new request-create class for a Larapi Template      |
| make:request-update-larapitemplate      | Create a new request-update class for a Larapi Template      |
| make:routes-larapitemplate              | Create a new routes class for a Larapi Template              |
| make:service-larapitemplate             | Create a new service class for a Larapi Template             |

### Ejemplos

```bash
php artisan make:controller-larapitemplate Productos
```

Generaría el siguiente controlador con la estructura del [stub](./stubs/controller.larapi-template.stub):

```txt
api
├─ Productos
│  └─ Controllers
|     └─ ProductoController.php
...
```

O tambien podria ejecutar:

```bash
php artisan make:controller-larapitemplate Productos/Categoria -a
```

Que generaría los siguientes archivos:

```txt
api
├─ Productos
│  ├─ Controllers
|  |  └─ CategoriaController.php
|  ├─ Exceptions
|  |  └─ CategoriaNoEncontradoException.php
|  ├─ Models
|  |  └─ Categoria.php
|  ├─ Repositories
|  |  └─ CategoriaRepository.php
|  ├─ Requests
|  |  ├─ CategoriaActualizarRequest.php
|  |  └─ CategoriaCrearRequest.php
|  ├─ Services
|  |  └─ CategoriaService.php
|  └─ routes.php
...
```

## Test

```bash
cp .env .env.testing
```

```bash
php artisan test
```

## Idioma

Tiene las traducciones para el idioma español.
