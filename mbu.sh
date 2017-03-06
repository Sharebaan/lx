#!/bin/bash
SHELL=/bin/bash

date=`date +%Y%m%d`

tar -cvzf /var/www/syncbackups/HHmonthlybackup.tar.gz /var/www/html/
mysqldump --user=root --password=hellosql12 --default-character-set=utf8 hello > /root/../var/www/syncbackups/dbmonthly.sql