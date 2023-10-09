#!/bin/bash

# Change working directory
cd /var/www/treasurytracker/

# Rewrite config entry
SALT=$(openssl rand -hex 32)
sed -i "s/'salt' => env('SECURITY_SALT', ''),/'salt' => env('SECURITY_SALT', '$SALT'),/g" config/app_local.php

# Sleep while the database server starts
sleep 10

# Clean database and reimport
yes | mysqladmin -uroot -ptesting123 -hdb drop treasurytracker
yes | mysqladmin -uroot -ptesting123 -hdb create treasurytracker
mysql -uroot -ptesting123 -hdb treasurytracker < tt.sql

# Run composer
yes | composer install

# Change ownership
chown www-data:www-data . -R

# Start crontab
service cron start

# Start apache
apache2-foreground
