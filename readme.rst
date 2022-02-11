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
