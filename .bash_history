brew install php
sudo yum update -y   # Para Amazon Linux y RHEL
sudo apt update && sudo apt upgrade -y   # Para Ubuntu y Debian
sudo yum groupinstall "Development Tools" -y
sudo yum install curl file git -y
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
sudo yum update -y
sudo amazon-linux-extras list
sudo amazon-linux-extras enable php7.4
sudo yum install -y php php-cli php-fpm php-mysqlnd
php -v
sudo yum install -y httpd
clear
