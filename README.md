Wordpress Remote Installer
==========================

Installing Wordpress is relatively easy. Their developers have done alot of work and spent alot of time making the actual installation as easy as possible, but problems persist, specifically getting Wordpress onto the actual server to run that installation.

Some webhosts offer services allowing for Wordpress to be automatically installed into a site, but these installs often contain bloatware in the form of extensions or themes which, frankly, are useless.

## The Old Way
Previously, the only way to install Wordpress onto webhosts who do not offer automated installs (or onto webhosts who do, but where you want to avoid that bloatware) was by:

1. Going to the Wordpress website
2. Downloading the latest version of Wordpress
3. Extracting Wordpress on your computer
4. Connecting to the Server with an FTP client
5. Uploading the numerous Wordpress files

## The New Way
The Wordpress Remote Installer aims to eliminate almost all of these steps, instead, installing Wordpress would be as simple as:

1. Upload **wp-remote-install.php**
2. (Optional) Upload **wp-remote-plugins.txt** and/or **wp-remote-themes.txt**
3. Go to **http://yourserver.com/wp-remote-install.php**

The script then performs all the following for you:

1. Downloads the latest version of Wordpress
2. Extracts it onto your server
3. (Optionally) Downloads and extracts your listed Plugins and/or Themes into the new install of Wordpress
4. Deletes itself
5. Redirects you to the Wordpress Installer

The bonus being that all of the downloading of Wordpress, its Plugins and Themes happen between your server and the Wordpress servers - through links far faster than the connection to your home.

## Requirements
In order for this script to work, your webserver must:

- Be empty
- Have **allow\_url\_fopen** enabled

(Pretty simple requirements, really. Most webservers will satisfy these needs.)

## Installation
Pretty straight-forward, really...

1. Connect to your webserver via FTP
2. Upload **wp-remote-install.php**
3. Go to **http://yourserver.com/wp-remote-install.php**
4. Complete the Wordpress Install
5. Have a beer.