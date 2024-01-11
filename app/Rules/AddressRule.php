<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressRule implements ValidationRule, DataAwareRule
{
    public array $data;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->data['deliveries'] as $delivery) {
            if (!$delivery['from_coordinates'] && !$delivery['to_coordinates']) {
                $fail('You need to choose the right address');
            }
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}


//сын собака
