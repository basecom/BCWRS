# BCWRS Firewall
The firewall blocks http requests to the guarded web application by taking the country of origin into account. Based on the geographic origin, a block rule can protect control panels or other possibly vulnerable areas.

## Version
Software Version: 0.1 alpha

## Installation
Copy the bcwrs_fw directory to somewhere outside of your web root, so the web server does not expose the contents to the public.

### Configuration
Edit your config.firewall.php inside your firewall directory. You will find instructions on what the options do in the comments of that file.

After that you need to configure php to set firewall.php as a script that is automatically prepended before every php script. To do this, set auto_prepend_file in your php.ini to the full absolute path of the firewall.php file.
- You can do this by creating a .user.ini in your web root using auto_prepend_file=/var/www/vhosts/yoursite/bcwrs_fw/firewall.php
- You can use .htaccess to modify php configuration
- You can modify your php.ini
