<?php

namespace Egysatria\Controllers\Auth;

use Egysatria\Models\Users;
use Egysatria\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
	public function getSignUp( $request, $response )
	{
		return $this->view->render( $response, 'auth/signup.twig' );
	}

	public function postSignUp( $request, $response )
	{
		$validation = $this->validator->validate($request, [
			'name'     => v::noWhitespace()->notEmpty()->alpha(),
			'email'    => v::noWhitespace()->notEmpty(),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		if( $validation->failed() )
		{
			return $response->withRedirect( $this->router->pathFor('auth.signup') );
		}
		
		$user = Users::create([
			'users_name' 	 => $request->getParam('name'),
			'email_users' 	 => $request->getParam('email'),
			'password_users' => password_hash( $request->getParam('password'), PASSWORD_DEFAULT ),
		]);
		return $response->withRedirect( $this->router->pathFor('home') );
	}
}