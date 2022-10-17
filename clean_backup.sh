#!/bin/bash
ls -d -1tr /home/ifgfglob/public_html/dtbs/storage/app/Laravel/* | head -n -7 | xargs -d '\n' rm -f
