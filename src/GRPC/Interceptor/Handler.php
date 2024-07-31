<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\GRPC\Interceptor;

use Google\Protobuf\Internal\Message;
use Spiral\Interceptors\Context\CallContextInterface;
use Spiral\Interceptors\HandlerInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\InvokerInterface;
use Spiral\RoadRunner\GRPC\Method;
use Spiral\RoadRunner\GRPC\ServiceInterface;

/**
 * @deprecated
 */
final class Handler implements HandlerInterface
{
    public function __construct(
        private readonly InvokerInterface $invoker,
    ) {}

    public function handle(CallContextInterface $context): string
    {
        $args = $context->getArguments();
        \assert($args['service'] instanceof ServiceInterface);
        \assert($args['method'] instanceof Method);
        \assert($args['ctx'] instanceof ContextInterface);
        \assert(\is_string($args['input']) || null === $args['input']);

        /** @psalm-suppress PossiblyInvalidArgument */
        return $this->invoker->invoke(
            $args['service'],
            $args['method'],
            $args['ctx'],
            (isset($args['message']) && $args['message'] instanceof Message)
                ? $args['message']
                : $args['input'],
        );
    }
}
