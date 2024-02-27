<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use \MongoDB\BSON;

class ScheduleCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // prepare schedule for reading
        !is_array($value) || array_walk($value, function (&$item, $key) {
            array_walk($item, function (&$property, $key) {
                if (is_a($property, BSON\UTCDateTime::class)) {
                    $property = $property->toDateTime()->format('c');
                }
                if (is_a($property, BSON\ObjectId::class)) {
                    $property = $property->__toString();
                }
            });
        });

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // prepare schedule for saving
        !is_array($value) || array_walk($value, function (&$item, $key) {
            // only modify updated items
            if (array_key_exists('updated', $item)) {
                unset($item['updated']);
                // check each item property
                array_walk($item, function (&$property, $key) {
                    if (is_string($property) && preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/", $property)) {
                        $property = new BSON\UTCDateTime(strtotime($property) * 1000);
                    }
                    if (is_string($property) && preg_match("/^[a-fA-F0-9]{24}$/", $property)) {
                        $property = new BSON\ObjectId($property);
                    }
                    if ($property === '00:00' && in_array($key, ['startTime', 'endTime'])) {
                        $property = null;
                    }
                    if ($key === 'count' && $property < 2) {
                        $property = null;
                    }
                });
                $item = array_filter($item);
            }
        });

        return !empty($value) ? [$key => $value] : [$key => []];
    }
}
