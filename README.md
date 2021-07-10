# Press
An elegant markdown-powered blog for the Laravel framework. This package was originally adapted from https://github.com/vicgonvt/press.git. However, since it was only compatible with Laravel 5.x and not 6.x for several months. I'll maintain this newer version.

# Install

Add these lines to your `composer.json` file

```json
{
    "require": {
        "roniestein/press": "dev-master"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/RoniEstein/press.git"
        }
    ]
}
```

And then in the terminal, run the following command.

`composer update`

### Publish the package config

Up next, you need to publish the package's config file that includes some defaults for us. To publish that, run the following command.

`php artisan vendor:publish --tag=press`

You will now find the config file located in `/config/press.php` you can also update the migrations if you need to extend the functionality of press.

### Add your new service PressServiceProvider to config/spp.php in the providers section


### Migrate Database

Now that our configs have been published review the new migrations and see if you need to make any changes to adapt the new migrations to your domain logic. If you don't need to or once you are done, you need to migrate the database to add the necessary tables for Press. In the command line, run the following command.

`php artisan migrate`

### Create directory to hold posts

The last step in the installation, is to create a directory for your markdown files that Press will use to turn into your blog posts. By default, it is just a directory in the root directory of your project called `blogs`. You may change that in the config file we published in the previous step.

`mkdir aticles` 


### Sample Post

To create your first post, here's a sample markdown file to get you started. Copy and paste it into a `.md` file in your articles directory.

```
---
title: My First Blog Post
description: This is my very first blog post with Press
author: roni-estein
---

# Extra Extra Extra!

You are now a blogger!

***make sure to change the author: (slug) to a user or valid model that contains a mathing slug in your bound table. That table will need a valid slug field, and either a name field or an accessor that can retrieve the field "name"*** 
```

## Extending Press

- create pinned class example steps
  - add pinned class to the press service provider
  - update pinned process if required


### Course
This repo holds is the source code for the Laravel Package Development course found here - https://coderstape.com/series/1-laravel-package-development. Be warned, this source code and course is for educational purposes only and may not be production ready. Use at your own risk.



## BASIC LAYOUTS & LIVEWIRE COMPONENTS

### Livewire Blog Index Component


### Livewire Blog Index View


### Livewire Blog Post Component



### Livewire Blog Post View




