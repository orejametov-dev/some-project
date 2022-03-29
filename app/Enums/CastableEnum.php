<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use MyCLabs\Enum\Enum;

abstract class CastableEnum extends Enum implements Castable
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class implements CastsAttributes {
            /**
             * @param Model $model
             * @param string $key
             * @param mixed $value
             * @param array $attributes
             */
            public function get($model, string $key, $value, array $attributes): ?Enum
            {
                if ($value === null) {
                    return null;
                }
                $enum = $model->getCasts()[$key];

                return $enum::from($value);
            }

            /**
             * @param Model $model
             * @param  string  $key
             * @param  mixed  $value
             * @param  array  $attributes
             * @return mixed
             */
            public function set($model, string $key, $value, array $attributes)
            {
                $enum = $model->getCasts()[$key];

                if ($value instanceof $enum) {
                    return $value->getValue();
                }

                $enum::assertValidValue($value);

                return $value;
            }
        };
    }
}
