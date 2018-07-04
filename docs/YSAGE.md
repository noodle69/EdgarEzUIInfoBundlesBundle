# EdgarEzUIInfoBundlesBundle

Two solutions to populate community bundles datas.

## Crons

This bundle comes with EdgarEzUICronBundle.

By defaut, EdgarEzUIInfoBundlesBundle define a Cron scheduled to be launched every hours.

So follow EdgarEzUICronBundle USAGE.md documentation to define which cronjob to register in your crontab

## Manually

You can populate community bundles datas by executing InfoBundles Command (Cron) manually

Execute this command :
 
```bash
sudo -u [www-data|apache|...] php bin/console edgarez:bundles:update ezplatform-bundle --env=...
```
