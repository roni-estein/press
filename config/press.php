<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Source Driver
    |--------------------------------------------------------------------------
    |
    | Press allows you to select a driver that will be used for storing your
    | blog posts. By default, the file driver is used, however, additional
    | drivers are available, or write your own custom driver to suite.
    |
    | Supported: "file"
    |
    */

    'driver' => 'file',
    
    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | Here you can specify the base timezone that your blog uses for reference
    | time, it will allow you to evaluate when a file is published and visible
    | to the public.
    |
    */
    
    'timezone' => 'America/Winnipeg',

    /*
    |--------------------------------------------------------------------------
    | File Driver Options
    |--------------------------------------------------------------------------
    |
    | Here you can specify any configuration options that should be used with
    | the file driver. The path option is a path to the directory with all
    | of the markdown files that will be processed inside the command.
    |
    */

    'file' => [
        'path' => 'articles',
    ],

    /*
    |--------------------------------------------------------------------------
    | URI Address Path
    |--------------------------------------------------------------------------
    |
    | Use this path value to determine on what URI we are going to serve the
    | blog. For example, if you wanted to serve it at a different prefix
    | instead of www.example.com/articles, like www.example.com/my-blog,
    | simply change the value url uri to '/my-blog'.
    |
    */

    'uri' => 'articles',

    /*
    |--------------------------------------------------------------------------
    | Author Model
    |--------------------------------------------------------------------------
    |
    | This is the model that becomes the related author of each article.
    | the default is the default User model shipped with laravel but you
    | can map the relationship to any model that has
    | - a name
    | - a unique slug field
    | - and a primary key
    | - in most cases you may want to shift this to some model likely Author as
    |   you may want al users to be able to post articles
    */
    'author' => [
        'model' => 'App\Models\User',
        'slug_field' => 'slug', //not implemented yet
    ],
];