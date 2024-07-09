<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'icon' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Inserisci un nome!!',
            'name.max' => "Puoi usare al massimo :max caratteri",
            'icon.required' => 'Inserisci un icona per il servizio!!',
        ];
    }
}
