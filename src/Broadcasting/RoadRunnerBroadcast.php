<?php

declare(strict_types=1);

namespace Spiral\RoadRunnerBridge\Broadcasting;

use Psr\Http\Message\ServerRequestInterface;
use Spiral\Broadcasting\AuthorizationStatus;
use Spiral\Broadcasting\Driver\AbstractBroadcast;
use Spiral\Broadcasting\GuardInterface;
use Spiral\RoadRunner\Broadcast\BroadcastInterface;
use Spiral\RoadRunner\Broadcast\TopicInterface;

final class RoadRunnerBroadcast extends AbstractBroadcast implements GuardInterface
{
    public function __construct(
        private readonly BroadcastInterface $broadcast,
        private readonly GuardInterface $guard
    ) {
    }

    /**
     * @param non-empty-list<string> $topics
     * @param non-empty-list<string> $messages
     *
     * @throws \Spiral\RoadRunner\Broadcast\Exception\BroadcastException
     */
    public function publish(iterable|string|\Stringable $topics, iterable|string $messages): void
    {
        $this->broadcast->publish($topics, $messages);
    }

    public function join(iterable|string|\Stringable $topics): TopicInterface
    {
        return $this->broadcast->join($topics);
    }

    public function authorize(ServerRequestInterface $request): AuthorizationStatus
    {
        return $this->guard->authorize($request);
    }
}
