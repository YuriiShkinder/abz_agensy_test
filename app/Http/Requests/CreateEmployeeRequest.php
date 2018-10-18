<?php declare( strict_types = 1 );

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateEmployeeRequest
 *
 * @package App\Http\Requests
 */
class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'last_name'       => 'required|string',
            'first_name'      => 'required|string',
            'salary'          => 'required|int',
            'data_reception'  => 'required|date_format:Y-m-d',
            'boss'            => 'required|exists:employees,hash',
            'position'        => 'required|exists:positions,hash',
            'img'             => 'required|image|mimes:jpeg,png'
        ];
    }

}
