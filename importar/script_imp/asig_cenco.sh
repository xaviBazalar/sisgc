sh  /var/www/html/sisgc/importar/script_imp/asignacion_cenco.sh
unzip /var/www/html/sisgc/rem_ciber_original/rem_AE7`date -d "yesterday" +%Y%m%d`.zip -d  /var/www/html/sisgc/rem_ciber_original
php /var/www/html/sisgc/importar/script_imp/convertir_data_cencosud.php
rm -rf /var/www/html/sisgc/rem_ciber_original/rem_AE7`date -d "yesterday" +%Y%m%d`.zip
