<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use \MongoDB\BSON;

class PetSettings implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // convert MongoDB datetime objects to ISO8601 string
        !is_array($value) || array_walk_recursive($value, function (&$value, $key) {
            if (is_a($value, BSON\UTCDateTime::class)) {
                $value = $value->toDateTime()->format('c');
            }
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
        // turn last fields into MongoDB datetime object
        !is_array($value) || array_walk_recursive($value, function (&$value, $key) {
            if (preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/", $value)) {
                $value = new BSON\UTCDateTime(strtotime($value) * 1000);
            }
        });

        // remove empty settings
        !is_array($value) || $value = $this->filter_settings($value);

        return !empty($value) ? [$key => $value] : null;
    }

    protected function filter_settings($settings): mixed
    {
        foreach ($settings as &$setting) {
            if (is_array($setting)) {
                $setting = $this->filter_settings($setting);
            }
        }
        return array_filter($settings);
    }
}
