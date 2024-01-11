<?php

namespace App\Http\Requests;

use App\Rules\AddressRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'deliveries.*.from_address' => ['required', 'string', new AddressRule()],
            'deliveries.*.to_address' => ['required', 'string', new AddressRule()],
            'deliveries.*.sender' => 'required|string|min:2',
            'deliveries.*.sender_contact' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'deliveries.*.recipient' => 'required|string|min:2',
            'deliveries.*.recipient_contact' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'deliveries.*.should_delivered' => 'required|date',


            'deliveries.*.products.*.name' => 'required|string|min:2',
            'deliveries.*.products.*.weight' => 'required|integer|min:0.1',
            'deliveries.*.products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
