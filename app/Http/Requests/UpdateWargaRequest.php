<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kelurahan' => 'required|string|max:100',
            'penghasilan' => 'required|string',
            'tanggungan' => 'required|string',
            'rumah' => 'required|string',
            'pekerjaan' => 'required|string',
            'aset' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama warga wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rw.required' => 'RW wajib diisi.',
            'kelurahan.required' => 'Kelurahan wajib diisi.',
            'penghasilan.required' => 'Penghasilan wajib dipilih.',
            'tanggungan.required' => 'Jumlah tanggungan wajib dipilih.',
            'rumah.required' => 'Kondisi rumah wajib dipilih.',
            'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
            'aset.required' => 'Kepemilikan aset wajib dipilih.',
        ];
    }
}
