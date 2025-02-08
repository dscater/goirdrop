<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{

    private $modulo = "ROLES";
    private $no_delete_role = [1, 2]; // super administrador|cliente

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $roles = Role::select("roles.*")->where("usuarios", 1)->get();
        return $roles;
    }

    public function listadoPaginado(int $length, int $start, int $page, string $search): LengthAwarePaginator
    {
        $usuarios = Role::select("roles.*");
        if ($search && trim($search) != '') {
            $usuarios->where("nombre", "LIKE", "%$search%");
        }
        $usuarios = $usuarios->paginate($length, ['*'], 'page', $page);
        return $usuarios;
    }

    /**
     * Crear role
     *
     * @param array $datos
     * @return Role
     */
    public function crear(array $datos): Role
    {

        $role = Role::create([
            "nombre" => mb_strtoupper($datos["nombre"])
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN ROLE", $role);

        return $role;
    }

    /**
     * Actualizar role
     *
     * @param array $datos
     * @param Role $role
     * @return Role
     */
    public function actualizar(array $datos, Role $role): Role
    {
        $old_role = Role::find($role->id);
        $role->update([
            "nombre" => mb_strtoupper($datos["nombre"])
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN ROLE", $old_role, $role);

        return $role;
    }

    /**
     * Eliminar role
     *
     * @param Role $role
     * @return boolean
     */
    public function eliminar(Role $role): bool
    {
        // verificar usos
        $usos = User::where("role_id", $role->id)->get();
        if (count($usos) > 0) {
            throw ValidationException::withMessages([
                'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
            ]);
        }

        // no eliminar roles predeterminados para el funcionamiento del sistema
        if (!in_array($role->id, $this->no_delete_role)) {
            $old_role = Role::find($role->id);
            $role->o_permisos()->delete();
            $role->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN ROLE", $old_role);

            return true;
        }
        return false;
    }
}
