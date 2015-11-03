Pixell HUB - Newsletter Voxmail Bundle
======================================

This Symfony2 bundle offers a simple integration for Voxmail and allow users to subscribe and register on your voxmail.

Installation
-------------

add in your composewr.json

```yaml
    ...,
    "pixellhub/newsletter-voxmail-bundle":"dev-master"
},
"repositories": [
   {
     "type": "git",
     "url": "git@github.com:PixellCreativeSolutions/PixellNewsletterVoxmailBundle.git"
   }
],
```

Enable bundle in the kernel adding in your AppKernel.php

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new PixellHub\NewsletterBundle\PixellHubNewsletterBundle(),
    );
}
```

Then, enable the routing adding:

```yaml
# app/config/routing.yml

pixell_newsletter:
    resource: "@PixellHubNewsletterBundle/Controller/"
    type:     annotation
    prefix:   /
```

Now configure your bundle with your voxmail information:

```yaml
# app/config/config.yml

pixell_newsletter:
    host:       "voxmail_account"
    secret:     "token_key"
    api_key:    "token_key"
```


License
-------

This bundle is released under the LGPL license. See the [complete license text](Resources/meta/LICENSE).
