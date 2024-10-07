<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transport;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpFactory;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpTransportFactory as AmqpMessengerTransportFactory;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\Connection;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class AmqpTransportFactory extends AmqpMessengerTransportFactory
{
    /**
     * @throws \AMQPChannelException
     * @throws \AMQPException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     *
     * @phpstan-ignore-next-line
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        $exchanges = [
            'bindings' => $options['exchange']['bindings'] ?? [],
            'name' => $options['exchange']['name'],
        ];

        unset($options['exchange']['bindings']);
        unset($options['transport_name']);

        $amqpTransport = parent::createTransport($dsn, $options, $serializer);
        $connection = Connection::fromDsn($dsn, $options);

        $connection->exchange()->declareExchange();
        $channel = $connection->channel();

        $this->createExchanges($channel, $exchanges);

        return $amqpTransport;
    }

    /**
     * @param array{
     *     bindings: array<int|string, array{type?: string, flags?: int, binding_keys: mixed}>,
     *     name: string
     * } $configuration
     *
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    private function createExchanges(\AMQPChannel $channel, array $configuration): void
    {
        $amqpFactory = new AmqpFactory();

        foreach ($configuration['bindings'] as $exchange_name => $arguments) {
            $exchange = $this->createExchange($amqpFactory, $channel, $exchange_name, $arguments);
            $this->bindMessages($exchange, $configuration['name'], $arguments);
        }
    }

    /**
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     *
     * @phpstan-param array{
     *     type?: string,
     *     flags?: integer
     * } $arguments
     */
    private function createExchange(
        AmqpFactory $factory,
        \AMQPChannel $channel,
        int|string $exchange_name,
        mixed $arguments,
    ): \AMQPExchange {
        $exchange = $factory->createExchange($channel);
        $exchange->setName((string) $exchange_name);
        $exchange->setType($arguments['type'] ?? \AMQP_EX_TYPE_TOPIC);
        $exchange->setFlags($arguments['flags'] ?? \AMQP_DURABLE);
        $exchange->declareExchange();

        return $exchange;
    }

    /**
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     *
     * @phpstan-param array{binding_keys: mixed} $arguments
     */
    private function bindMessages(\AMQPExchange $exchange, string $externalExchangeName, array $arguments): void
    {
        if (!\is_array($arguments['binding_keys'])) {
            $arguments['binding_keys'] = [$arguments['binding_keys']];
        }

        foreach ($arguments['binding_keys'] as $routingKey) {
            $exchange->bind($externalExchangeName, $routingKey);
        }
    }
}
