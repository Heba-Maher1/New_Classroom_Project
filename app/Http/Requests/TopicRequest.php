<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:5',
            'classroom_id' => 'int|exists:classrooms,id' ,
            'user_id' => 'nullable|int|exists:users,id' ,
        ];
        }

        public function messages() : array
        {
            return [
                'required' => ':attribute Important !' ,
                'required.classroom_id' => 'Classroom Id must add',

            ];
        }



}
