<?php

namespace App\Http\Requests\Media;

use App\Models\Media\Tag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FileExists;

class StorePhotoRequest extends FormRequest
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
        $tags_table = (new Tag)->getTable();

        return [
            'filename' => ['required', new FileExists('upload/temporary')],
            'taken_at_type' => ['in:selected,category'],
            'taken_at' => ['sometimes', 'date_format:d/m/Y'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['numeric', "exists:${tags_table},id"]
        ];
    }

    protected function prepareForValidation()
    {
        if($this->input('tags')) {
            // Change comma separated tags into array
            $this->merge(['tags' => explode(';', $this->input('tags'))]);
        }
    }

    public function failedValidation(Validator $validator)
    {
        session()->flash('type', 'photo');
        parent::failedValidation($validator);
    }
}
