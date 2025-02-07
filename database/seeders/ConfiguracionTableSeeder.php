<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuracion::create([
            "nombre_sistema" => "GOIRDROP S.A.",
            "alias" => "GOIRDROP.",
            "logo" => "logo.png",
            "fono" => "7777777",
            "dir" => "ZONA LOS OLIVOS C. 4 #333",
            "conf_correos" => '{
                                "host": "smtp.hostinger.com",
                                "correo": "mensaje@emsytsrl.com",
                                "driver": "smtp",
                                "nombre": "unibienes",
                                "puerto": "587",
                                "password": "8Z@d>&kj^y",
                                "encriptado": "tls"
                            }',
            "conf_moneda" => '{
                                "moneda": "Bolivianos",
                                "abrev": "Bs"
                            }',
            "conf_captcha" => '{
                            "key": "aaa",
                            "key2": "bbb"
                        }',
        ]);
    }
}
