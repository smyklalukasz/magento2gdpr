# GPDR Magento2 Module

This module provides UE GPDR compliance inside Magento2

See [this article](https://connect.adfab.fr/dev/le-rgpd-gdpr-c-est-maintenant-pour-les-e-commercants) (in french)

## Disclaimer / Licence

See [license](https://github.com/AdFabConnect/magento2gdpr/blob/master/LICENSE)

## Installation / Setup

As a classic magento2 module, just install through composer

```bash
composer require adfab/m2-module-gdpr
php bin/magento cache:clean
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

## Usage

Once this module is installed, a new section appears in administration > Stores > Configuration > Customers > Customer Configuration > Privacy (GDPR). Then you can enable / disable each functionnality. If you use 3rd parties plugins (such as retargeting module), you'll have to customize them, in order to take in account user preferences. 

Don't forget to backup var/keys folder, unless customer data could be complety lost.