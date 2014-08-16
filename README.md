Boxmeup
=======

Boxmeup is a web and mobile application to help users keep track of what they have in their containers and how to find items in specific containers.

[![Build Status](https://img.shields.io/travis/boxmeup/Boxmeup/3.0.svg?style=flat)](https://travis-ci.org/boxmeup/Boxmeup)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/boxmeup/boxmeup.svg?style=flat)](https://scrutinizer-ci.com/g/boxmeup/Boxmeup/?branch=3.0)
[![Huboard](https://img.shields.io/badge/Hu-Board-7965cc.svg?style=flat)](https://huboard.com/boxmeup/Boxmeup)

# Requirements

* PHP 5.4+
* MySQL 5.5
* Sphinxsearch 2.0+
* Composer
* NodeJS (npm)
* Bower
* Compass
* Grunt CLI

# Quick start

```bash
npm install && bower install && composer install
cp config/environment.dist.php config/environment.php
```

# Build CSS/JS

```bash
grunt build
```

# Debug

To get debug mode:

* For apache: set `DEBUG 1` in `.htaccess`
* For all others, set the environment variable `DEBUG=1` for runtime.

# Contributing

See the [CONTRIBUTING.md](https://github.com/boxmeup/Boxmeup/blob/3.0/CONTRIBUTING.md) document.

# License (GPLv3)

This software is licensed under GPLv3. See the [LICENSE.md](https://github.com/boxmeup/Boxmeup/blob/3.0/LICENSE.md) for complete GPLv3 license attached to this software.
