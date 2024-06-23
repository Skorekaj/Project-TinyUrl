#!/bin/bash
docker ps -q | while read i; do docker stop $i; done
