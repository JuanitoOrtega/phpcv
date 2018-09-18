<?php

namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator as v;

class JobsController extends BaseController{
  public function getAddJobAction($request) {
    $responseMessage = null;

    if(!empty($request->getMethod() == 'POST')) {
      //Setear reglas de validación
      $jobValidator = v::key('title', v::stringType()->notEmpty())
                      ->key('description', v::stringType()->notEmpty());
      
      try {
        //Obtener la data del request
        $postData = $request->getParsedBody();

        //Validar los datos
        $jobValidator->assert($postData);

        //Subida de imagen
        $files = $request->getUploadedFiles();
        $logo = $files['logo'];

        if($logo->getError() == UPLOAD_ERR_OK) {
          $fileName = $logo->getClientFilename();
          $logo->moveTo("uploads/$fileName");
        }

        //Inserción a BD
        $job = new Job();
        $job->title = $postData['title'];
        $job->description = $postData['description'];
        $job->save();

        $responseMessage = 'Job Saved';
      }
      catch(\Exception $e) {
        $responseMessage = $e->getMessage();
      }      
    }

    return $this->renderHTML('addJob.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}