#!/bin/sh

until nc -z db 3306; do
  echo "Waitting for db to be ready..."
  sleep 1
done