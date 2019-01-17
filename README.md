<p align="center">
  <img src="https://i.imgur.com/wtCAJR0.png" title="Linkace">
</p>

<p>&nbsp;</p>

<p align="center"><b>A small, selfhosted bookmark manager.</b></p>
<p align="center"><b>:warning: This application is still in development! :warning:</b></p>

<p align="center">
  <a href="https://hub.docker.com/r/linkace/linkace">
    <img src="https://img.shields.io/badge/Docker-linkace%2Flinkace-2596EC.svg" alt="Docker Repository">
  </a>
  <a href="https://github.com/Kovah/LinkAce/releases">
    <img src="https://img.shields.io/github/release/kovah/linkace.svg" alt="Latest Release">
  </a>
  <a href="https://opensource.org/licenses/MIT">
    <img src="https://img.shields.io/github/license/kovah/linkace.svg" alt="License">
  </a>
</p>

<p>&nbsp;</p>


## Development

### Setup

I recommend using Docker for development. You need to have [Docker](https://www.docker.com/products/docker-desktop) 
installed on your machine. Then, simply run the following command to build your containers.

```
$ docker-compose up -d --build
```

Now, install all dependencies from inside the PHP container:

```
$ docker exec linkace-php bash -c "composer install"
```

Last step: compile all assets. You need [Node](https://nodejs.org/en/) with either NPM or [Yarn](https://yarnpkg.com) 
installed.  
Node 10 LTS is the minimum version required and recommended to use.

```
$ npm install
OR
$ yarn install

$ ./node_modules/.bin/grunt build
```

### Working with the Artisan command line

I recommend using the Artisan command line tool in the PHP container only to make sure that the same environment is 
used. To do so, use the following example command:

```
$ docker exec linkace-php bash -c "php artisan migrate"
```

### Registering a new user

Currently you can do this by using the command line:

```
$ docker exec -it linkace-php bash -c "php artisan registeruser [user name] [user email]"
```

---

LinkAce is a project by [Kovah](https://kovah.de) | [Contributors](https://github.com/Kovah/LinkAce/graphs/contributors)
