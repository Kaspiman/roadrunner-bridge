<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\Tcp\Interceptor;

use Spiral\Core\Container\Autowire;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Interceptors\InterceptorInterface;

/**
 * @psalm-type TLegacyInterceptor = Autowire|CoreInterceptorInterface|class-string<CoreInterceptorInterface>
 * @psalm-type TInterceptor = Autowire|InterceptorInterface|class-string<InterceptorInterface>
 */
interface RegistryInterface
{
    /**
     * @param non-empty-string $server
     *
     * @return array<CoreInterceptorInterface|InterceptorInterface>
     */
    public function getInterceptors(string $server): array;

    /**
     * @param non-empty-string $server
     * @param TInterceptor|TLegacyInterceptor $interceptor
     */
    public function register(
        string $server,
        Autowire|CoreInterceptorInterface|InterceptorInterface|string $interceptor,
    ): void;
}
