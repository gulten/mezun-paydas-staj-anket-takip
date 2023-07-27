<?php

namespace App\Http\ResponseConverters;

use MarcinOrlowski\ResponseBuilder\Contracts\ConverterContract;
use MarcinOrlowski\ResponseBuilder\Validator;

class JsonResourceToArrayConverter implements ConverterContract
{
    /**
     * Returns array representation of the object.
     *
     * @param object $obj    Object to be converted
     * @param array  $config Converter config array to be used for this object (based on exact class
     *                       name match or inheritance).
     *
     * @return array
     */
    public function convert($obj, array /** @scrutinizer ignore-unused */ $config): array
    {
        Validator::assertIsObject('obj', $obj);

        return $obj->toArray(request());
    }
}
