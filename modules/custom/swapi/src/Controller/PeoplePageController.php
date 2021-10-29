<?php

/**
* @return
* Contains \Drupal\swapi\Controller\PeoplePageController.
*/

namespace Drupal\swapi\Controller;

/**
* Provides route responses for the Swapi module.
*/
class PeoplePageController {

  /**
  * Returns a simple page.
  *
  * @return array
  *   A simple renderable array.
  */
  public function content() {
    $element = array(
      '#markup' => 'Hello',
    );
    return $element;
  }

}
