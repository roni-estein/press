<?php

namespace roniestein\Press\Fields;

use Carbon\Carbon;

class PublishedAt extends FieldContract
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
        return [
            $fieldType => (strlen($fieldValue)>1 ? Carbon::parse($fieldValue) : null),
        ];
    }
}