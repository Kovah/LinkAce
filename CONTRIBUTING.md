# :construction: Contribution

If you want to contribute to the project please open a [ticket](https://github.com/Kovah/LinkAce/issues) first and 
describe what you want to do or what your idea is. Maybe there already is an existing ticket for your or a very similar 
topic.

I may decline contributions for features that may not fit into the application, so please make sure to talk to me before
starting to code.


## Contribution Guidelines

* Always use the `dev` branch to work on the application. The dev branch will contain the latest version of the app
  while the `main` branch contains the stable version (which may be outdated in terms of development).
* Consider using a separate branch if you are working on a larger feature.
* Reference the issue number in your commits please.
* When opening a pull request, link to your ticket and describe what you did to solve the problem.


---


## Development

### Minimum Requirements

* [Docker](https://www.docker.com/products/docker-desktop) _or_ PHP 7.4
* [Node](https://nodejs.org/en/) (16 LTS)

### 1. Basic Setup

The following steps assume that you are using Docker for development, which I highly encourage. If you use other ways to work with PHP projects you must adapt the commands to your system. Clone the repository to your machine and run the following commands to start the Docker container system:

```bash
cp .env.docker .env
docker-compose up -d --build
```

Now, install all dependencies from inside the PHP container:

```bash
docker exec -it linkace-php composer install

docker exec -it linkace-php php artisan key:generate
```

Last step: compile all assets. Node 16 LTS is the minimum version required and recommended to use. You may use either NPM or Yarn for installing the asset dependencies.

```bash
npm install

npm run dev
```

#### 2. Working with the Artisan command line

I recommend using the Artisan command line tool in the PHP container only, to make sure that the same environment is  used. To do so, use the following example command:

```bash
docker exec -it linkace-php php artisan migrate
```

#### 3. Registering a new user

Currently, you can do this by using the command line:

```bash
docker exec -it linkace-php php artisan registeruser [user name] [user email]
```


## Tests

You can run existing tests with the following command:

```bash
docker exec -it linkace-php composer run lint
docker exec -it linkace-php composer run test
```


## LinkAce Base Docker image

The Base image for LinkAce contains several packages and PHP extensions needed by LinkAce. It shortens the build time of the release images. This step is not needed by any developer working on LinkAce and is just a documentation for maintainers.

```bash
docker buildx build --push --platform "linux/amd64,linux/arm64,linux/arm/v7" -t linkace/base-image:php-8.2-alpine -f resources/docker/dockerfiles/release-base.Dockerfile .
```
