<?php

namespace Drupal\bigbox_payment_state;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;

/*
 * Изменяет состояние сообщения о статусе сайта.
 */
class ChangeVisibilityMessage extends ControllerBase {
  
  public function changeVisibility(){
    if ($_POST['data'] === 'true') \Drupal::state()->set('bigbox_alert_visible', TRUE);
    if ($_POST['data'] === 'false') \Drupal::state()->set('bigbox_alert_visible', FALSE);
    
    return new AjaxResponse([state => \Drupal::state()->get('bigbox_alert_visible')]);
  }
  
}