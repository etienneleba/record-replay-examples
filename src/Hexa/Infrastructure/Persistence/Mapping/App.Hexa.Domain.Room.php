<?php

namespace App\Hexa\Infrastructure\Persistence\Mapping;

use App\Hexa\Infrastructure\Persistence\Postgres\PostgresRoomRepository;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

(function (ClassMetadata $metadata) {
    $builder = new ClassMetadataBuilder($metadata);

    $builder->setTable('rooms');

    $builder->setCustomRepositoryClass(PostgresRoomRepository::class);

    $builder->createField('id', "string")->columnName("id")->makePrimaryKey()->build();

    $builder->createField("number", "string")->columnName("number")->build();

    $builder->createField("isFree", "boolean")->columnName("is_free")->build();

})($metadata);
