VideoGallery bundle
=====================

This bundle can be used for retrieving playlists from various video providers.
Currently only YouTube is supported, although it is easy to add implementations for other vendors.

## Installation

### Get the code

**Using the vendors script**

Add the following lines in your `deps` file:

```
[ImiBorbasVideoGalleryBundle]
    git=git://github.com/imiborbas/ImiBorbasVideoGalleryBundle.git
    target=bundles/ImiBorbas/VideoGalleryBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, the run the following:

``` bash
$ git submodule add git://github.com/imiborbas/ImiBorbasVideoGalleryBundle.git vendor/bundles/ImiBorbas/VideoGalleryBundle
$ git submodule update --init
```

### Register the bundle in the application kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new ImiBorbas\VideoGalleryBundle\ImiBorbasVideoGalleryBundle(),
    );
}
```

### Import routing

``` yaml
# app/config/routing.yml
videogallery_admin:
    resource: "@ImiBorbasVideoGalleryBundle/Resources/config/routing/admin.yml"
    prefix: /admin

videogallery:
    resource: "@ImiBorbasVideoGalleryBundle/Resources/config/routing/videogallery.yml"
    prefix: /videogallery
```

### Configure the bundle

``` yaml
# app/config/config.yml
imi_borbas_video_gallery:
    thumbnail_directory: thumbnails
```
