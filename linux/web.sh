#!/bin/bash

# Verificar si el script se ejecuta como root
[ "$EUID" -ne 0 ] && echo "Por favor, ejecute este script como root." && exit 1

# Función de barra de progreso
progress_bar() { echo "[$(date '+%T')] $1"; }

# Instalar dependencias
progress_bar "Instalando dependencias..."
apt-get update && apt-get install -y apache2 php libapache2-mod-php php-mysql mysql-server curl openssl cifs-utils php-gd php-xml
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar Apache y SSL
progress_bar "Configurando Apache y SSL..."
a2enmod ssl rewrite
mkdir -p /etc/apache2/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/certificate.key -out /etc/apache2/ssl/certificate.crt -subj "/C=ES/ST=Madrid/L=Alcorcon/O=Enhancereads/OU=IT/CN=www.enhancereads.com"

# Renovar el certificado SSL cada año con crontab (El 1 de enero a las 02:00 AM)
cat <<EOF > /usr/local/bin/renew_ssl.sh
#!/bin/bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/certificate.key -out /etc/apache2/ssl/certificate.crt -subj "/C=ES/ST=Madrid/L=Alcorcon/O=Enhancereads/OU=IT/CN=www.enhancereads.com"
systemctl restart apache2
EOF
chmod +x /usr/local/bin/renew_ssl.sh
echo "0 2 1 1 * /usr/local/bin/renew_ssl.sh" | crontab -

# Crear y activar la configuración del VirtualHost
cat <<EOF > /etc/apache2/sites-available/enhancereads.conf
<VirtualHost *:80>
    ServerName enhancereads.com
    Redirect permanent / https://www.enhancereads.com/
</VirtualHost>

<VirtualHost *:443>
    DocumentRoot /var/www/enhancereads/public
    ServerName enhancereads.com
    ServerAlias www.enhancereads.com

    SSLEngine on
    SSLCertificateKeyFile /etc/apache2/ssl/certificate.key
    SSLCertificateFile /etc/apache2/ssl/certificate.crt

    <Directory /var/www/enhancereads/public>
        Require all granted
        AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
EOF

a2ensite enhancereads.conf
a2dissite 000-default.conf
systemctl restart apache2

# Configurar el proyecto y la base de datos
progress_bar "Configurando proyecto y base de datos..."
mv "$(pwd)/enhancereads" /var/www/
cd /var/www/enhancereads && composer update --no-interaction
echo "127.0.0.1 www.enhancereads.com" >> /etc/hosts
mysql -u root -pV1O=z6N^6=wv <<EOF
CREATE DATABASE IF NOT EXISTS enhancereads;
USE enhancereads;
SOURCE /var/www/enhancereads/sql/enhancereads.sql;
ALTER USER "root"@"localhost" IDENTIFIED WITH mysql_native_password BY "V1O=z6N^6=wv";
FLUSH PRIVILEGES;
EOF

# Configurar los permisos de la aplicación
chown -R www-data:www-data /var/www/enhancereads
chmod -R 755 /var/www/enhancereads

# Copia de seguridad de la base de datos
progress_bar "Configurando copia de seguridad semanal de la base de datos..."

# Script para realizar la copia de seguridad
cat <<EOF > /usr/local/bin/backup_bbdd.sh
#!/bin/bash
BACKUP_DIR="/var/backups/mysql"
DATE=\$(date +\%F_\%H-\%M)
mkdir -p \$BACKUP_DIR
mysqldump -u root -pV1O=z6N^6=wv enhancereads > \$BACKUP_DIR/enhancereads_\$DATE.sql
EOF

chmod +x /usr/local/bin/backup_bbdd.sh

# Crear el cron job para la copia de seguridad semanal (domingos a las 2:00 AM)
(crontab -l 2>/dev/null; echo "0 2 * * 0 /usr/local/bin/backup_bbdd.sh") | crontab -

# Configurar las interfaces de red de manera persistente
progress_bar "Configurando las interfaces de red..."
cat <<EOF > /etc/netplan/50-cloud-init.yaml
network:
  version: 2
  ethernets:
    enp0s3:
      dhcp4: yes # Habilitado DHCP para tener conexión en la interfaz NAT
    enp0s8:
      dhcp4: no
      addresses: [192.168.1.15/24]
      nameservers:
        addresses: [192.168.1.10] # IP del servidor DNS
EOF
netplan apply

# Fin del script
progress_bar "Instalación completada."