<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
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
            'all_merchants' => 'required_without:recipients|boolean',
            'recipients' => 'required_without:all_merchants|array',
            'recipients.*.merchant_id' => 'required|integer',
            'recipients.*.store_ids' => 'nullable|array',
        ];
    }
}
