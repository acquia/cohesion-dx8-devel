<?php

namespace Drupal\sitestudio_debug\Service;

use Drupal\Core\Database\Connection;

/**
 *
 */
class EventLoggerService {
  /** @var \Drupal\Core\Database\Connection */
  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Logs an event to the sitestudio_debug_events table.
   *
   * @param array $event_data
   *   The event data to log.
   */
  public function logEvent(array $event_data) {
    $this->database->insert('sitestudio_debug_events')
      ->fields($event_data)
      ->execute();
  }

  /**
   * Fetches all logged events.
   *
   * @return array
   */
  public function getAllEvents() {
    return $this->database->select('sitestudio_debug_events', 'e')
      ->fields('e')
      ->orderBy('timestamp', 'DESC')
      ->execute()
      ->fetchAll();
  }

}
