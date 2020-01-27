<p align="center">
  <img src="https://i.imgur.com/wtCAJR0.png" title="Linkace">
</p>

<p>&nbsp;</p>

<p align="center"><b>A small, selfhosted bookmark manager.</b></p>
<p align="center"><b>:warning: This application is still in development! :warning:</b></p>

<p align="center">
  <a href="https://hub.docker.com/r/linkace/linkace"><img src="https://img.shields.io/badge/Docker-linkace%2Flinkace-2596EC.svg" alt="Docker Repository"></a>
  <a href="https://github.com/Kovah/LinkAce/releases"><img src="https://img.shields.io/github/v/release/kovah/linkace?include_prereleases&label=Latest%20Release" alt="Latest Release"></a>
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

![Preview Screenshot](https://www.linkace.org/images/preview/linkace_dashboard.png)

LinkAce is a bookmark manager similar to Shaarli and other tools. I built this tool to have something that fits my
actual needs which other bookmark managers couldn't solve, even if most features are almost the same.

### Features

* Bookmark links with automatic title and description generation
* Organize bookmarks in categories and tags
* A bookmarklet to quickly save links from any browser
* Private or public links, so friends or internet strangers can see your collection
* Add notes to links to add thoughts
* Advanced search including different filters and ordering
* Import existing bookmarks from HTML exports (other methods planned)
* Automated link checks to make sure your bookmarks stay available
* Automated “backups” of your bookmarks via the Waybackmachine
* Implemented support for complete database and app backups to Amazon AWS S3
* A built-in light and dark color scheme

More features are already planned. Take a look at the [project board](https://github.com/Kovah/LinkAce/projects/1)
for more information.

### Documentation and Community

Any further information about all the available features and how to install the app, can be found on the 
[LinkAce Website](https://www.linkace.org/). Additionally, you may visit the [community forums](https://community.linkace.org/)
to share your ideas, talk with other users or find help for specific problems.


---

## :gear: Setup

### Setup with Docker

Working with Docker is pretty straight forward. The image available on Docker Hub contains the application code, any
precompiled assets as well as PHP installed. This means you can use any web server you want, any cache driver you want
and any database system you want.

To make things easier, we provide a Docker Compose file (docker-compose.production.yml) in the repository which
contains all needed services, perfectly configured to run the application right away.

### 1. Copy all needed files

All files you need are `docker-compose.production.yml`, `.env.docker` and `nginx.conf`. Copy both to the directory you
want to use for the application.

### 2. Modify the .env.docker file

Make a copy of the `.env.docker` file and name it `.env`. By default, you only must change two variables set in this 
file before starting the setup:

* DB_PASSWORD - Please set a secure password here
* REDIS_PASSWORD - Please set a secure password here

### 3. Modify the nginx.conf file (optional)

This step is optional but may depend on your setup. You probably want to run the app standalon on a server. For this I
highly recommend providing SSL certificates ([Let's Encrypt](https://letsencrypt.org/)) and change the `nginx.conf` as 
well as the `docker-compose.production.yml` file:

* In `nginx.conf`: replace `listen 0.0.0.0:8085` with `listen 0.0.0.0:8085 ssl;`
* In `nginx.conf`: uncomment the lines beginning with `ssl_certificate` and change the certificate file names
* In `docker-compose.production.yml`: replace `"127.0.0.1:80:8085"` with `"127.0.0.1:443:8085"`.
* In `docker-compose.production.yml`: uncommend the `/path/to/ssl/certificates:/bitnami/nginx/conf/bitnami/certs` line 
  and set the correct path to your certificates before the colon.

### 4. Run the application

After you completed the above steps, run the following command to start up the container setup:

```bash
docker-compose up -d
```

### 5. Run the Setup

After you started the Docker containers, you are almost ready to run the setup. Before the setup, we have to generate
a secret key.
Please note that `linkace_php_1` is the name of your PHP container here. It may differ from your name. You will find
the name of your container in the output of the previous command, but will most likely end with `_php_1`.

```bash
docker exec -it linkace_php_1 bash -c "php artisan key:generate"
```

Open the URL which points to your Docker container in your browser now. You have to configure the database and your 
user account in this process.

Please make sure to follow the post-installation tasks to fully enable all features. A guide can be found in the 
[wiki](https://www.linkace.org/docs/v1/setup/post-setup).


### Setup without Docker

The application was developed with being used with Docker in mind. If you don't want to or if you can't use Docker,
you can also run LinkAce as a regular PHP application. Please notice that there won't be any support for custom 
environments, unsupported PHP versions or help with setting up Apache or your nginx proxy.

Please note that you **must have shell access to your server**. A shared hosting may not be suitable for this.

Follow the instructions in the [wiki](https://www.linkace.org/docs/v1/setup/setup-without-docker) to install 
LinkAce without Docker.


---

## :warning: Support and Bugreports

If you need help or want to report a bug within the application, please open a new [issue](https://github.com/Kovah/LinkAce/issues)
and describe:

* which version you are using,
* what your exact problem is,
* and what you already did to solve the problem.

**Please notice**: This is **a private side-project* mainly developed for *myself*. Therefore I cannot guarantee that 
the app will work without any problems, and I may not answer support requests within a short period of time. I also
do not offer any customization or installation help.

If you need an app with extensive support please consider using another solution.


---

## :construction: Contributions

I will gladly welcome any help with the development of the application. If you want to contribute to the project please
open a [ticket](https://github.com/Kovah/LinkAce/issues) first and describe what you want to do or what your idea is.
Maybe there already is an existing ticket for your or a very similar topic.

### Some Contribution Guidelines

* Always use the `dev` branch to work on the application. The dev branch will contain the latest version of the app
while the `master` branch may contains the stable version (which may be outdated in terms of development).
* Consider using a separate branch if you are working on a larger feature.
* When opening a pull request, link to your ticket and describe what you did to solve the problem.


## Development

#### Stack

![Docker](https://img.shields.io/badge/Containers-Docker-0087C9.svg)
![PHP 7.4](https://img.shields.io/badge/PHP-7.4-8892BF.svg)
![MariaDB 10.4](https://img.shields.io/badge/MariaDB-10.4-009ca5.svg)
![Redis 5](https://img.shields.io/badge/Redis-5.0-f01e1a.svg)
![Laravel 6](https://img.shields.io/badge/Laravel-6-FF0000.svg)
![Node 12 LTS](https://img.shields.io/badge/Node-12_LTS-55a15c.svg)
![Bootstrap 4](https://img.shields.io/badge/Bootstrap-4-5b3c80.svg)

#### Current Status

[![Github Build Status](https://img.shields.io/github/workflow/status/kovah/linkace/PHP%20Lint%20&%20Test)](https://github.com/Kovah/LinkAce/actions)
[![Docker Build Status](https://img.shields.io/docker/cloud/build/linkace/linkace)](https://hub.docker.com/r/linkace/linkace)
[![Open Pull Requests](https://img.shields.io/github/issues-pr/kovah/linkace)](https://github.com/Kovah/LinkAce/pulls)


### Minimum Requirements

* [Docker](https://www.docker.com/products/docker-desktop)
* [Node](https://nodejs.org/en/) (12 LTS)

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

npm run dev
```

### 2. Working with the Artisan command line

I recommend using the Artisan command line tool in the PHP container only, to make sure that the same environment is 
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

You can run existing tests with the following command:

```bash
docker exec -it linkace-php bash -c "./vendor/bin/phpunit"
```

---

LinkAce is a project by [Kovah](https://kovah.de) | [Contributors](https://github.com/Kovah/LinkAce/graphs/contributors)
