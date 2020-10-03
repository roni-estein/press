<?php

namespace RoniEstein\Press\Fields;

class HeaderImage extends FieldContract
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
        $imageDetails = [];
        $data = explode(',', $fieldValue);
        if (isset($data[0]) && strlen(trim($data[0])) > 0) {
            $imageDetails['header-image'] = trim($data[0]);
            
            if (isset($data[1]) && strlen(trim($data[1])) > 0) {
                $imageDetails['header-image-alt'] = trim($data[1]);
                
                if (isset($data[2]) && strlen(trim($data[2])) > 0) {
                    $imageDetails['header-image-photographer'] = trim($data[2]);
                    
                    if (isset($data[3]) && strlen(trim($data[3])) > 0) {
                        $imageDetails['header-image-photographer-profile'] = trim($data[4]);
                        
                        
                    }
                }
                
            }
            
        }
        
        return [
            'extra' => json_encode($imageDetails),
        ];
    }
}