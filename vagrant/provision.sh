#!/usr/bin/env bash
export DEBIAN_FRONTEND=noninteractive

# Locales
sudo locale-gen

# UTC
#sudo timedatectl set-timezone UTC
sudo ln -fs /usr/share/zoneinfo/Etc/Rome /etc/localtime
sudo dpkg-reconfigure -f noninteractive tzdata

# Update
sudo add-apt-repository ppa:ondrej/php
sudo add-apt-repository ppa:ondrej/nginx
sudo apt update -y
sudo apt install -y vim git curl wget unzip zip supervisor build-essential libssl-dev software-properties-common \
                    ca-certificates apt-transport-https gnupg-agent chromium-browser

# MySql
echo "mysql-server mysql-server/root_password password secret" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password secret" | sudo debconf-set-selections
sudo apt -y install mysql-server
sudo mysql_install_db
# mysql_secure_installation
sudo mysql -u root -psecret -e "DELETE FROM mysql.user WHERE User=''"
sudo mysql -u root -psecret -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
sudo mysql -u root -psecret -e "DROP DATABASE IF EXISTS test"
sudo mysql -u root -psecret -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'"
sudo mysql -u root -psecret -e "CREATE DATABASE IF NOT EXISTS test DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci"
sudo mysql -u root -psecret -e "CREATE DATABASE IF NOT EXISTS amz DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci"
sudo mysql -u root -psecret -e "CREATE DATABASE IF NOT EXISTS amz_logs DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci"
sudo mysql -u root -psecret -e "FLUSH PRIVILEGES"

# PHP
sudo apt install -y php8.0-cli php8.0-fpm php8.0-mysqlnd php8.0-dom php8.0-mbstring php8.0-curl php-json php8.0-cgi \
                    php8.0-gd php8.0-bz2 php8.0-zip php8.0-bcmath php8.0-ctype php8.0-intl php8.0-soap php8.0-xml \
                    php8.0-gmp php8.0-imagick php8.0-redis php8.0-xdebug php8.0-imap
#sudo apt install -y memcached libmemcached-tools libxmlsec1 sendmail

# Xdebug
echo '[xdebug]' | sudo tee /etc/php/8.0/fpm/conf.d/20-xdebug.ini
echo 'zend_extension=xdebug.so' | sudo tee -a /etc/php/8.0/fpm/conf.d/20-xdebug.ini
echo 'xdebug.mode=develop,coverage,debug,trace' | sudo tee -a /etc/php/8.0/fpm/conf.d/20-xdebug.ini
echo 'xdebug.client_host=127.0.0.1' | sudo tee -a /etc/php/8.0/fpm/conf.d/20-xdebug.ini
sudo cp /etc/php/8.0/fpm/conf.d/20-xdebug.ini /etc/php/8.0/cli/conf.d/20-xdebug.ini
sudo sed -i "s/;sendmail_path.*/sendmail_path='\/usr\/local\/bin\/mailhog sendmail info@fitplay.local'/" /etc/php/8.0/fpm/php.ini
sudo sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/8.0/fpm/php.ini
echo 'max_input_time = 5400' | sudo tee -a /etc/php/8.0/fpm/php.ini # 90 minutes
echo 'post_max_size = 2G' | sudo tee -a /etc/php/8.0/fpm/php.ini    # 2Gb max file size.
echo 'upload_max_filesize = 2G' | sudo tee -a /etc/php/8.0/fpm/php.ini
echo 'memory_limit=256M' | sudo tee -a /etc/php/8.0/fpm/php.ini
sudo sed -i "s/^user = www-data/user = vagrant/" /etc/php/8.0/fpm/pool.d/www.conf
sudo sed -i "s/^group = www-data/group = vagrant/" /etc/php/8.0/fpm/pool.d/www.conf
sudo sed -i "s/^listen.owner = www-data/listen.owner = vagrant/" /etc/php/8.0/fpm/pool.d/www.conf
sudo sed -i "s/^listen.group = www-data/listen.group = vagrant/" /etc/php/8.0/fpm/pool.d/www.conf
sudo sed -i "s/^;listen.mode = 0660/listen.mode = 0660/" /etc/php/8.0/fpm/pool.d/www.conf

# Composer
wget -qO- https://getcomposer.org/installer | php
sudo mv composer.phar /usr/bin/composer

# Install crontab.
cat /home/vagrant/provision/crontab.conf | crontab -

# Nginx
sudo apt install -y python redis-server nginx ffmpeg
sudo mkdir /etc/nginx/ssl
sudo rm /etc/nginx/sites-enabled/default
sudo cp -r /home/vagrant/provision/nginx/ssl/* /etc/nginx/ssl/
sudo cp -r /home/vagrant/provision/nginx/sites-enabled/* /etc/nginx/sites-enabled/
sudo cp -r /home/vagrant/provision/supervisor/* /etc/supervisor/conf.d/
sudo sed -i "s/^user www-data/ user vagrant/" /etc/nginx/nginx.conf
sudo systemctl enable nginx

# Install oh-my-zsh
sudo apt-get -y install zsh
sudo git clone git://github.com/robbyrussell/oh-my-zsh.git /home/vagrant/.oh-my-zsh
sudo cp /home/vagrant/provision/zshrc /home/vagrant/.zshrc
sudo chsh -s $(which zsh) vagrant
sudo git clone https://github.com/zsh-users/zsh-syntax-highlighting.git /home/vagrant/.oh-my-zsh/custom/plugins/zsh-syntax-highlighting

# Restart services
sudo service nginx restart
sudo service php8.0-fpm restart
sudo systemctl enable supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all

# MailHog
wget https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_linux_amd64
sudo mv MailHog_linux_amd64 /usr/local/bin/mailhog
sudo chmod +x /usr/local/bin/mailhog

sudo cp /home/vagrant/provision/service/mailhog.service /lib/systemd/system/mailhog.service
sudo chmod +x /lib/systemd/system/mailhog.service
sudo systemctl daemon-reload
sudo systemctl enable mailhog
sudo systemctl start mailhog

# Node JS
wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.37.2/install.sh | bash
. ~vagrant/.nvm/nvm.sh;nvm install 14.15.4
. ~vagrant/.nvm/nvm.sh;nvm use 14.15.4
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
echo 'Y' | /home/vagrant/.nvm/versions/node/v14.15.4/bin/node /home/vagrant/.nvm/versions/node/v14.15.4/bin/npm install -g @angular/cli

# SSH Keys
cp /home/vagrant/provision/ssh/id_rsa* /home/vagrant/.ssh/
chmod 400 /home/vagrant/.ssh/id_rsa*
ssh-keyscan -H bitbucket.org >> ~/.ssh/known_hosts
ssh-keyscan -H github.com >> ~/.ssh/known_hosts

# Amz Update
/usr/bin/sh /home/vagrant/provision/update.sh

# Database Seeder
cd /home/vagrant/amz-api
php artisan db:seed
