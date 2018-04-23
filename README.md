# NeatoScheduler
NeatoScheduler is a php framework, wrapped with a bootstrap interface. The framework provides a webservice that can be used to control your ESP8266 controlled neatobotvac. 

## Table of contents
* [Setup](#setup)
  * [Software](#software)
  * [Configuration](#configuration)
* [Usage](#usage)
  * [HTML Interface](#html-interface)
* [Known Issues and Limitations](#known-issues-and-limitations)

## Setup
### Software
Required:
* Apache 2.4.X +
* Php 5.4.16 +

To install, simply clone this repository into the folder of your choice, and run install.sh with bash. This should create the credentials (admin/password) and systems folders for you, and set the appropriate permissions. For simplicity sake, the web application creates files on disk rather than in a database. 
### Configuration
To make the deployment of neatoscheduler simple and site configurable, we've included a config.php file that allows you to set default values for fields, as well as define additional facts that you want to add to your site. This file also includes folder paths that you can change based on your requirements. Detailed discussion of this file won't be done here, as the configuration file is marked up for ease of use.
## Usage
### HTML Interface
Upon opening the webservice for the first time, you will need to login with the username "admin" and password "password". Please be sure to change this password!
## Known Issues and Limitations
* PHP permissions must be set as such, that you're able to create local files.
* Currently only works under linux. (*Now that python requirements have been removed, this may no longer be the case*)
* This interface only works with neato-bot ESP8266 image >= 2.0
* The api folder needs to be accessable via http and not https. This is because the 8266 has no ability to use https for accessing the api. I've included an .htaccess that prevents that redirection.
