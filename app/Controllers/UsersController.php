<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class UsersController extends BaseController{
  public function getAddUserAction($request) {
    $responseMessage = null;

    if(!empty($request->getMethod() == 'POST')) {
      //Setear reglas de validación
      $userValidator = v::key('email', v::stringType()->notEmpty())
                      ->key('password', v::stringType()->notEmpty());
      
      try {
        //Obtener la dada del request
        $postData = $request->getParsedBody();

        //Validar los datos
        $userValidator->assert($postData);

        //Inserción a BD
        $user = new User();
        $user->email = $postData['email'];
        $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
        $user->save();

        $responseMessage = 'User Saved';
      }
      catch(\Exception $e) {
        $responseMessage = $e->getMessage();
      }      
    }

    return $this->renderHTML('addUser.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}