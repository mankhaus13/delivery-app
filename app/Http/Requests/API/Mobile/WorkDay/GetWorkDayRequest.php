<?php

namespace App\Http\Requests\API\Mobile\WorkDay;

use App\DTO\API\Mobile\WorkDay\GetWorkDayDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class GetWorkDayRequest extends ApiRequest
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
                'date_format:d.m.Y',
                'before:tomorrow',
            ],
        ];
    }

    public function toDTO(): GetWorkDayDTO
    {
        $data = $this->validated();

        return new GetWorkDayDTO(
            date: $data['date'],
            expeditorId: (int)Auth::id(),
        );
    }
}
