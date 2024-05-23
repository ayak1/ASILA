<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddApartmentRequest extends FormRequest
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
            'apartment_type_id' => 'required|exists:apartment_types,id',
            'city_id' => 'required|exists:cities,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'is_for_sell' => 'required|boolean',
            'is_for_rent' => 'required|boolean',
            'space' => 'required|integer',
            'has_parking' => 'required|boolean',
            'room_number' => 'required|integer',
            'baths_number' => 'required|integer',
            'floor' => 'required|integer',
            'is_rented' => 'required|boolean',
            'is_sold' => 'required|boolean',
            'available_for_rent_at' => 'nullable|date',
            'rent_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'in_installments' => 'required|boolean',
            'sell_per_month' => 'nullable|numeric',
            'rent_per_month' => 'nullable|numeric',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            'translations.*.address' => 'required|string',
            'translations.*.description' => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'apartment_type_id.required' => [
                'ar' => 'حقل نوع الشقة مطلوب.',
                'tr' => 'Daire türü alanı gereklidir.',
                'en' => 'The apartment type field is required.',
            ],
            'apartment_type_id.exists' => [
                'ar' => 'نوع الشقة المحدد غير صالح.',
                'tr' => 'Seçilen daire türü geçersiz.',
                'en' => 'The selected apartment type is invalid.',
            ],
            'city_id.required' => [
                'ar' => 'حقل المدينة مطلوب.',
                'tr' => 'Şehir alanı gereklidir.',
                'en' => 'The city field is required.',
            ],
            'city_id.exists' => [
                'ar' => 'المدينة المحددة غير صالحة.',
                'tr' => 'Seçilen şehir geçersiz.',
                'en' => 'The selected city is invalid.',
            ],
            'longitude.required' => [
                'ar' => 'حقل خط الطول مطلوب.',
                'tr' => 'Boylam alanı gereklidir.',
                'en' => 'The longitude field is required.',
            ],
            'longitude.numeric' => [
                'ar' => 'يجب أن يكون خط الطول رقمًا.',
                'tr' => 'Boylam sayı olmalıdır.',
                'en' => 'The longitude must be a number.',
            ],
            'latitude.required' => [
                'ar' => 'حقل دائرة العرض مطلوب.',
                'tr' => 'Enlem alanı gereklidir.',
                'en' => 'The latitude field is required.',
            ],
            'latitude.numeric' => [
                'ar' => 'يجب أن تكون دائرة العرض رقمًا.',
                'tr' => 'Enlem sayı olmalıdır.',
                'en' => 'The latitude must be a number.',
            ],
            'is_for_sell.required' => [
                'ar' => 'حقل البيع مطلوب.',
                'tr' => 'Satış alanı gereklidir.',
                'en' => 'The sell field is required.',
            ],
            'is_for_rent.required' => [
                'ar' => 'حقل الإيجار مطلوب.',
                'tr' => 'Kiralama alanı gereklidir.',
                'en' => 'The rent field is required.',
            ],
            'space.required' => [
                'ar' => 'حقل المساحة مطلوب.',
                'tr' => 'Boşluk alanı gereklidir.',
                'en' => 'The space field is required.',
            ],
            'space.integer' => [
                'ar' => 'يجب أن تكون المساحة رقمًا صحيحًا.',
                'tr' => 'Alan tamsayı olmalıdır.',
                'en' => 'The space must be an integer.',
            ],
            'has_parking.required' => [
                'ar' => 'حقل التوقيف مطلوب.',
                'tr' => 'Park alanı gereklidir.',
                'en' => 'The parking field is required.',
            ],
            'room_number.required' => [
                'ar' => 'حقل عدد الغرف مطلوب.',
                'tr' => 'Oda sayısı alanı gereklidir.',
                'en' => 'The room number field is required.',
            ],
            'room_number.integer' => [
                'ar' => 'يجب أن يكون عدد الغرف رقمًا صحيحًا.',
                'tr' => 'Oda sayısı bir tamsayı olmalıdır.',
                'en' => 'The room number must be an integer.',
            ],
            'baths_number.required' => [
                'ar' => 'حقل عدد الحمامات مطلوب.',
                'tr' => 'Banyo sayısı alanı gereklidir.',
                'en' => 'The baths number field is required.',
            ],
            'baths_number.integer' => [
                'ar' => 'يجب أن يكون عدد الحمامات رقمًا صحيحًا.',
                'tr' => 'Banyo sayısı bir tamsayı olmalıdır.',
                'en' => 'The baths number must be an integer.',
            ],
            'floor.required' => [
                'ar' => 'حقل الطابق مطلوب.',
                'tr' => 'Kat alanı gereklidir.',
                'en' => 'The floor field is required.',
            ],
            'floor.integer' => [
                'ar' => 'يجب أن يكون الطابق رقمًا صحيحًا.',
                'tr' => 'Kat bir tamsayı olmalıdır.',
                'en' => 'The floor must be an integer.',
            ],
            'is_rented.required' => [
                'ar' => 'حقل الإيجار مطلوب.',
                'tr' => 'Kiralanmış alanı gereklidir.',
                'en' => 'The rented field is required.',
            ],
            'is_sold.required' => [
                'ar' => 'حقل البيع مطلوب.',
                'tr' => 'Satılmış alanı gereklidir.',
                'en' => 'The sold field is required.',
            ],
            'available_for_rent_at.date' => [
                'ar' => 'يجب أن يكون متاحًا للإيجار تاريخًا صحيحًا.',
                'tr' => 'Kiralık tarih bir tarih olmalıdır.',
                'en' => 'The available for rent at must be a valid date.',
            ],
            'rent_price.numeric' => [
                'ar' => 'يجب أن يكون سعر الإيجار رقمًا.',
                'tr' => 'Kira fiyatı bir sayı olmalıdır.',
                'en' => 'The rent price must be a number.',
            ],
            'sell_price.numeric' => [
                'ar' => 'يجب أن يكون سعر البيع رقمًا.',
                'tr' => 'Satış fiyatı bir sayı olmalıdır.',
                'en' => 'The sell price must be a number.',
            ],
            'in_installments.required' => [
                'ar' => 'حقل التقسيط مطلوب.',
                'tr' => 'Taksit alanı gereklidir.',
                'en' => 'The installments field is required.',
            ],
            'sell_per_month.numeric' => [
                'ar' => 'يجب أن يكون البيع في الشهر رقمًا.',
                'tr' => 'Aylık satış bir sayı olmalıdır.',
                'en' => 'The sell per month must be a number.',
            ],
            'rent_per_month.numeric' => [
                'ar' => 'يجب أن يكون الإيجار في الشهر رقمًا.',
                'tr' => 'Aylık kira bir sayı olmalıdır.',
                'en' => 'The rent per month must be a number.',
            ],
            'translations.required' => [
                'ar' => 'يجب تقديم الترجمات لجميع اللغات.',
                'tr' => 'Tüm diller için çeviriler sağlanmalıdır.',
                'en' => 'Translations must be provided for all languages.',
            ],
            'translations.*.locale.required' => [
                'ar' => 'حقل اللغة مطلوب.',
                'tr' => 'Dil alanı gereklidir.',
                'en' => 'The language field is required.',
            ],
            'translations.*.locale.string' => [
                'ar' => 'يجب أن يكون اللغة سلسلة نصية.',
                'tr' => 'Dil dizesi olmalıdır.',
                'en' => 'The language must be a string.',
            ],
            'translations.*.address.required' => [
                'ar' => 'حقل العنوان مطلوب.',
                'tr' => 'Adres alanı gereklidir.',
                'en' => 'The address field is required.',
            ],
            'translations.*.address.string' => [
                'ar' => 'يجب أن يكون العنوان سلسلة نصية.',
                'tr' => 'Adres bir dize olmalıdır.',
                'en' => 'The address must be a string.',
            ],
            'translations.*.description.string' => [
                'ar' => 'يجب أن يكون الوصف سلسلة نصية.',
                'tr' => 'Açıklama bir dize olmalıdır.',
                'en' => 'The description must be a string.',
            ],
        ];
    }
}
