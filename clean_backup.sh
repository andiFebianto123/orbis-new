#!/bin/bash
ls -d -1tr /var/www/html/orbis/storage/app/Laravel/* | head -n -7 | xargs -d '\n' rm -f