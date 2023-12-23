This contains the source files for the "*Omnipedia - Logger*" Drupal module,
which provides logging enhancements for [Omnipedia](https://omnipedia.app/).

⚠️ ***[Why open source? / Spoiler warning](https://omnipedia.app/open-source)***

----

# Description

This module is fairly bare-bones at the moment. It exists primarily to provide
the requirement for [`drupal/monolog`](https://www.drupal.org/project/monolog)
and a custom Monolog mail handler
([`Logger\Handler\DrupalMailHandler`](src/Logger/Handler/DrupalMailHandler.php))
to send emails using Drupal core's mail manager while implementing true
[dependency
injection](https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/services-and-dependency-injection-in-drupal-8).

----

# Requirements

* [Drupal 9.5 or 10](https://www.drupal.org/download) ([Drupal 8 is end-of-life](https://www.drupal.org/psa-2021-11-30))

* PHP 8.1

* [Composer](https://getcomposer.org/)

----

# Installation

## Composer

### Set up

Ensure that you have your Drupal installation set up with the correct Composer
installer types such as those provided by [the `drupal/recommended-project`
template](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates#s-drupalrecommended-project).
If you're starting from scratch, simply requiring that template and following
[the Drupal.org Composer
documentation](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates)
should get you up and running.

### Repository

In your root `composer.json`, add the following to the `"repositories"` section:

```json
"drupal/omnipedia_logger": {
  "type": "vcs",
  "url": "https://github.com/neurocracy/drupal-omnipedia-logger.git"
}
```

### Installing

Once you've completed all of the above, run `composer require
"drupal/omnipedia_logger:^4.0@dev"` in the root of your project to have
Composer install this and its required dependencies for you.

----

# Major breaking changes

The following major version bumps indicate breaking changes:

* 4.x - Requires Drupal 9.5 or [Drupal 10](https://www.drupal.org/project/drupal/releases/10.0.0).
