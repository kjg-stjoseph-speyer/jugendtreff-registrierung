<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'admin_key' => '123456',
            'mysql_host' => '127.0.0.1',
            'mysql_user' => 'root',
            'mysql_password' => '123456',
            'mysql_database' => 'jugendtreff',
            'notification_email' => "noreply@example.com",
            'logger' => [
                'name' => 'jugendtreff-api',
                //'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'path' => 'php://stdout',
                'level' => Logger::DEBUG,
            ],
        ],
    ]);
};
