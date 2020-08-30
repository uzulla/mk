`xdebug.remote_enable=On`はPHP_INI_ALLではない

`ssh -L 9009:localhost:9009 target`

- `pecl install xdebug`
- `php -i |grep xdebug`

### php.ini

```
error_reporting=-1
xdebug.remote_enable=On
xdebug.remote_autostart=On
xdebug.remote_host=localhost
xdebug.remote_port=9009
xdebug.var_display_max_children= -1
xdebug.var_display_max_data= -1
xdebug.var_display_max_depth= -1
```


### .htaccess

```
php_value error_reporting -1
php_value xdebug.remote_enable On
php_value xdebug.remote_autostart On
php_value xdebug.remote_host host.docker.internal
php_value xdebug.remote_port 9009
php_value xdebug.var_display_max_children -1
php_value xdebug.var_display_max_data -1
php_value xdebug.var_display_max_depth -1
```
