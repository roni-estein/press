<?php

namespace RoniEstein\Press\Fields;

use RoniEstein\Press\MarkdownParser;

class Body extends FieldContract
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
            $fieldType => MarkdownParser::parse($fieldValue),
        ];
    }
}