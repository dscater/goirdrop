<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteUpdateRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{
    public function __construct(private ClienteService $clienteService) {}

    public function listado()
    {
        $clientes = Cliente::select("clientes.*")->get();
        return response()->JSON([
            "clientes" => $clientes
        ]);
    }

    public function update(ClienteUpdateRequest $request, Cliente $cliente)
    {
        DB::beginTransaction();
        try {
            $this->clienteService->updateInfoCliente($request->validated(), $cliente);
            DB::commit();
            return redirect()->route("usuarios.clientes")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
