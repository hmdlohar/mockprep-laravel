#!/usr/bin/env bash
set -e

echo "Starting MockPrep Docker environment (Laravel 11 + MySQL 8.0)..."
docker-compose up -d

echo "App running at: http://localhost:8000"
echo "To view logs: docker-compose logs -f app"
