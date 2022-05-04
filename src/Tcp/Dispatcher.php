<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\Tcp;

use Psr\Container\ContainerInterface;
use Spiral\Boot\DispatcherInterface;
use Spiral\Boot\FinalizerInterface;
use Spiral\Exceptions\ExceptionReporterInterface;
use Spiral\RoadRunner\Environment\Mode;
use Spiral\RoadRunner\EnvironmentInterface;
use Spiral\RoadRunner\WorkerInterface;

final class Dispatcher implements DispatcherInterface
{
    public function __construct(
        private readonly EnvironmentInterface $env,
        private readonly ContainerInterface $container,
        private readonly FinalizerInterface $finalizer
    ) {
    }

    public function canServe(): bool
    {
        return \PHP_SAPI === 'cli' && $this->env->getMode() === Mode::MODE_TCP;
    }

    public function serve(): void
    {
        /** @var Server $server */
        $server = $this->container->get(Server::class);
        /** @var WorkerInterface $worker */
        $worker = $this->container->get(WorkerInterface::class);

        $server->serve(
            $worker,
            function (\Throwable $e = null): void {
                if ($e !== null) {
                    $this->handleException($e);
                }

                $this->finalizer->finalize(false);
            }
        );
    }

    private function handleException(\Throwable $e): void
    {
        try {
            $this->container->get(ExceptionReporterInterface::class)->report($e);
        } catch (\Throwable) {
            // no need to notify when unable to register an exception
        }
    }
}
