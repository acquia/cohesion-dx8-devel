<?php

namespace Drupal\sitestudio_debug\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\sitestudio_debug\Service\EventLoggerService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for displaying detailed event information.
 */
class EventDetailController extends ControllerBase {

  /**
   * The event logger service.
   *
   * @var \Drupal\sitestudio_debug\Service\EventLoggerService
   */
  protected $loggerService;

  /**
   * Constructs an EventDetailController object.
   *
   * @param \Drupal\sitestudio_debug\Service\EventLoggerService $loggerService
   *   The logger service.
   */
  public function __construct(EventLoggerService $loggerService) {
    $this->loggerService = $loggerService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('sitestudio_debug.event_logger')
    );
  }

  /**
   * Displays the details of a specific event.
   *
   * @param int $event_id
   *   The ID of the event to display.
   *
   * @return array
   *   A render array for the event details page.
   */
  public function view($event_id) {
    $event = $this->loggerService->getEvent($event_id);

    if (!$event) {
      throw new NotFoundHttpException();
    }

    $build = [
      '#type' => 'container',
      '#attributes' => ['class' => ['event-detail']],
    ];

    $build['header'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('ID: @id', ['@id' => $event->id]),
    ];

    $build['details'] = [
      '#type' => 'details',
      '#title' => $this->t('Basic Information'),
      '#open' => TRUE,
      'content' => [
        '#theme' => 'item_list',
        '#items' => [
          $this->t('Timestamp: @timestamp', ['@timestamp' => date('Y-m-d H:i:s', $event->timestamp)]),
          $this->t('Method: @method', ['@method' => $event->method]),
          $this->t('URI: @uri', ['@uri' => $event->uri]),
          $this->t('Status Code: @status', ['@status' => $event->status_code]),
          $this->t('Request ID: @request_id', ['@request_id' => $event->request_id]),
          $this->t('Request Duration: @duration ms', ['@duration' => isset($event->request_duration) ? number_format($event->request_duration, 3) : 'N/A']),
        ],
      ],
    ];

    if (!empty($event->exception_message)) {
      $build['exception'] = [
        '#type' => 'details',
        '#title' => $this->t('Exception Message'),
        '#open' => TRUE,
        'content' => [
          '#type' => 'html_tag',
          '#tag' => 'pre',
          '#value' => $event->exception_message,
          '#attributes' => ['class' => ['exception-message']],
        ],
      ];
    }

    if (!empty($event->data)) {
      $build['request_data'] = [
        '#type' => 'details',
        '#title' => $this->t('Request Data'),
        '#open' => FALSE,
        'content' => [
          '#type' => 'html_tag',
          '#tag' => 'pre',
          '#value' => $event->data,
          '#attributes' => ['class' => ['request-data']],
        ],
      ];
    }

    if (!empty($event->response_data)) {
      $build['response_data'] = [
        '#type' => 'details',
        '#title' => $this->t('Response Data'),
        '#open' => FALSE,
        'content' => [
          '#type' => 'html_tag',
          '#tag' => 'pre',
          '#value' => $event->response_data,
          '#attributes' => ['class' => ['response-data']],
        ],
      ];
    }

    $build['back_link'] = [
      '#type' => 'link',
      '#title' => $this->t('Back to Event Report'),
      '#url' => Url::fromRoute('sitestudio_debug.report'),
      '#attributes' => ['class' => ['button']],
    ];

    return $build;
  }
}
