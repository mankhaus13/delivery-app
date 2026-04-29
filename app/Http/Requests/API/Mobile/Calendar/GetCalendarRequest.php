<?php

namespace App\Http\Requests\API\Mobile\Calendar;

use App\DTO\API\Mobile\Calendar\GetCalendarDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class GetCalendarRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'bail',
                'required',
                'date_format:Y-m',
                'before:tomorrow',
            ],
        ];
    }

    public function toDTO(): GetCalendarDTO
    {
        $data = $this->validated();

        return new GetCalendarDTO(
            date: $data['date'],
            expeditorId: (int)Auth::id(),
        );
    }
}
