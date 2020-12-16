<p align="center">
  <img src="https://www.linkace.org/images/linkace-social.jpg" title="Linkace">
</p>

<p>&nbsp;</p>

<p align="center"><b>Your self-hosted bookmark archive. Free and open source.</b></p>

<p align="center">
  <a href="https://community.linkace.org"><img src="https://img.shields.io/twitter/url?label=Community&logo=discourse&logoColor=44679f&style=social&url=https%3A%2F%2Fcommunity.linkace.org%2F" alt="Get support for LinkAce and chat about the project"></a>
  <a href="https://twitter.com/LinkAceApp"><img src="https://img.shields.io/twitter/url?label=%40LinkAceApp&style=social&url=https%3A%2F%2Ftwitter.com%2FLinkAceApp" alt="Follow LinkAce on Twitter"></a>
  <a href="https://hub.docker.com/r/linkace/linkace"><img src="https://img.shields.io/badge/Docker-linkace%2Flinkace-2596EC.svg" alt="Docker Repository"></a>
  <a href="https://github.com/Kovah/LinkAce/releases"><img src="https://img.shields.io/github/v/release/kovah/linkace?label=Latest%20Release" alt="Latest Release"></a>
  <a href="https://opensource.org/licenses/GPL-3.0"><img src="https://img.shields.io/github/license/kovah/linkace.svg" alt="License"></a>
</p>
<p>&nbsp;</p>


### Contents

* [About LinkAce](#about-linkace)
* [Support Disclaimer](#bulb-support-for-linkace)
* [Setup](#gear-setup)
* [Contribution](#construction-contribution)
  * [Development](#development)


---


### About LinkAce

![Preview Screenshot](https://www.linkace.org/images/preview/linkace_dashboard.png)

LinkAce is a bookmark archive. It wasn't built to manage the bookmarks of your browser but has its very own philosophy. My browser bookmarks contain only websites I regularly use and access. LinkAce, in contrast, is meant to provide a _long-term_ archive of links to websites, media files or anything else which has a valid URL. I store interesting articles, neat web tools or libraries I _may_ use sometime in the future.

#### Feature Highlights

* Save links with automatic title and description generation.
* Automated link monitoring informs you when any links become unavailable or were moved.
* Automated backups of saved sites via the [Internet Archive](https://web.archive.org/).
* Organize bookmarks with the help of lists and tags.
* A full REST API offers access to all features of LinkAce from other apps and services.
* A bookmarklet to quickly save links from any browser.
* Links can be private or public, so friends or internet strangers may see your collection.
* Add notes to links to add thoughts or other relevant information.
* An advanced search including different filters and ordering.
* Import and export of bookmarks from HTML.
* Support for complete database and application backups to Amazon AWS S3.
* A built-in light and dark color scheme.

More features are already planned. Take a look at the [project board](https://github.com/Kovah/LinkAce/projects/1) for more information.

#### Documentation and Community

Any further information about all the available features and how to install the app, can be found on the [LinkAce Website](https://www.linkace.org/). Additionally, you may visit the [community forums](https://community.linkace.org/) to share your ideas, talk with other users or find help for specific problems.


---


### :bulb: Support for LinkAce

I built LinkAce to solve my own problem, and I now offer my solution and code without charging anything. I spent a lot of my free time building this application already, so I won't offer any *free* personal support, customization or installation help. If you need help please visit the [community forum](https://community.linkace.org/) and post your issue there.

You can get personal and dedicated support by **becoming a [Patreon](https://www.patreon.com/Kovah)** or **[Github Sponsor](https://github.com/sponsors/Kovah)**. :star:


---


### :gear: Setup

LinkAce provides multiple ways of installing it on your server. The complete documentation for all installation methods can be found [**in the wiki**](https://www.linkace.org/docs/v1/setup/).

* Setup with Docker (_recommended_)
  * Simple setup with 1 Docker image
  * Advanced setup with multiple Docker images
* Setup without Docker


---


### :construction: Contribution

[![Translations](https://img.shields.io/badge/Translations-Crowdin-2b303d)](https://crowdin.com/project/linkace) [![Code Climate maintainability](https://img.shields.io/codeclimate/maintainability/Kovah/LinkAce) ![Code Climate coverage](https://img.shields.io/codeclimate/coverage/Kovah/LinkAce)](https://codeclimate.com/github/Kovah/LinkAce) [![GitHub Build Status](https://img.shields.io/github/workflow/status/Kovah/LinkAce/Testing/dev?label=Dev%20Build)](https://github.com/Kovah/LinkAce/actions?query=workflow%3ATesting+branch%3Adev)

Please review the [**contribution guidelines**](CONTRIBUTING.md) before starting to work on any features.


### Development

#### Requirements

* [Docker](https://www.docker.com/products/docker-desktop)
* [Node](https://nodejs.org/en/) (12 LTS)

#### 1. Basic Setup

The following steps assume that you are using Docker for development, which I highly encourage. If you use other ways to work with PHP projects you must adapt the commands to your system. Clone the repository to your machine and run the following commands to start the Docker container system:

```bash
cp .env.docker .env
docker-compose up -d --build
```

Now, install all dependencies from inside the PHP container:

```bash
docker exec linkace-php bash -c "composer install"
```

Last step: compile all assets. Node 10 LTS is the minimum version required and recommended to use. You may use either NPM or Yarn for installing the asset dependencies.

```bash
npm install
OR
yarn install

npm run dev
```

#### 2. Working with the Artisan command line

I recommend using the Artisan command line tool in the PHP container only, to make sure that the same environment is  used. To do so, use the following example command:

```bash
docker exec linkace-php bash -c "php artisan migrate"
```

#### 3. Registering a new user

Currently, you can do this by using the command line:

```bash
docker exec -it linkace-php bash -c "php artisan registeruser [user name] [user email]"
```


#### Tests

You can run existing tests with the following command:

```bash
docker exec -it linkace-php composer run tests
```


---


LinkAce is a project by [Kovah](https://kovah.de) | [Contributors](https://github.com/Kovah/LinkAce/graphs/contributors)
