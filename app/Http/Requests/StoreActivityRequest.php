<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreActivityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('activities')
                    ->ignore($this->route('activity')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'operations.*' => ['integer'],
            'operations' => ['array'],

            'recovery_time_objective' => ['nullable', 'regex:/^\d{1,3}:[0-5]\d$/'],
            'maximum_tolerable_downtime' => [
                'nullable',
                'regex:/^\d{1,3}:[0-5]\d$/',
                function ($attribute, $value, $fail) {
                    $rto = $this->input('recovery_time_objective');
                    $mtd = $value;

                    if ($rto && $mtd) {
                        $rtoParts = explode(':', $rto);
                        $mtdParts = explode(':', $mtd);

                        if (count($rtoParts) === 2 && count($mtdParts) === 2) {
                            [$h1, $m1] = $rtoParts;
                            [$h2, $m2] = $mtdParts;
                            $rtoMinutes = (int) $h1 * 60 + (int) $m1;
                            $mtdMinutes = (int) $h2 * 60 + (int) $m2;

                            if ($rtoMinutes >= $mtdMinutes) {
                                $fail('recovery_time_objective >= maximum_tolerable_downtime');
                            }
                        }
                    }
                },
            ],

            'recovery_point_objective' => ['nullable', 'regex:/^\d{1,3}:[0-5]\d$/'],
            'maximum_tolerable_data_loss' => [
                'nullable',
                'regex:/^\d{1,3}:[0-5]\d$/',
                function ($attribute, $value, $fail) {
                    $rpo = $this->input('recovery_point_objective');
                    $mtdl = $value;

                    if ($rpo && $mtdl) {
                        $rpoParts = explode(':', $rpo);
                        $mtdlParts = explode(':', $mtdl);

                        if (count($rpoParts) === 2 && count($mtdlParts) === 2) {
                            [$h1, $m1] = $rpoParts;
                            [$h2, $m2] = $mtdlParts;
                            $rpoMinutes = (int) $h1 * 60 + (int) $m1;
                            $mtdlMinutes = (int) $h2 * 60 + (int) $m2;

                            if ($rpoMinutes >= $mtdlMinutes) {
                                $fail('recovery_point_objective >= maximum_tolerable_data_loss');
                            }
                        }
                    }
                },
            ],

        ];
    }
}
