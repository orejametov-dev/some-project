<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
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
        return [
            'title_uz' => 'required|string',
            'title_ru' => 'required|string',
            'body_uz' => 'required|string',
            'body_ru' => 'required|string',
            'start_schedule' => 'nullable|date_format:Y-m-d H:i',
            'end_schedule' => 'nullable|date_format:Y-m-d H:i',
        ];
    }
}
