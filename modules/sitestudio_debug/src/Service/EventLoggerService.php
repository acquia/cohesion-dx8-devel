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
    // Filter event data to only allowed fields.
    $filtered_data = array_intersect_key(
      $event_data,
      array_flip(self::$allowedFields)
    );
    $this->database->insert('sitestudio_debug_events')
      ->fields($filtered_data)
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
