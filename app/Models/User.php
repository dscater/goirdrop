<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "usuario",
        "nombres",
        "apellidos",
        "ci",
        "ci_exp",
        "correo",
        "password",
        "role_id",
        "sedes_todo",
        "foto",
        "fecha_registro",
        "acceso",
        "status",
    ];
    protected $appends = ["permisos", "url_foto", "foto_b64", "full_name", "full_ci", "fecha_registro_t", "usuario_abrev", "nom_sedes", "array_sedes_id"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    // APPENDS
    public function getArraySedesIdAttribute()
    {
        $id_sedes = [];
        if (Auth::check()) {
            if ($this->sedes_todo === 1) {
                $id_sedes =  Sede::select("id")->get()->pluck("id")->toArray();
            } else {
                $id_sedes = $this->sedes->pluck("id")->toArray();
            }
        }
        return $id_sedes;
    }

    public function getNomSedesAttribute()
    {
        $nom_sedes = [];
        if (Auth::check()) {
            if ($this->sedes_todo === 1) {
                $nom_sedes =  Sede::select("nombre")->get()->pluck("nombre")->toArray();
            } else {
                $id_sedes = $this->sedes->pluck("id")->toArray();
                $nom_sedes =  Sede::select("nombre")
                    ->whereIn("id", $id_sedes)->get()->pluck("nombre")->toArray();
            }
        }

        return !empty($nom_sedes) ? implode(", ", $nom_sedes) : "";
    }

    public function getUsuarioAbrevAttribute()
    {
        $tam_usuario = strlen($this->usuario);
        if ($tam_usuario > 8) {
            return substr($this->usuario, 0, 8) . "...";
        }

        return $this->usuario;
    }

    public function getPermisosAttribute()
    {
        return $this->getPermisos();
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullNameAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/users/" . $this->foto);
        }
        return asset("imgs/users/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/users/" . $this->foto);
        if (!$this->foto || !file_exists($path)) {
            $path = public_path("imgs/users/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    // RELACIONES
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 'sede_users', 'user_id', 'sede_id')->withTimestamps();
    }

    public function notificacions()
    {
        return $this->belongsToMany(Notificacion::class, 'notificacion_users', "user_id", "notificacion_id")->withTimestamps()->withPivot(["visto"]);
    }

    // FUNCIONES
    /**
     * Obtener permisos de usuario logeado
     *
     * @return array
     */
    public function getPermisos(): array|string
    {
        $role_id = Auth::user()->role_id;
        $role = Role::find($role_id);
        if ($role->permisos == 1) {
            // todos los permisos
            return "*";
        }
        $permisos = Permiso::join("modulos", "modulos.id", "=", "permisos.modulo_id")
            ->where("permisos.role_id", $role->id)
            ->pluck("modulos.nombre")
            ->toArray();

        return $permisos;
    }
}
