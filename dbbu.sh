#!/bin/bash
SHELL=/bin/bash

PS3='Please enter your choice: '
options=("Daily Backup" "Weekly Backup" "Monthly Backup" "Quit")

date=`date +%Y%m%d`
set PASS "infora12!"
select opt in "${options[@]}"
do
    case $opt in
        "Daily Backup")
            tar -cvzf /var/www/syncbackups/HHdailybackup.tar.gz /var/www/html/
			mysqldump --user=root --password=hellosql12 --default-character-set=utf8 hello > /root/../var/www/syncbackups/dbdaily.sql 
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/html/ /
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/syncbackups/dbdaily.sql /
			#/root/../var/www/synbackups/HHdailybackup.tar.gz--progress -avz -e /FTP/bkp_online_sites/ 
			echo "done"
		   	break
            ;;
        "Weekly Backup")
            tar -cvzf /var/www/syncbackups/HHweeklybackup.tar.gz /var/www/html/
			mysqldump --user=root --password=hellosql12 --default-character-set=utf8 hello > /root/../var/www/syncbackups/dbweekly.sql 
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/html/ /
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/syncbackups/dbweekly.sql /
			echo "done"
            break
            ;;
        "Monthly Backup")
            tar -cvzf /var/www/syncbackups/HHmonthlybackup.tar.gz /var/www/html/
			mysqldump --user=root --password=hellosql12 --default-character-set=utf8 hello > /root/../var/www/syncbackups/dbmonthly.sql 
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/html/ /
            #rsync --rsh="ssh admin@82.76.115.219" -avz /var/www/syncbackups/dbmonthly.sql /
			echo "done"
            break
            ;;
        "Quit")
            break
            ;;
        *) echo invalid option;;
    esac
done
