<?php

namespace Drupal\sitestudio_debug\EventSubscriber;

use Drupal\cohesion\Event\RequestExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\sitestudio_debug\Service\EventLoggerService;

/**
 *
 */
class RequestExceptionEventSubscriber implements EventSubscriberInterface {
  /** @var \Drupal\sitestudio_debug\Service\EventLoggerService */
  protected $loggerService;

  public function __construct(EventLoggerService $loggerService) {
    $this->loggerService = $loggerService;
  }

  public static function getSubscribedEvents() {
    return [
      'cohesion.request_exception' => 'onRequestException',
    ];
  }

  public function onRequestException(RequestExceptionEvent $event) {
    // Extract entity information from the event data or URI
    $entityId = $this->extractEntityId($event);
    $entityType = $this->extractEntityType($event);

    $this->loggerService->logEvent([
      'timestamp' => time(),
      'method' => $event->getMethod(),
      'uri' => $event->getUri(),
      'data' => json_encode($event->getPayload()),
      'status_code' => $event->getStatusCode(),
      'request_id' => $event->getRequestId(),
      'exception_message' => $event->getExceptionMessage(),
      'response_data' => json_encode($event->getResponseData()),
      'request_duration' => $event->getRequestDuration(),
      'entity_id' => $entityId,
      'entity_type' => $entityType,
    ]);
  }

  /**
   * Extract entity ID from the event data or URI.
   */
  protected function extractEntityId(RequestExceptionEvent $event) {
    // Check if entity ID is available in the event data
    if (method_exists($event, 'getEntityId')) {
      return $event->getEntityId();
    }

    // Try to extract from URI patterns
    $uri = $event->getUri();
    if (preg_match('/\/entity\/(\w+)\/(\d+)/', $uri, $matches)) {
      return $matches[2];
    }

    // Try to extract from request data
    $data = $event->getData();
    if (is_array($data)) {
      if (isset($data['entity_id'])) {
        return $data['entity_id'];
      }
      if (isset($data['id'])) {
        return $data['id'];
      }
    }

    return NULL;
  }

  /**
   * Extract entity type from the event data or URI.
   */
  protected function extractEntityType(RequestExceptionEvent $event) {
    // Check if entity type is available in the event data
    if (method_exists($event, 'getEntityType')) {
      return $event->getEntityType();
    }

    // Try to extract from URI patterns
    $uri = $event->getUri();
    if (preg_match('/\/entity\/(\w+)\/\d+/', $uri, $matches)) {
      return $matches[1];
    }

    // Try to extract from request data
    $data = $event->getData();
    if (is_array($data)) {
      if (isset($data['entity_type'])) {
        return $data['entity_type'];
      }
      if (isset($data['type'])) {
        return $data['type'];
      }
    }

    // Try to infer from URI patterns common in Site Studio
    if (strpos($uri, '/cohesion') !== FALSE) {
      if (strpos($uri, '/component') !== FALSE) {
        return 'cohesion_component';
      }
      if (strpos($uri, '/layout') !== FALSE) {
        return 'cohesion_layout';
      }
      if (strpos($uri, '/style') !== FALSE) {
        return 'cohesion_style';
      }
      if (strpos($uri, '/template') !== FALSE) {
        return 'cohesion_template';
      }
    }

    return NULL;
  }

}
