# Edinote #

Edinote is a web-based note taking application. It aims to be simple and fast,
while still providing some nice features like tagging and markdown support, and
an easy setup for your own web server.


### Features ###

* Self-hosted: data stays on your own server
* Open Source
* Easy, lightweight installation
* Simple, quick file management
* Instant file search
* Tag support
* Formatted Markdown view option
* Syntax highlighting
* Keyboard shortcuts
* Note storage as text files for simple backup
* Mobile usability
* Multi-user support
* A real good text editor (Ace)
* **no** WYSIWYG - it's Markdown ;)


## Demo ##

Log in with credentials 'demo' / 'demo' at [demo.edinote.org](https://demo.edinote.org).
Please note that user settings and files will not be stored permanently in the demo.


## Installation

### Prerequisites

Edinote runs with a standard Apache or Nginx + PHP setup. PHP is tested for version 5.5.

As its database, Edinote comes with SQLite per default, so the below instructions are
also for using it with SQLite. Installation setup for usage with MySQL is in development.

For example, to get started on Ubuntu 14.04 with Apache, you can use the following
installation command:

    sudo apt-get update && apt-get install apache2 php5 libapache2-mod-php5 php5-sqlite

You can use a simple standard vhost like the Apache example below. For production however, you should use
SSL/HTTPS, for example with a certificate from [letsencrypt](https://letsencrypt.org/getting-started/).

    <VirtualHost *:80>

    	ServerName edinote.example.com
    	DocumentRoot /var/www/edinote.example.com/public

    	ErrorLog ${APACHE_LOG_DIR}/edinote.example.com-error.log
    	CustomLog ${APACHE_LOG_DIR}/edinote.example.com-access.log combined

    </VirtualHost>

### Install

To install Edinote, choose the latest [release](https://github.com/haeringer/edinote/releases),
download and unpack it in your web server document root (for example, /var/www/ on Ubuntu):

    cd /var/www/
    sudo wget https://github.com/haeringer/edinote/archive/vX.X.X.tar.gz
    sudo tar xf vX.X.X.tar.gz

Rename the unpacked directory to the name you configured in the web server vhost:

    sudo mv edinote-X.X.X edinote.example.com

Grant the web server user (for example, 'www-data' on Ubuntu) owner permissions
on the Edinote data directory:

    sudo chown -R www-data:www-data edinote.example.com/data

After installation, restart your web server (e.g. `service apache2 restart` on Ubuntu)
and login as user '**admin**' with password '**edinote**'. After login, change the
default password in the user settings.

### Move the data directory (recommended)

In production environments, it is recommended to place the data directory outside
of the web server document root, for example on an external storage or at /var/lib/:

    sudo mkdir /var/lib/edinote
    sudo mv edinote.example.com/data /var/lib/edinote/

Change the DATADIR path in the Edinote configuration:

    sudo vi edinote.example.com/includes/constants.php
    define("DATADIR", "/var/lib/edinote/data/");

### Data Import

If you already have a bunch of text files that you'd like to import into Edinote,
you can just copy those files into the data directory of your user (e.g. into data/admin/)
and Edinote will read them in, updating the database automatically at page reload.


## License

Copyright (c) 2015 Ben Haeringer (MIT License)

See LICENSE.txt for more info.