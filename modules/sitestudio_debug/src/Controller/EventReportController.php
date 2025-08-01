<?php

namespace Drupal\sitestudio_debug\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\sitestudio_debug\Service\EventLoggerService;

/**
 *
 */
class EventReportController extends ControllerBase {
  /** @var \Drupal\sitestudio_debug\Service\EventLoggerService */
  protected $loggerService;

  public function __construct(EventLoggerService $loggerService) {
    $this->loggerService = $loggerService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('sitestudio_debug.event_logger')
    );
  }

  public function report() {
    $header = [
      'id' => $this->t('ID'),
      'timestamp' => $this->t('Timestamp'),
      'method' => $this->t('Method'),
      'uri' => $this->t('URI'),
      'status_code' => $this->t('Status Code'),
      'request_id' => $this->t('Request ID'),
      'exception_message' => $this->t('Exception Message'),
      'data' => $this->t('Request Data'),
      'response_data' => $this->t('Response Data'),
      'request_duration' => $this->t('Request Duration'),
      'operations' => $this->t('Operations'),
    ];
    $rows = [];
    foreach ($this->loggerService->getAllEvents() as $event) {
      $edit_url = '';
      if (!empty($event->type) && !empty($event->id)) {
        try {
          $url = Url::fromRoute("entity.{$event->type}.edit_form", [
            $event->type => $event->id,
          ]);
          $edit_url = \Drupal::l($this->t('Edit'), $url);
        } catch (\Exception $e) {
          $edit_url = '';
        }
      }
      $rows[] = [
        'id' => $event->id,
        'timestamp' => date('Y-m-d H:i:s', $event->timestamp),
        'method' => $event->method,
        'uri' => $event->uri,
        'status_code' => $event->status_code,
        'request_id' => $event->request_id,
        'exception_message' => $event->exception_message,
        'data' => $event->data,
        'response_data' => $event->response_data,
        'request_duration' => isset($event->request_duration) ? number_format($event->request_duration, 3) : '',
        'operations' => $edit_url,
      ];
    }
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No events found.'),
    ];
  }

}
