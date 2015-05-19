## サーバ
- AWS EC2 Ubuntu Server 12.04 LTS
- AWS RDS MySQL5.5

## 必要なパッケージのインストール
```sh
sudo apt-add-repository ppa:awstools-dev/awstools
sudo apt-get update
sudo apt-get install language-pack-ja
sudo apt-get install rdscli
sudo apt-get install apache2
sudo apt-get install php5 php5-mysql php5-curl
sudo apt-get install mysql-client
sudo apt-get install git
```

## RDS CLIの設定
- credentialファイルは予め準備しておくこと
- 参考
  - http://dev.classmethod.jp/cloud/p5498/
  - https://help.ubuntu.com/community/EC2StartersGuide

```sh
#~/.bashrc
#最後に追記
export AWS_CREDENTIAL_FILE=~/credential
export EC2_REGION=ap-northeast-1
```

## RDSの設定
```sh
name="devtool"
rds-modify-db-parameter-group $name -p "name=character_set_client, value=utf8, method=immediate"
rds-modify-db-parameter-group $name -p "name=character_set_connection, value=utf8, method=immediate"
rds-modify-db-parameter-group $name -p "name=character_set_database, value=utf8, method=immediate"
rds-modify-db-parameter-group $name -p "name=character_set_results, value=utf8, method=immediate"
rds-modify-db-parameter-group $name -p "name=character_set_server, value=utf8, method=immediate"
rds-modify-db-parameter-group $name -p "name=skip-character-set-client-handshake, value=1, method=pending-reboot"
```

## PHPの設定

```php
#/etc/php5/apache2/php.ini
error_reporting = E_ALL | E_STRICT
display_errors = On
session.cookie_lifetime = 86400
session.gc_maxlifetime = 86400
```

## devtoolのダウンロード

```sh
mkdir ~/www
cd ~/www
git clone git@github.com:h13i32maru/devtool.git
cd devtool
composer install

cd app
mkdir tmp
mkdir tmp/logs
mkdir tmp/twig
chmod -R 777 tmp

cd config/
#ドメインなどの必要な設定を行う
cp _sample_core.php core.php
#参考 https://code.google.com/apis/console/#access
cp _sample_google.php google.php

```

##Apacheの設定
```apache
#/etc/apache2/sites-available/devtool
<VirtualHost *:80>
    DocumentRoot /home/ubuntu/www/devtool/app/webroot/
    ErrorLog /home/ubuntu/www/devtool/app/tmp/logs/error.log
    CustomLog /home/ubuntu/www/devtool/app/tmp/logs/access.log custom_tsv
    <Directory /home/ubuntu/www/devtool/app/webroot>
        Order Allow,Deny
        Allow from all
        Options FollowSymlinks
        AllowOverride All
    </Directory>
</VirtualHost>
```

```sh
sudo a2dissite default
sudo a2ensite devtool
sudo a2enmod rewrite
sudo /etc/init.d/apache2 restart
```
