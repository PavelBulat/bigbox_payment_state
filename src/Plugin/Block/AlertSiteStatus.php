<?php

namespace Drupal\bigbox_payment_state\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "alert_site_status",
 *  admin_label = @Translation("Информация о статусе оплате за сайт"),
 * )
 */
class AlertSiteStatus extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $vars = [];
    $site_status = \Drupal::state()->get('bigbox_site_active');
    $site_expired = \Drupal::state()->get('bigbox_time_close_site');
    $site_trial = \Drupal::state()->get('bigbox_trial');
    $visible = \Drupal::state()->get('bigbox_alert_visible');
    $background = 'standart';
    $button_title = 'Оплатить';
    $message = '';
    
    // Истекает время оплаты.
    if (time() + 7 * 24 * 60 * 60 > $site_expired && $site_status) {
      $background = 'warning';
    }
    
    // Пробная версия. Время ещё не вышло.
    if ($site_trial && $site_status) {
      $message = 'Тестовый период заканчивается ' . date('d-m-Y', $site_expired) . '.';
    }
  
    // Пробная версия. Время истекло.
    if ($site_trial && !$site_status) {
      $message = 'Тестовый период завершён.';
      $background = 'close';
      $visible = TRUE;
    }
  
    // Сайт оплачен. Время не истекло.
    if (!$site_trial && $site_status) {
      $message = 'Сайт оплачен до ' . date('d-m-Y', $site_expired) . '.';
      $button_title = 'Продлить';
    }
  
    // Время оплаты на платном тарифе истекло.
    if (!$site_trial && !$site_status) {
      $message = 'Оплаченный период завершён. Сайт заблокирован.';
      $background = 'close';
      $button_title = 'Продлить';
      $visible = TRUE;
    }

    $vars['button_title'] = $button_title;
    $vars['background'] = $background;
    $vars['message'] = $message;
    $vars['open'] = $visible;
    $vars['payment_url'] = \Drupal::config('bigbox_payment_state.settings')->get('redirect_url') . '?site=' . \Drupal::state()->get('bigbox_domain') . '&status=' . $site_status . '&trial=' . $site_trial;
    
    $build = [];
    $build['#theme'] = 'alert_site_status';
    $build['#vars'] = $vars;
    
    return $build;
  }

}
