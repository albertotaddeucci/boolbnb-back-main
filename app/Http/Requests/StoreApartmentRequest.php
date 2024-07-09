<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:100',
            'description' => 'max:8000',
            'image' => 'file|max:2048|nullable|mimes:jpg,png',
            'n_rooms' => 'required|numeric|between:1,100',
            'n_beds' => 'required|numeric|between:1,100',
            'n_bathrooms' => 'required|numeric|between:1,100',
            'squared_meters' => 'required|numeric|between:10,200',
            'address' => 'required|max:100',
            'services' => 'required|exists:services,id|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Inserisci nome Albergo',
            'title.max' => "Puoi usare al massimo :max caratteri",

            'description.max' => "Puoi usare al massimo :max caratteri",

            'image.mimes' => "Inserisci un immagine",
            'image.max' => "Peso limite 2048 KB",

            'n_rooms.required' => 'Inserisci almeno una stanza',
            'n_rooms.between'=> 'Non possono essere inseriti valori inferiori a 1 e maggiori di 100',
            'n_rooms.numeric'=> 'Il valore da inserire deve essere numerico',
            
            'n_beds.required' => 'Inserisci almeno un posto letto',
            'n_beds.between'=> 'Non possono essere inseriti valori inferiori a 1 e maggiori di 100',
            'n_beds.numeric'=> 'Il valore da inserire deve essere numerico',
            
            'n_bathrooms.required' => 'Inserisci almeno un bagno',
            'n_bathrooms.between'=> 'Non possono essere inseriti valori inferiori a 1 e maggiori di 100',
            'n_bathrooms.numeric'=> 'Il valore da inserire deve essere numerico',
            
            'squared_meters.required' => 'Inserisci area Albergo',
            'squared_meters.between'=> 'Non possono essere inseriti valori inferiori a 10 e maggiori di 200',
            'squared_meters.numeric'=> 'Il valore da inserire deve essere numerico',
            
            'address.required' => 'Inserisci un indirizzo valido',
            'address.max' => 'Non credo esistano vie lunghe :max caratteri!!',
            
            'services.required' => "Inserisci almeno un servizio",
        ];
    }
}