<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'city' => [
                'required'
            ],
            'district' => [
                'required'
            ],
            'street' => [
                'required'
            ],
            'street2' => [
                'required'
            ],
            'apartment' => [
                'required'
            ],
            'apartment_no' => [
                'required'
            ],
            'apartment_floor' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'address' => [
                'required'
            ],
            'fullname' => [
                'required'
            ],
            'source' => [
                'required'
            ],
        ];
    }

    public function attributes()
    {
        //Todo(YasinKose): Vakti olan attribute tanımlamalarını yapabilir.
        return [
            "city" => "Şehir",
            "district" => "İlçe",
            "street" => "Cadde/Sokak",
            "source" => "Kaynak",
        ];
    }

    public function messages()
    {
        return [
            "city.required" => ":attribute alanı boş bırakılamaz",
            "district.required" => ":attribute alanı boş bırakılamaz",
            "street.required" => ":attribute alanı boş bırakılamaz",
            "source.required" => ":attribute alanı boş bırakılamaz",
        ];
    }

}
