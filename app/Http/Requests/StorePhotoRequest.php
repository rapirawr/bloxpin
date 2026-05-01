<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotoRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'tags' => 'nullable|string|max:500',
            'board_id' => 'nullable|exists:boards,id',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Silakan pilih foto untuk diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format yang didukung: JPG, PNG, GIF, WebP.',
            'image.max' => 'Ukuran foto maksimal 10MB.',
            'title.required' => 'Judul foto harus diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
        ];
    }
}
