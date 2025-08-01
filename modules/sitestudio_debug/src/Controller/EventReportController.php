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
      $edit_url = NULL;
      $operations_cell = NULL;
      if (!empty($event->entity_type) && !empty($event->entity_id)) {
        try {
          $url = Url::fromRoute("entity.{$event->entity_type}.edit_form", [
            $event->entity_type => $event->entity_id,
            'content_entity_type' => $event->entity_type,
          ]);
          $edit_url = [
            '#type' => 'link',
            '#title' => $this->t('Edit'),
            '#url' => $url,
            '#attributes' => [
              'class' => ['button', 'button--small'],
            ],
          ];
          $operations_cell = ['data' => $edit_url];
        } catch (\Exception $e) {
          // If the entity type or ID is invalid, we skip creating the edit link.
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
        'payload' => substr($event->data, 0, 100) . "...",
        'response_data' => $event->response_data,
        'request_duration' => isset($event->request_duration) ? number_format($event->request_duration, 3) : '',
        'operations' => $operations_cell,
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
