<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_id' => 'required|exists:reports,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'status' => 'required|in:delivered,in_process,completed,rejected',
            'description' => 'required|string',
        ];
    }
}
