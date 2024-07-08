<?php

namespace App\Http\Requests;

class DepositRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'amount' => 'its not possible to deposite negative values',
        ];
    }
}
