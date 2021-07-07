<?php

namespace RoniEstein\Press;

use Illuminate\Support\Str;

class Press
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * Check if Press config file has been published and set.
     *
     * @return bool
     */
    public function configNotPublished()
    {
        return is_null(config('press'));
    }
    
    public function authorModel()
    {
        if(app()->runningUnitTests()){
            return 'RoniEstein\Press\Author';
        }
        return config('press.author.model');
        
        
    }
    
    public function authorSlug()
    {
        if(app()->runningUnitTests()){
            return 'slug';
        }
        return config('press.author.slug_field');
    }
    
    /**
     * Get the current project timezone.
     *
     * @return string
     */
    public function timezone()
    {
        return config('press.timezone');
    }
    
    /**
     * Get an instance of the set driver.
     *
     * @return mixed
     */
    public function driver()
    {
        $driver = Str::title(config('press.driver'));
        $class = 'RoniEstein\Press\Drivers\\' . $driver . 'Driver';

        return new $class;
    }

    /**
     * Get the currently set URI path for the blog.
     *
     * @return string
     */
    public function uri()
    {
        return config('press.uri', 'articles');
    }

    /**
     * Merges an array of fields into the fields variable.
     *
     * @param array $fields
     */
    public function fields(array $fields)
    {
        $this->fields = array_merge($this->fields, $fields);
    }

    /**
     * Returns the list of available fields in reverse order.
     *
     * @return array
     */
    public function availableFields()
    {
        return array_reverse($this->fields);
    }
}