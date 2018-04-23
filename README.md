# puppetfacts
puppetfacts is a php framework, wrapped with a bootstrap interface. The framework provides a webservice that can be used to provide fact information for puppet, to automate the installation of a system based on it's MAC address. Because the MAC is used, it can be used to automate a somewhat, baremetal installation, so long as the system has access to call the api and already has puppet installed. 

## Table of contents
* [Setup](#setup)
  * [Software](#software)
  * [Configuration](#configuration)
* [Usage](#usage)
  * [Api](#api)
  * [HTML Interface](#html-interface)
  * [Examples](#examples)
* [Screenshots](#screenshots)
  * [Home](#home)
  * [AddSystem](#addsystem)
  * [AllSystems](#allsystems)
  * [AddUser](#adduser)
  * [AllUsers](#allusers)
  * [RoleCreds](#rolecreds)
  * [SystemApi](#systemapi)
  * [UserApi](#userapi)
  * [AllUsersApi](#allusersapi)
* [Known Issues and Limitations](#known-issues-and-limitations)

## Setup
### Software
Required:
* Apache 2.4.X +
* Php 5.4.16 +

To install, simply clone this repository into the folder of your choice, and run install.sh with bash. This should create the credentials and systems folders for you, and set the appropriate permissions. For simplicity sake, the web application creates files on disk rather than in a database. 
### Configuration
To make the deployment of puppet-facts simple and site configurable, we've included a config.php file that allows you to set default values for fields, as well as define additional facts that you want to add to your site. This file also includes folder paths that you can change based on your requirements. Detailed discussion of this file won't be done here, as the configuration file is marked up for ease of use.
## Usage
### Api
To access the puppet facts, you'll need to access the api located at /getInfo.php. To gain information about a system, you provide it the macAddress of the system. (/getInfo.php?macAddress=00:00:00:00:00:00)
This will provide a json output, that you can parse as a factor on the puppetized system side, with the language of your choice. In the future, we will provide example fact executables that can be used to capture the facts that you've created.

To access all user information, you provide the allusers GET parameter to getInfo.php (/getInfo.php?allusers)

To access a certain users information, you provide the username GET parameter to the getInfo.php (/getInfo.php?username=USERNAME)
### HTML Interface
Upon opening the webservice for the first time, you will need to login with the username and password "admin". Please be sure to change this password or delete this user after creating your own. You'll also be reminded that you must set a default root and recovery user password, to ensure you can access puppetized systems. To do this, use the Generate Credentials page. Once this has been completed, you can begin creating, editing and deleting system and user configurations via the All Systems, Add System, All Users, and Add User menu links at the top of the page.
### Examples
To help with pulling facts for puppet, we've included two example scripts in the example folder. There is one example of pulling system based information as a fact in puppet, and another that allows you to pull all users with puppet. They are both written in python, and use packages that are generally a part of the base python installation (json & urllib2)
## Screenshots
### Home
![Home](/images/Home.png)
### AddSystem
![AddSystem](/images/Add_System.png)
### AllSystems
![AllSystems](/images/All_Systems.png)
### AddUser
![AddUser](/images/Add_User.png)
### AllUsers
![AllUsers](/images/All_Users.png)
### RoleCreds
![RoleCreds](/images/Role_Creds.png)
### SystemApi
![SystemApi](/images/System_Json.png)
### UserApi
![UserApi](/images/User_Json.png)
### AllUsersApi
![AllUsersApi](/images/All_Users_Json.png)
## Known Issues and Limitations
* PHP permissions must be set as such, that you're able to create local files.
* Currently only works under linux. (*Now that python requirements have been removed, this may no longer be the case*)
