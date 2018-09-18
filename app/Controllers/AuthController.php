<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController{
  public function getLogin() {
    return $this->renderHTML('login.twig');
  }

  public function postLogin($request) {
    $responseMessage = null;

    if(!empty($request->getMethod() == 'POST')) {
      //Setear reglas de validación
      $loginValidator = v::key('email', v::stringType()->notEmpty())
                      ->key('password', v::stringType()->notEmpty());
      
      try {
        //Obtener la dada del request
        $postData = $request->getParsedBody();

        //Validar los datos
        $loginValidator->assert($postData);

        //Verificar si el usuarios existe
        $user = User::where('email', $postData['email'])->first();

        if($user) {
          if(\password_verify($postData['password'], $user->password)) {
            $_SESSION['userId'] = $user->id;
            return new RedirectResponse('/admin');
          }
          else {
            $responseMessage = 'Bad credentials';
          }
        }
        else {
          $responseMessage = 'Bad credentials';
        }
      }
      catch(\Exception $e) {
        $responseMessage = $e->getMessage();
      }      
    }

    return $this->renderHTML('login.twig', [
      'responseMessage' => $responseMessage
    ]);
  }

  public function getLogout() {
    unset($_SESSION['userId']);
    return new RedirectResponse('/login');
  }
}