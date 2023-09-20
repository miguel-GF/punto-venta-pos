<?php

namespace App\Http\Requests;

use App\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VentaRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules sthat apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules(): array
  {
    return [
      'clienteId' => 'required',
      'productos' => 'required',
      'totalVenta' => 'required',
      'numeroProductos' => 'required',
      'fechaActual' => 'required',
    ];
  }

  public function messages()
	{
		return [
			'clienteId.required' => 'El cliente es obligatorio.',
			'productos.required' => 'Los productos son obligatorios.',
			'totalVenta.required' => 'El total de venta es obligatorio.',
			'numeroProductos.required' => 'Número de productos es obligatorio.',
			'fechaActual.required' => 'La fecha actual es requerida.',
		];
	}

  protected function failedValidation(Validator $validator)
	{
		$response = response([
      'mensaje' => $validator->errors()->first(),
      'status' => Constants::ERROR_DATOS_INVALIDOS,
    ]);
		throw new HttpResponseException($response);
	}
}
