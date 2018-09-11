<?php

namespace Drupal\bigbox_payment_state;

use Drupal\Core\Controller\ControllerBase;
use function MongoDB\BSON\toJSON;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Обработчик запроса от bigbox.by.
 */
class SiteStatusController extends ControllerBase {
  
  public function changeSiteStatus(){
    // TODO: обеспечить защиту, что бы нельзя было изменять время работы сайта кому попало.
    $data = json_decode(\Drupal::request()->getContent(), TRUE);
    if ($data['work_time']) {
      \Drupal::state()->set('bigbox_time_close_site', (int) $data['work_time']);
      \Drupal::state()->set('bigbox_site_active', time() < (int) $data['work_time']);          
      \Drupal::state()->set('bigbox_trial', $data['trial']);
      return new JsonResponse(json_encode(["success" => [ "work_time" => \Drupal::state()->get('bigbox_time_close_site')]]));
    }
  
    return new JsonResponse(json_encode(["error" => 'Время выключения сайта не установлено']));
  }
  
  public function getSiteStatus() {
    $trial = \Drupal::state()->get('bigbox_trial');
    $time = \Drupal::state()->get('bigbox_time_close_site');
    $status = \Drupal::state()->get('bigbox_site_active');
    $redirect_url = \Drupal::config('bigbox_payment_state.settings')->get('redirect_url') . '?site=' . \Drupal::state()->get('bigbox_domain') . '&status=' . $status . '&trial=' . $trial;
    
    $admin = \Drupal::currentUser()->isAuthenticated();
  
    return new JsonResponse(json_encode(['trial' => $trial, 'time' => $time, 'status' => $status, 'redirect_url' => $redirect_url, 'admin' => $admin]));
  }
  
}
