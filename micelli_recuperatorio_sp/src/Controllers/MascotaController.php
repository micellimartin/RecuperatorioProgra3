<?php

//hacer composer dumpautoload -o 
namespace App\Controllers;

use \Firebase\JWT\JWT;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Mascota;

class MascotaController
{
    public function registro(Request $request, Response $response, $args)
    {       
        $parsedBody = $request->getParsedBody();
        
        if(!(empty($parsedBody["tipo"])) && !(empty($parsedBody["precio"])))
        {
            $validacionTipo = MascotaController::validarTipo($parsedBody["tipo"]);

            if($validacionTipo == true)
            {
                $tipo = $parsedBody["tipo"];
                $precio = $parsedBody["precio"];
                
                $nuevaMascota = new Mascota();
                $nuevaMascota->tipo = $tipo;
                $nuevaMascota->precio = $precio;
                $nuevaMascota->id_tipo = (string)crc32(uniqid());
            
                $response->getBody()->write(json_encode($nuevaMascota->save()));
                $response->getBody()->write(json_encode(" Registro exitoso!"));
            }
            else
            {
                echo $validacionTipo;
                $response->getBody()->write(json_encode("Error: tipo solo puede ser perro, gato o huron"));
            }
        }
        else
        {
            $response->getBody()->write(json_encode("Para registrar una mascota tiene que enviar tipo y precio"));
        }
    
        return $response;
    }

    public static function validarTipo($tipo)
    {
        //tipo invalido
        $retorno = false;

        $tipo = strtolower($tipo);

        if($tipo == "perro" || $tipo == "gato" || $tipo == "huron")
        {
            //tipo valido
            $retorno = true;
        }

        return $retorno;
    }
}