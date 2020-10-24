<?php
declare(strict_types=1);

use App\Domain\Registration\EventRepository;
use App\Infrastructure\Persistence\Registration\MySqlEventRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        EventRepository::class => \DI\autowire(MySqlEventRepository::class)
    ]);
};
