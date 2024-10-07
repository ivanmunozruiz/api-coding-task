#!/bin/sh

until nc -z rabbitmq 5672; do
  echo "Waiting for RabbitMQ to be ready..."
  sleep 1
done

echo "RabbitMQ is up and running!"
