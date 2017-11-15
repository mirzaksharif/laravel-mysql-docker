## Laravel + MySQL + Docker Container
* Author/Developer: **Khurram Sharif** *

------------
This repo contains dockerized implementation of Laravel, PHP, MySQL and NginX.

Please follow below description to run this docker:-

1. First you should have **Docker** & **Docker-Compose** installed on your system. I recommend Ubuntu because Docker is native for Linux, however, you are free to use Docker for Windows or MacOS.
2. Clone or download this repo.
3. In **Terminal** run commad ***(sudo) docker-compose build***
*This will fetch required images and build according to required modules. In this case we have to enable PHP PDO MySQL for database queries.*
4. Once build is successful, run this command: ***(sudo) docker-compose up***

### Setting-up Database: 

Now your container is running and you have to Import Database SQL. You can open any MySQL Client and connect it using:-
```javascript
Server: 127.0.0.1
Login: root
Pass: laravel
Database: laravel
```
* If you don't have any MySQL client installed, then you can get one here for free: 
https://dev.mysql.com/downloads/workbench/ *

Download SQL file from below and import in into "Laravel" database.
* SQL file: https://s3.eu-west-2.amazonaws.com/echomany-test-task/codes.sql *

### You are done! 
Just open your browser and load:
```javascript
http://localhost/v1/codes?type=pizza
```



