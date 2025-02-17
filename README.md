# Setup Apache, PHP, and Composer on Raspberry Pi 3 for Slackluky/escpos

This guide will help you set up Apache, PHP, and Composer on a Raspberry Pi 3 to use the [Slackluky/escpos](https://github.com/Slackluky/escpos) program.

---

## **1. Update and Upgrade System**

Before starting, make sure your Raspberry Pi OS is up-to-date.

```bash
sudo apt update && sudo apt upgrade -y
```

---

## **2. Install Apache Web Server**

Apache is required to serve PHP applications.

```bash
sudo apt install apache2 -y
```

### Verify Apache Installation

Once installed, verify that Apache is running by opening your browser and navigating to:

```
http://<your-raspberry-pi-ip>
```

You should see the default Apache page.

---

## **3. Install PHP**

Slackluky/escpos requires PHP. Install PHP and necessary extensions:

```bash
sudo apt install php libapache2-mod-php php-cli php-common php-mbstring php-json php-curl php-xml php-zip -y
```

### Verify PHP Installation

Check the installed PHP version:

```bash
php -v
```

You should see the installed PHP version.

---

## **4. Install Composer**

Composer is a dependency manager for PHP and is required to install the Slackluky/escpos library.

### Download and Install Composer

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

### Verify Composer Installation

Check the installed Composer version:

```bash
composer -v
```

---

## **5. Clone the Slackluky/escpos Repository**

Navigate to your Apache document root and clone the repository:

```bash
cd /var/www/html
sudo git clone https://github.com/Slackluky/escpos.git
cd escpos
```

---

## **6. Install Dependencies with Composer**

Run Composer to install the dependencies for Slackluky/escpos:

```bash
composer install
```

---

## **7. Set Permissions**

Ensure the Apache user has the correct permissions for the project directory:

```bash
sudo chown -R www-data:www-data /var/www/html/escpos
sudo chmod -R 775 /var/www/html/escpos
```

---

## **8. Configure Apache**

Set up a virtual host for the Slackluky/escpos program.

### Create a Configuration File

```bash
sudo nano /etc/apache2/sites-available/escpos.conf
```

Add the following content:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/escpos
    <Directory /var/www/html/escpos>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### Enable the Site and Rewrite Module

```bash
sudo a2ensite escpos.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

---

## **9. Test the Setup**

Navigate to your Raspberry Pi's IP in the browser:

```
http://<your-raspberry-pi-ip>
```

You should see the application running.

---

## **10. Configure Printer (Optional)**

Slackluky/escpos is designed for printing via ESC/POS printers. Connect and configure your printer as per the [escpos documentation](https://github.com/Slackluky/escpos).

---

## **Troubleshooting**

### Check Apache Logs

```bash
sudo tail -f /var/log/apache2/error.log
```

### Check PHP Errors

Ensure `display_errors` is enabled in your `php.ini` for debugging:

```bash
sudo nano /etc/php/<version>/apache2/php.ini
```

Set:

```ini
display_errors = On
```

Then restart Apache:

```bash
sudo systemctl restart apache2
```

---

Your Raspberry Pi is now set up to serve the Slackluky/escpos application!

