<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $medicineId = $this->route('medicine') ? $this->route('medicine')->id : null;

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:medicines,code,' . $medicineId,
            'category' => 'required|string|max:100',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'expired_date' => 'nullable|date',
        ];
    }
}
