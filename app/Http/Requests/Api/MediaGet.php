<?php

namespace App\Http\Requests\Api;

use App\Models\Category;
use App\Models\Media\Tag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MediaGet extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $category_table = (new Category)->getTable();
        $tags_table = (new Tag)->getTable();

        return [
            'category_id' => ['numeric', "exists:${category_table},id"],
            'order' => ['in:asc,desc'],
            'order_by' => ['in:random,date,views'],
            'tags' => ['array'],
            'tags.*' => ['numeric', "exists:${tags_table},id"]
        ];
    }

    protected function prepareForValidation()
    {
        if($this->input('tags')) {
            // Change comma separated tags into array
            $this->merge(['tags' => explode(',', $this->input('tags'))]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        return response()->json(['data' => ''], 500);
    }
}
