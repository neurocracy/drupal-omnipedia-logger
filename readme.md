This contains the source files for the "*Omnipedia - Logger*" Drupal module,
which provides logging enhancements for [Omnipedia](https://omnipedia.app/).

⚠️⚠️⚠️ ***Here be potential spoilers. Proceed at your own risk.*** ⚠️⚠️⚠️

----

# Why open source?

We're dismayed by how much knowledge and technology is kept under lock and key
in the videogame industry, with years of work often never seeing the light of
day when projects are cancelled. We've gotten to where we are by building upon
the work of countless others, and we want to keep that going. We hope that some
part of this codebase is useful or will inspire someone out there.

----

# Requirements

* [Drupal 9](https://www.drupal.org/download) ([Drupal 8 is end-of-life](https://www.drupal.org/psa-2021-11-30))

* PHP 8

* [Composer](https://getcomposer.org/)

----

# Installation

Ensure that you have your Drupal installation set up with the correct Composer
installer types such as those provided by [the ```drupal\recommended-project```
template](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates#s-drupalrecommended-project).
If you're starting from scratch, simply requiring that template and following
[the Drupal.org Composer
documentation](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates)
should get you up and running.

Then, in your root ```composer.json```, add the following to the
```"repositories"``` section:

```
{
  "type": "vcs",
  "url": "https://github.com/neurocracy/drupal-omnipedia-logger.git"
}
```

Then, in your project's root, run ```composer require
"drupal/omnipedia_logger:3.x-dev@dev"``` to have Composer install the module
and its required dependencies for you.

## Patches

This currently provides a patch for [a `drupal/monolog`
issue](https://www.drupal.org/project/monolog/issues/3284825) that fixes a fatal
error due to a `monolog/monolog` upstream change which will be removed once that
makes it into a stable release.
[cweagans/composer-patches](https://github.com/cweagans/composer-patches) is
installed as a required dependency by Composer to automate this.

----

# Description

This module is fairly bare-bones at the moment. It exists primarily to provide
the requirement for [`drupal/monolog`](https://www.drupal.org/project/monolog)
and a custom Monolog mail handler
([`Logger\Handler\DrupalMailHandler`](src/Logger/Handler/DrupalMailHandler.php))
to send emails using Drupal core's mail manager while implementing true
[dependency
injection](https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/services-and-dependency-injection-in-drupal-8).
