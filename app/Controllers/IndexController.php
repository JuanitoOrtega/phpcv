<?php

namespace App\Controllers;

use App\Models\{Job, Project};

class IndexController extends BaseController {
  public function indexAction() {

    $jobs = Job::all();

    $project1 = new Project('Project 1', 'Hello!');
    $projects = [$project1];
    $name = 'Sergio Minei';
    $limitMonths = 200;

    return $this->renderHTML('index.twig', [
      'name' => $name,
      'jobs' => $jobs
    ]);
  }
}