<?php
require_once "AuthJWT.php";
class MWAuth
{
	/**
	 * @api {any} /MWparaAutenticar/  Verificar Usuario
	 * @apiVersion 0.1.0
	 * @apiName VerificarUsuario
	 * @apiGroup MIDDLEWARE
	 * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
	 *
	 * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
	 * @apiParam {ResponseInterface} response El objeto RESPONSE.
	 * @apiParam {Callable} next  The next middleware callable.
	 *
	 * @apiExample Como usarlo:
	 *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
	 */
	public function verifyUser($request, $response, $next)
	{
		$rv = new stdclass();
		$rv->message = "";

		if (isset($request->getParsedBody()["jwt"]))
		{
			$jwt = $request->getParsedBody()["jwt"];

			if (AuthJWT::verifyToken($jwt))
			{$response = $next($request, $response);}
			else
			{
				$rv->message = "La credencial no es válida, ha expirado o no tiene los permisos para realizar ésta acción.";
				$response = $response->withJson($rv, 404);
			}
		}
		else {
			$rv->message = "No se han enviado las credenciales.";
			$response = $response->withJson($rv, 404);
		}


		return $response;
	}
}