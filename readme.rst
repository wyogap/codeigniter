###################
HOWTO Configure
###################

1. Create database using one of sql script in /schema
2. Edit database connection setting in /application/config/database.php
3. Edit base_url setting in /application/config/config.php
4. Create temp folder /application/cache/smarty_templates_cache. If you are in linux, make sure it can be accessed by nginx/httpd user. Easiest is to do "chmod 666 <folder-name>"

*******************
Default User
*******************

* User: superadmin, Paswd: ******
* User: admin, Passwd: ******
* User: user, Passwd: ******

*******************
Clean URL
*******************

To have clean url (without index.php):

1. Configure the webserver to redirect to index.php

If it is apache, use htaccess and mod_rewrite configuration below::

     RewriteEngine on
     RewriteCond %{REQUEST_URI} !^/index.php$
     RewriteRule ^(.+)$ /index.php?url=$1 [NC,L]

If it is nginx, use the following configuration in virtual host block::

     location / {
        try_files $uri $uri/ /index.php?$args;
     }

2. Configure index_page setting in /application/config/config.php. Set it to '' (empty string).
			
