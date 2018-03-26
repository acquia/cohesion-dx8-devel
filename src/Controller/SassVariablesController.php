<?php

namespace Drupal\cohesion_devel\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class SassVariablesController extends ControllerBase
{

  function view(Request $request)
  {
    return [
      '#theme' => 'sass_variables',
      '#colours' => $this->colourVars()
    ];
  }

  protected function colourVars()
  {
    $palette = $this->entityTypeManager()
      ->getStorage('cohesion_website_settings')
      ->load('color_palette');

    $data = Json::decode($palette->get('json_values'));

    $colours = [];
    foreach ($data['colors'] as $item) {
      $colours[] = $item['variable'] . ': ' . $item['value']['value']['rgba'] . ';';
    }

    return $colours;

  }

}