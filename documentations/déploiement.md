Voici la documentation d'installation base sur le tutoriel de owncloud :
https://doc.owncloud.com/server/10.12/admin_manual/installation/quick_guides/ubuntu_20_04.html

Je remercie philémon qui m'a donné le lien vers le tuto, et qui m'a accompagné lors du déploiement

Création des mots de passe aléatoires
```bash
my_domain="Your.Domain.tld"

sec_admin_pwd=$(openssl rand -base64 18)
echo $sec_admin_pwd > /root/.sec_admin_pwd.txt

sec_db_pwd=$(openssl rand -base64 18)
echo $sec_db_pwd > /root/.sec_db_pwd.txt
``` 

Définition du hostname
```bash
hostnamectl set-hostname $my_domain
```

Mise a jour du système
```bash
apt update && apt full-upgrade -y
```

Installation des paquets nécessaires
```bash
apt install -y apache2 libapache2-mod-php php-common mariadb-server php-json php-mysql php-gd wget
```


Création du fichier de configuration apache2
```bash
FILE="/etc/apache2/sites-available/sae.conf"
cat <<EOM >$FILE
<VirtualHost *:80>
	DirectoryIndex index.php index.html
	DocumentRoot /var/www/sae
	<Directory /var/www/sae>
		Options +FollowSymlinks -Indexes
		AllowOverride All
		Require all granted
		SetEnv HOME /var/www/sae
		SetEnv HTTP_HOME /var/www/sae
	</Directory>
</VirtualHost>
EOM
```

Il faut activer ce site
```bash
a2dissite 000-default
a2ensite sae.conf
```

Il faut activer ces modules
```bash
a2enmod dir env headers mime rewrite setenvif
systemctl restart apache2
```



une fois mysql installé il faut faire ceci :
```bash
sed -i "/\[mysqld\]/atransaction-isolation = READ-COMMITTED\nperformance_schema = on" /etc/mysql/mariadb.conf.d/50-server.cnf
systemctl start mariadb
```



```bash
cd /var/www/
wget https://gitlab.com/paul.trochel/sae-but2-s1/-/archive/main/sae-but2-s1-main.tar.gz && \
tar -xf sae-but2-s1-main.tar.gz && \
mv sae-but2-s1-main.tar.gz/ sae/ && \
chown -R www-data sae
```

Lancer les scripts d'initialisation de la BD

Création de la BD et de l'utilisateur
```bash
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS sae; \
GRANT ALL PRIVILEGES ON sae.* TO sae@localhost IDENTIFIED BY '${sec_db_pwd}';"
```

Initialisation des tables
```bash
mysql -u root -D sae < /var/www/sae/bd/bddsae.sql
```

Création de fichier de config du site
```bash
FILE="/var/www/sae/config.json"
cat <<EOM >$FILE
{
  "BaseDonnees": {
    "Systeme": "mysql",
    "Hote": "localhost",
    "Nom": "sae",
    "Utilisateur": "sae",
    "MotDePasse": "$sec_db_pwd"
  },
  "WebSocket": {
    "Protocole": "wss://",
    "Domaine": "$my_domain",
    "Chemin": "/wss",
    "Port": "",
    "PortInterne": 8888
  }
}
EOM
```

Lancement du Websocket
```bash
php websockets/run.php
```


Vérification que les permissions sont bonnes
```bash
cd /var/www/
chown -R www-data. sae
```


une fois ceci fait, le site est opérationel.