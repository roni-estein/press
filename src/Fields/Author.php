<?php

namespace roniestein\Press\Fields;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use roniestein\Press\Facades\Press;

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
        
        try {
            $id = Press::authorModel()::where('slug', $fieldValue)->firstOrFail()->id;
            
        } catch (ModelNotFoundException $e) {
            abort(404,
                "\n\n" . 'No Author found in the table "' .
                Press::authorModel()::make()->getTable() .
                '" with the slug: ' . $fieldValue . "\nCheck for spelling errors\n");
        }
        
        return [
            Press::authorKey() . '_id' => $id,
            Press::authorKey() . '_type' => Press::authorModel(),
        ];
        
        
    }
}