<?php

//hacer composer dumpautoload -o 
namespace App\Controllers;

use \Firebase\JWT\JWT;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Turno;

class TurnoController
{
    public function registro(Request $request, Response $response, $args)
    {   
        $parsedBody = $request->getParsedBody();
        
        if(!(empty($parsedBody["tipo"])) && !(empty($parsedBody["fecha"])))
        {
            //Otengo todos los headers
            $header = getallheaders();
            //Esto no lo valido porque ya se valida en los middlewares
            $token = JWT::decode($header['token'], "segundoparcial", array('HS256'));
                           
            $turno = new Turno();
            $turno->nombre_cliente = $token->nombre;
            $turno->tipo_mascota = $parsedBody["tipo"];
            $turno->fecha = $parsedBody["fecha"];
            $response->getBody()->write(json_encode($turno->save()));
            $response->getBody()->write(json_encode(" Registro exitoso!"));  
        }
        else
        {
            $response->getBody()->write(json_encode("Param registrar un turno debe enviar tipo de mascota y fecha del turno"));
        }

        return $response;
    } 
    
    public function funcionJoin (Request $request, Response $response, $args)
    {
        $consulta = Turno::join('mascotas', 'turnos.tipo_mascota', '=', 'mascotas.tipo')
        ->select('turnos.nombre_cliente', 'turnos.tipo_mascota', 'turnos.fecha' , 'mascotas.precio')->get();

        $response->getBody()->write(json_encode($consulta));

        return $response;
    }

    public function asignarAtendido(Request $request, Response $response, $args)
    {
        //Obtengo el body
        $parsedBody = $request->getParsedBody();

        $idTurno = $args["idTurno"];

        //Valida existencia turno
        $turno = Turno::find($args["idTurno"]);

        if($turno == null)
        {
            $response->getBody()->write(json_encode("No existe un turno de id: $idTurno"));
        }
        else
        {
            //La tengo que agarrar con find para poder modificarle una atributo y despues guarda la version nueva en la tabla
            $turno = Turno::find($idTurno);
                    
            $turno->atendido = "SI";
            $turno->save();
                
            $response->getBody()->write(json_encode("Asignacion de atendido exitosa"));
        }

        return $response;
    }
}