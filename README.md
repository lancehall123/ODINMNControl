# ODINMNControl
ODIN Masternodes Control

Set up a PHP website that allows you to monitor your ODIN wallet and/or Masternodes.

Assumptions before starting: You've got working Linux system with ODIN wallet(s) setup that allows external connections.

 # Install Apache
 Run the following codes in command line:
 ```
 sudo apt update
 sudo apt install apache2
 ```
 # Make the firewall allow HTTP requests
 ```
 sudo ufw app list
 sudo ufw allow in "Apache Full"
 ```
 
 # Install PHP
 ```
 sudo apt install php libapache2-mod-php php-curl
 ```
 
  # Prioritize PHP files over HTML files
  ```
  sudo nano /etc/apache2/mods-enabled/dir.conf
  ```
  
  Change
  
  ```
  <IfModule mod_dir.c>
      DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
  </IfModule>
  ```
  
  to
  
  ```
  <IfModule mod_dir.c>
      DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
  </IfModule>
  ```
  
  # Restart Apache
  ```
  sudo systemctl restart apache2
  ```
  
  # Upload files of ODINMNControl
  Download the files of ODINMNControl to /var/www/html. 
  Adjust index.php to connect to your wallet(s).
  
  # Navigate to your systems public ip address, you should see your dashboard. You're free to alter anything you want.
  
  
  # Like it? Enough for a small donation?
  I appreciate donation through ODIN on **oYMkg73AiFdbjN1kweaLRDXZf8Jo1jJ7BL**
  
  Good luck
  
