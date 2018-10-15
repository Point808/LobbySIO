![LobbySIO](assets/logo-small.example.png?raw=true "lobbysio")

### Concept ###
Touchscreen or tablet installed in a lobby or waiting room allows guest to self-register.  A security desk operator has a secured interface to log in and manage the registration queue.  

### About ###
Josh North - josh.north@point808.com  
Basic visitor sign-in/sign-out web application.  
Hosted at https://git.point808.com and mirrored to github.
GPLv3. Credit is appreciated.  

### Support ###
Email me or use the tools at https://git.point808.com/Point808/LobbySIO  

### Credits ###
* Bootstrap - http://getbootstrap.com  
* Fontawesome - http://fontawesome.com  
* Jquery - http://jquery.org  
* phpass - http://www.openwall.com/phpass/  

### Requirements ###
* PHP 5, 7  
* MySQL  
* Web server (tested on Apache)  

### Setup ###
1.  MAKE SURE YOUR SERVER CLOCK IS CORRECT!!! This system relies on the server time, not the client machine time.  
2.  Go to your webserver root as a user with write privileges (i.e. /var/www/html).  Clone the repo to whatever sub dir, or use ./ to go to root.  Set permissions and enter directory.  

        cd /var/www/html  
        git clone https://git.point808.com/Point808/LobbySIO.git lobbysio  
        chown -R www-data:www-data lobbysio  
        cd lobbysio  

3. Copy sample language files and settings file.  

        cp src/Language/en.lang.ini.example src/Language/en.lang.ini  
        cp src/Language/es.lang.ini.example src/Language/es.lang.ini  
        cp src/Language/de.lang.ini.example src/Language/de.lang.ini  
        cp src/Language/fr.lang.ini.example src/Language/fr.lang.ini  
        cp src/Config/Registry.example.php src/Config/Registry.php  

4. Set up a database.  Example here uses MySQL - adapt for yours.  

        mysql -u root -p  
        CREATE USER 'lsio_user'@'localhost' IDENTIFIED BY 'yoursecret';  
        CREATE DATABASE lsio;  
        GRANT ALL PRIVILEGES ON lsio. * TO 'lsio_user'@'localhost';  
        FLUSH PRIVILEGES;  
        exit;  
        mysql -u root -p lsio < assets/mysqlSchema.sql  

5. Edit configuration file.  

        nano src/Config/Registry.php  

6. Run a tail to troubleshoot if needed...  

        tail -f /var/log/apache2/error.log  

7. Customize.  Under the assets directory, you may create the following files that will automaticall be used if found:  

        assets/logo-small.png  
        assets/logo-text.png  
        assets/Rules.pdf  

8. Go to the URL.  Default username/password "admin" and "admin1234".  

### Screenshots ###
![Main Page](assets/Main.png?raw=true "Main Page")
![Multi-language](assets/MultiLanguage.png?raw=true "Multi-language")
![Multi-site](assets/MultiSite.png?raw=true "Multi-site")
![Sign In](assets/SignIn.png?raw=true "Sign In")
![Sign Out](assets/SignOut.png?raw=true "Sign Out")
![Approvals](assets/Approvals.png?raw=true "Approvals")
