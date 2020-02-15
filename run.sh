#!/bin/bash

set -e

cd docker
docker-compose pull
docker-compose build
docker-compose up
