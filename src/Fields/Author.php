<?php

namespace RoniEstein\Press\Fields;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use RoniEstein\Press\Facades\Press;
use function Tests\authorFromException;

class Author extends FieldContract
{
    /**
     * Process the field and make any modifications.
     *
     * @param $fieldType
     * @param $fieldValue
     * @param $data
     *
     * @return array
     */
    public static function process($fieldType, $fieldValue, $data)
    {
        
        $slugs = Str::of($fieldValue)->explode(', ');
        
        
        try {
            $authors = $slugs->map(function ($slug) {
                return [
                    'press_author_id' => Press::authorModel()::where('slug', $slug)->firstOrFail()->id,
                    'press_author_type' => Press::authorModel(),
                ];
            });
            
            return ['authors' => $authors];
            
        } catch (ModelNotFoundException $e) {
            
            abort(422,
                "\n\n" . 'No Author found in the table "' .
                Press::authorModel()::make()->getTable() .
                '" with the slug: ' . authorFromException($e) . "\nCheck for spelling errors\n");
        }
        
        
    }
}