#!/bin/bash
/usr/bin/expect <<EOD
spawn scp Ae7@148.243.71.213:/u02/cyber/rcvry/cybertemp/cencosud/Ae7/rem_AE7`date -d "yesterday" +%Y%m%d`.zip /var/www/html/sisgc/rem_ciber_original
			set pass "Ae7cencosud"
			set timeout 350
			expect {
			password: {send "Ae7cencosud\r"; exp_continue}
			}
EOD
