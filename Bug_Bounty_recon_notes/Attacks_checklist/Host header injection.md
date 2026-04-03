
#### how to create virtual host 

```bash
sudo nano /etc/hosts

#add
127.0.0.1 app1.local
127.0.0.1 app2.local
```


##### Doing in apache2 virtual host
```bash
sudo apt update
sudo apt-get install apache2 -y

#create directories
sudo mkdir -p /var/www/app1
sudo mkdir -p /var/www/app2

#add sample pages
echo "<h1>App1 Website</h1>" | sudo tee /var/www/app1/index.html
echo "<h1>App2 Website</h1>" | sudo tee /var/www/app2/index.html

#add permission
sudo chown -R $USER:$USER /var/www/app1
sudo chown -R $USER:$USER /var/www/app2


#create virtual host
sudo nano /etc/apache2/sites-available/app1.conf

<VirtualHost *:80>
    ServerName app1.local
    DocumentRoot /var/www/app1

    ErrorLog ${APACHE_LOG_DIR}/app1_error.log
    CustomLog ${APACHE_LOG_DIR}/app1_access.log combined
</VirtualHost>


sudo nano /etc/apache2/sites-available/app2.conf

<VirtualHost *:80>
    ServerName app2.local
    DocumentRoot /var/www/app2

    ErrorLog ${APACHE_LOG_DIR}/app2_error.log
    CustomLog ${APACHE_LOG_DIR}/app2_access.log combined
</VirtualHost>


sudo a2ensite app1.conf
sudo a2ensite app2.conf


sudo systemctl restart apache2



```

##### Nginx virtual host (server blocks)
```bash
sudo apt install nginx -y

sudo nano /etc/nginx/sites-available/app1
server {
    listen 80;
    server_name app1.local;

    root /var/www/app1;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }
}

sudo nano /etc/nginx/sites-available/app2
server {
    listen 80;
    server_name app2.local;

    root /var/www/app2;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }
}

#Enable sites (symbolic links)
sudo ln -s /etc/nginx/sites-available/app1 /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/app2 /etc/nginx/sites-enabled/

#Test config
sudo nginx -t

#restart nginx
sudo systemctl restart nginx
```