# Basics

Ubuntu's Apache2 default configuration is different from the upstream default configuration, and split into several files optimized for interaction with Ubuntu tools. The configuration system is fully documented in `/usr/share/doc/apache2/README.Debian.gz`. Documentation for the web server itself can be found by accessing the manual if the apache2-doc package was installed on this server.

The configuration layout for an Apache2 web server on Ubuntu is as follows:

* `apache2.conf` is the main configuration file. It puts the pieces together by including all remaining configuration files when starting up the web server.
* `ports.conf` is always included from the main configuration file. It is used to determine the listening ports for incoming connections.
* Configuration files in the `mods-enabled/`, `conf-enabled/` and `sites-enabled/` directories contain particular configuration snippets which manage modules, global configuration fragments, and virtual host configurations.
  * They are activated by symlinking available configuration files. These should be managed by using the helpers `a2enmod`/`a2dismod`, `a2ensite`/`a2dissite`, and `a2enconf`/`a2disconf`. See their man pages for detailed information.
* The binary is called `apache2`. Due to the use of environment variables, in the default configuration, apache2 needs to be started/stopped with `/etc/init.d/apache2` or `apache2ctl`.

* Document Roots
  * By default, Ubuntu does not allow access through the web browser to any file apart of those located in `/var/www`, `public_html` directories (when enabled) and `/usr/share` (for web applications). If your site is using a web document root located elsewhere (such as in `/srv`) you may need to whitelist your document root directory in `/etc/apache2/apache2.conf`.
  * The default Ubuntu document root is `/var/www/html`. You can make your own virtual hosts under `/var/www`. This is different to previous releases which provides better security out of the box.


# Standard config

* create html directories & files:

```
    sudo mkdir -p /var/www/edinote.org/public_html
    sudo mkdir -p /var/www/demo.edinote.org/public_html
    sudo chown -R $USER:$USER /var/www/edinote.org
    sudo chown -R $USER:$USER /var/www/demo.edinote.org
    sudo chmod -R 755 /var/www
    vi /var/www/edinote.org/public_html/index.html

        <html>
          <head>
            <title>Test</title>
          </head>
          <body>
            <h1>vhost is working</h1>
          </body>
        </html>
    

    cp /var/www/edinote.org/public_html/index.html /var/www/demo.edinote.org/public_html/
    vi /var/www/demo.edinote.org/public_html/index.html
    
        `vhost 2 is working`
```

* create config files:

```
    sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/edinote.org.conf
    sudo vi /etc/apache2/sites-available/edinote.org.conf
    
<VirtualHost *:80>

	ServerAdmin admin@edinote.org
	ServerName www.edinote.org
	ServerAlias edinote.org haeri.de www.haeri.de
	DocumentRoot /var/www/edinote.org/public_html

	ErrorLog ${APACHE_LOG_DIR}/edinote.org-error.log
	CustomLog ${APACHE_LOG_DIR}/edinote.org-access.log combined

</VirtualHost>

    sudo cp /etc/apache2/sites-available/edinote.org.conf /etc/apache2/sites-available/demo.edinote.org.conf
    sudo vi /etc/apache2/sites-available/demo.edinote.org.conf

<VirtualHost *:80>
	
	ServerAdmin admin@edinote.org
	ServerName demo.edinote.org
	DocumentRoot /var/www/demo.edinote.org/public_html

	ErrorLog ${APACHE_LOG_DIR}/demo.edinote.org-error.log
	CustomLog ${APACHE_LOG_DIR}/demo.edinote.org-access.log combined

</VirtualHost>
```

* enable vhosts:
```
    sudo a2ensite edinote.org.conf
    sudo a2ensite demo.edinote.org.conf
    sudo service apache2 restart
```



# HTTP Proxy for SSH connections


* Load the required modules:
`a2enmod rewrite proxy proxy_connect proxy_http'`

* conf:

```
Listen 443

<VirtualHost ssh.edinote.org:443>

  # ggf. unnoetig
  # ServerName youwebserver:443

  # Only ever allow incoming HTTP CONNECT requests.
  RewriteEngine On
  RewriteCond %{REQUEST_METHOD} !^CONNECT [NC]
  RewriteRule ^/(.*)$ - [F,L]

  ProxyRequests On
  ProxyBadHeader Ignore
  ProxyVia On
  AllowCONNECT 22 1195

  # By default, deny everyone, then allow localhost and public ip
  <Proxy *>
    Require all denied
  </Proxy>
  <Proxy 127.0.0.1>
    Require all granted
  </Proxy>
  # ggf. unnoetig
  <Proxy 77.87.224.99>
    Require all granted
  </Proxy>

  LogLevel warn
  ErrorLog /var/log/apache/ssh.edinote.org-proxy_error_log
  CustomLog /var/log/apache/ssh.edinote.org-proxy_request_log combined

</VirtualHost>
```

