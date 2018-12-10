<?php

namespace Drupal\cohesion_devel\Controller;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
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
    $module = system_get_info('module', 'cohesion');
    if (version_compare('8.x-3.11', $module['version']) === -1) {
      // New (>=5.0)
      $colour_entities = $this->entityTypeManager
        ->getStorage('cohesion_color')
        ->loadMultiple();

      foreach ($colour_entities as $colour) {
        $data = Json::decode($colour->get('json_values'));
        $colours[] = $data['variable'] . ': ' . $data['value']['value']['rgba'] . ';';
      }
    }
    else {
      // Old (<=3.11)
      $palette = $this->entityTypeManager()
        ->getStorage('cohesion_website_settings')
        ->load('color_palette');

      $data = Json::decode($palette->get('json_values'));

      $colours = [];
      foreach ($data['colors'] as $item) {
        $colours[] = $item['variable'] . ': ' . $item['value']['value']['rgba'] . ';';
      }
    }

    return $colours;

  }

}