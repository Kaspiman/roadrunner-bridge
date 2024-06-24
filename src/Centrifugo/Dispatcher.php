<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\Centrifugo;

use Psr\Container\ContainerInterface;
use RoadRunner\Centrifugo\CentrifugoWorker;
use RoadRunner\Centrifugo\CentrifugoWorkerInterface;
use Spiral\Attribute\DispatcherScope;
use Spiral\Boot\DispatcherInterface;
use Spiral\Core\CompatiblePipelineBuilder;
use Spiral\Interceptors\PipelineBuilderInterface;
use Spiral\RoadRunnerBridge\RoadRunnerMode;

#[DispatcherScope(scope: 'centrifugo')]
final class Dispatcher implements DispatcherInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        ?PipelineBuilderInterface $pipelineBuilder = null,
    ) {
        $this->pipelineBuilder = $pipelineBuilder ?? $container->get(CompatiblePipelineBuilder::class);
    }

    public static function canServe(RoadRunnerMode $mode): bool
    {
        return \PHP_SAPI === 'cli' && $mode === RoadRunnerMode::Centrifuge;
    }

    public function serve(): void
    {
        /** @var Server $server */
        $server = $this->container->get(Server::class);
        /** @var CentrifugoWorker $worker */
        $worker = $this->container->get(CentrifugoWorkerInterface::class);

        $server->serve($worker);
    }
}
