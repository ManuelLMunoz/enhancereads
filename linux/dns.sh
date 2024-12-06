#!/bin/bash

# Verificar si el script se ejecuta como root
[ "$EUID" -ne 0 ] && echo "Por favor, ejecute este script como root." && exit 1

# Función de barra de progreso
progress_bar() { echo "[$(date '+%T')] $1"; }

# Instalar dependencias
progress_bar "Instalando dependencias..."
apt-get update && apt-get install -y bind9

# Configurar servidor DNS
progress_bar "Configurando servidor DNS..."

cat <<EOF > /etc/bind/named.conf.local
zone "enhancereads.com" {
    type master;
    file "/etc/bind/zones/db.enhancereads.com";
};

zone "1.168.192.in-addr.arpa" {
    type master;
    file "/etc/bind/zones/db.192.168.1";
};
EOF

# Crear directorio para zonas
mkdir -p /etc/bind/zones

# Configuración de zona directa
cat <<EOF > /etc/bind/zones/db.enhancereads.com
\$TTL    604800
@       IN      SOA     dns.enhancereads.com. admin.enhancereads.com. (
                              1         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL

@       IN      NS      dns.enhancereads.com.
client  IN      A       192.168.1.100
web     IN      A       192.168.1.15
dns     IN      A       192.168.1.10
www     IN      CNAME   web.enhancereads.com.

EOF

# Configuración de zona inversa
cat <<EOF > /etc/bind/zones/db.192.168.1
\$TTL    604800
@       IN      SOA     dns.enhancereads.com. admin.enhancereads.com. (
                              1         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL

@      IN      NS      dns.enhancereads.com.
10     IN      PTR     dns.enhancereads.com.
15     IN      PTR     web.enhancereads.com.
100    IN      PTR     client.enhancereads.com.
EOF

# Verificar configuración
progress_bar "Verificando configuración..."
named-checkconf
named-checkzone enhancereads.com /etc/bind/zones/db.enhancereads.com
named-checkzone 1.168.192.in-addr.arpa /etc/bind/zones/db.192.168.1
systemctl restart bind9
systemctl status bind9

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
      addresses: [192.168.1.10/24]
      nameservers:
        addresses: [192.168.1.10] # IP del servidor DNS
EOF
netplan apply

# Modificar resolv.conf para que use siempre el servidor DNS local
echo -e "nameserver 127.0.0.1" > /etc/resolv.conf
echo -e "[Resolve]\nDNS=127.0.0.1" > /etc/systemd/resolved.conf
systemctl restart systemd-resolved
rm -f /etc/resolv.conf
ln -s /run/systemd/resolve/resolv.conf /etc/resolv.conf

# Fin del script
progress_bar "Instalación completada."
