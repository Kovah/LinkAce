<p align="center">
  <img src="https://i.imgur.com/wtCAJR0.png" title="Linkace">
</p>

<p>&nbsp;</p>

<p align="center"><b>A small, selfhosted bookmark manager.</b></p>
<p align="center"><b>:warning: This application is still in development! :warning:</b></p>

<p align="center">
  <a href="https://hub.docker.com/r/linkace/linkace"><img src="https://img.shields.io/badge/Docker-linkace%2Flinkace-2596EC.svg" alt="Docker Repository"></a>
  <a href="https://github.com/Kovah/LinkAce/releases"><img src="https://img.shields.io/github/release/kovah/linkace.svg" alt="Latest Release"></a>
  <a href="https://travis-ci.org/Kovah/LinkAce"><img src="https://img.shields.io/travis/kovah/linkace.svg" alt="Build Status"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/kovah/linkace.svg" alt="License"></a>
</p>

<p>&nbsp;</p>


## Contents

* [About LinkAce](#about-linkace)
* [Setup](#setup)
  * [Setup with Docker](#setup-with-docker)
  * [Setup without Docker](#setup-without-docker)
* [Support & Bugreports](#support-and-bugreports)
* [Contributions](#Contributions)
  * [Development](#development)

---

## About LinkAce

> @TODO Screenshot(s)

LinkAce is a bookmark manager similar to Shaarli and other tools. I built this tool to have something that fits my
actual needs that other bookmark managers couldn't solve, even if most features are almost the same.

### Features

* Bookmark links with automatic title generation
* Organize bookmarks in categories and tags
* A bookmarklet to quickly save links from any browser
* Private or public links so friends or internet stranges can see your collection
* Add notes to links to add thoughts
* Advanced search for your bookmarks
* Import existing bookmarks from HTML exports (other methods planned)

More features are already planned. Take a look at our [project board](https://github.com/Kovah/LinkAce/projects/1)
for more information. 


---

## Setup

### Setup with Docker

Working with Docker is pretty straight forward. The image available on Docker Hub contains the application code, any
precompiled assets as well as PHP installed. This means you can use any web server you want, any cache driver you want
and any database system you want.

To make things easier, we provide a Docker Compose file (docker-compose.production.yml) in the repository which
contains all needed services, perfectly configured to just run the application right away.

#### 1. Copy all needed files

All files you need are `docker-compose.production.yml`, `.env.docker` and `nginx.conf`. Copy both to the directory you
want to use for the application.

#### 2. Modify the .env.docker file

Rename the `.env.docker` file to `.env`.  
Now open the `.env` file and follow the instructions inside the file. All needed variables you have to configure
are marked accordingly.

#### 3. Modify the nginx.conf file (optional)

This step is optional but may depend on your setup. You probably want to run the app standalon on a server. For this I
highly recommend providing SSL certificates ([Let's Encrypt](https://letsencrypt.org/)) and change the `nginx.conf` as 
well as the `docker-compose.production.yml` file:

* In `nginx.conf`: replace `listen 0.0.0.0:8085` with `listen 0.0.0.0:8085 ssl;`
* In `nginx.conf`: uncomment the lines beginning with `ssl_certificate` and change the certificate file names
* In `docker-compose.production.yml`: replace `"127.0.0.1:80:8085"` with `"127.0.0.1:443:8085"`.
* In `docker-compose.production.yml`: uncommend the `/path/to/ssl/certificates:/bitnami/nginx/conf/bitnami/certs` line 
  and set the correct path to your certificates before the colon.

#### 4. Run the application

After you completed the above steps, run the following command to start up the container setup:

```bash
docker-compose up -d --build
```


### Setup without Docker

The application was developed with being used with Docker in mind. All following steps will try to work around this but
cannot be guaranteed to work in every environment.

#### Requirements

* PHP > 7.2
* MySQL compatible database server
* nginx / Apache web server

#### 1. Get the .zip file

To make things easier I provide a .zip file that contains all precompiled assets and stuff like that so you can use
LinkAce right away. Download the package from the [latest release](https://github.com/Kovah/LinkAce/releases).

Extract all files and place them wherever you need them. This obviously depends on how and where you want to run the
app.

#### 2. Edit the .env file

Make a copy of the `.env.example` file and name it `.env`. Open the file and follow all instructions inside the file. 
All needed variables you have to configure are marked accordingly.

#### 3. Point your web server to /public

For security reasons the application won't run from the base filder where you extracted the files to. Instead, point
your web server to the `/public` directory in your linkace folder.

If you are using Apache, LinkAce already ships with a proper .htaccess file.

If you are using nginx, please add the following lines to your nginx configuration file:

```
add_header X-Frame-Options "SAMEORIGIN";
add_header X-XSS-Protection "1; mode=block";
add_header X-Content-Type-Options "nosniff";

location / {
  try_files $uri $uri/ /index.php?$query_string;
}

location ~* \.(?:css|js|map|scss)$ {
  expires 7d;
  access_log off;
  add_header Cache-Control "public";
  try_files $uri @fallback;
}

error_page 404 /index.php;
```

#### 4. Import a database dump to your Database

To be able to run the app you need to import a database dump into your database.
> @TODO


---

## Support and Bugreports

If you need help or want to report a bug within the application, please open a new [issue](https://github.com/Kovah/LinkAce/issues)
and describe:

* which version you are using,
* what your problem is,
* and what you already done to solve the problem.

**Please notice**: This is a private side-project mainly developed for *myself*. Therefore I cannot guarantee that the
app will work without any problems and I also won't answer support requests within a short period of time.


---

## Contributions

I will gladly welcome any help with the development of the application. If you want to contribute to the project please
open a [ticket](https://github.com/Kovah/LinkAce/issues) first and describe what you want to do or what your idea is.
Maybe there already is an existing ticket for your or a very similar topic.

### Some Contribution Guidelines

* Always use the `dev` branch to work on the application. The dev branch will contain the latest version of the app
while the `master` branch may contains the stable version (which may be outdated in terms of development).
* When opening a pull request, link to your ticket and describe what you did to solve the problem.


## Development

### Requirements

* [Docker](https://www.docker.com/products/docker-desktop)
* [Node](https://nodejs.org/en/) (10 LTS)

### 1. Basic Setup

Simply clone the repository to your machine and run the following command to build the container setup:

```bash
docker-compose up -d --build
```

Now, install all dependencies from inside the PHP container:

```bash
docker exec linkace-php bash -c "composer install"
```

Last step: compile all assets. Node 10 LTS is the minimum version required and recommended to use.
You may use either NPM or Yarn for installing the asset dependencies.

```bash
npm install
OR
yarn install

./node_modules/.bin/grunt build
```

### 2. Working with the Artisan command line

I recommend using the Artisan command line tool in the PHP container only to make sure that the same environment is 
used. To do so, use the following example command:

```bash
docker exec linkace-php bash -c "php artisan migrate"
```

### 3. Registering a new user

Currently you can do this by using the command line:

```bash
docker exec -it linkace-php bash -c "php artisan registeruser [user name] [user email]"
```


### Tests

You may run some existing tests with the following command:

```bash
docker exec -it linkace-php bash -c "./vendor/bin/phpunit"
```

---

LinkAce is a project by [Kovah](https://kovah.de) | [Contributors](https://github.com/Kovah/LinkAce/graphs/contributors)
