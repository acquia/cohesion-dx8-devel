<?php

namespace Drupal\cohesion_devel\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;

/**
 * Controller for style inspector.
 */
class StyleInspectorController extends ControllerBase {
  /**
   * Constructs the stylesheet inspector page.
   */
  public function index() {
    // Load the stylesheet.json
    $path = 'public://cohesion/styles/stylesheet.json';
    if (file_exists($path)) {
      $content = Json::decode(file_get_contents($path), TRUE);

      // Merge the 3rd level items together. 
      foreach ($content as $key => $value) {
        foreach ($value as $key2 => $value2) {
          if (is_array($value2)) {
            $content[$key][$key2] = implode("\n", $value2);
          }
        }
      }

      return [
        '#theme' => 'stylesheet_inspector',
        '#data' => $content,
        '#attached' => [
          'library' => [
            'cohesion_devel/stylesheet-inspector',
          ],
        ],
      ];
    }
    else {
      // No stylesheet.json file.
      return [
        '#markup' => $this->t('stylesheet.json file not found.'),
      ];
    }

  }

}
