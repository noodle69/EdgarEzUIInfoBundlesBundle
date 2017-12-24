# EdgarEzUIInfoBundlesBundle

## Installation

### Get the bundle using composer

Add EdgarEzUIInfoBundlesBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-uiinfobundles-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\CronBundle\EdgarCronBundle(),
        new Edgar\EzUICronBundle\EdgarEzUICronBundle(),
        new Edgar\EzUIInfoBundlesBundle\EdgarEzUIInfoBundlesBundle(),
        // ...
    );
}
```

## Add doctrine ORM support

in your ezplatform.yml, add

```yaml
doctrine:
    orm:
        auto_mapping: true
```

## Update your SQL schema

```
php bin/console doctrine:schema:update --force
```
