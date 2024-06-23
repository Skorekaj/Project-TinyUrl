#!/bin/bash

docker run -dit --rm --name mysql-server -p 3306:3306 --env MYSQL_ROOT_PASSWORD=jaas -v ~/mysql_docker/mysql-data:/var/lib/mysql mysql:latest
docker run -dit --name "ubuntu-lighttpd-php" --rm -p 80:80 -v $(pwd):/var/www/html ubuntu_post_install
docker exec -it ubuntu-lighttpd-php service apache2 start

