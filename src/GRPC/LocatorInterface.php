<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\GRPC;

use Spiral\RoadRunner\GRPC\ServiceInterface;

interface LocatorInterface
{
    /**
     * Return list of available GRPC services in the form of [interface => object].
     *
     * @return array<class-string<ServiceInterface>, \ReflectionClass<ServiceInterface>>
     */
    public function getServices(): array;
}
