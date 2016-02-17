# BEA Facetwp Import Export #

## Description ##

This enhance FacetWP admin. Instead of copy/paste json export/import data in textarea, just export/import json files

## Important to know ##

To get this work, use composer :

```
git clone https://github.com/bea/bea-facetwp-import-export && cd bea-facetwp-import-export
composer dump-autoload
```

Then go to tools > FacetWP Import Export page to import or export your settings

<<<<<<< HEAD
In case you want to include this small plugin to your project running composer you can run the following command :

```
composer require "bea/bea-facetwp-import-export"
=======
In case you want to include this small plugin to your project running composer you can add this line to your composer.json :

```
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/BeAPI/bea-facetwp-import-export"
    }
  ]
```

then run the command :

```
composer require bea/bea-facetwp-import-export dev-master
>>>>>>> 59202603dce7a5f79d2cc0f609f3563c42a356e1
```

## TODO ##

* add toggle all options (export)

## Changelog ##

### 0.1
* 12 Feb 2016
* initial
