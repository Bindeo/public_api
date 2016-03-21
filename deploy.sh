#!/usr/bin/env bash
cd /var/www/html/bindeo/public_api
composer install
rm -rf var/cache/*