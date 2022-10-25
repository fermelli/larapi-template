<?php

return [

    'modules_folder' => 'api',

    'extra_routes' => [
        'routes' => [
            'middleware' => ['api'],
            'namespace' => 'Controllers',
            'prefix' => 'api',
        ],
        'routes_public' => [
            'middleware' => [],
            'namespace' => 'Controllers',
            'prefix' => null
        ],
    ],

    'extra_routes_namespaces' => [
        'Api' => base_path() . DIRECTORY_SEPARATOR . 'api',
    ],

    'exceptions_formatters' => [
        Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException::class => App\ExceptionsFormatters\UnprocessableEntityHttpExceptionFormatter::class,
        Throwable::class => one2tek\larapi\ExceptionsFormatters\ExceptionFormatter::class
    ]
];
