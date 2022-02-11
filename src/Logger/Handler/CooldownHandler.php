<?php declare(strict_types=1);

namespace Drupal\omnipedia_logger\Logger\Handler;

use Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

/**
 * Monolog handler that ignores log messages until a set time has passed.
 *
 * This essentially acts as a cooldown or debounce handler, preventing rapid
 * fire spam of log messages. This is intended primarily for email or other
 * notifications, where the first notification needs to be sent but no
 * subsequent messages should be sent until the specified time has elapsed.
 *
 * @see \Monolog\Handler\BufferHandler
 *
 * @see \Monolog\Handler\DeduplicationHandler
 *
 * @todo Can this buffer the log messages received during the cooldown time and
 *   pass them to the specified handler as a batch once the cooldown expires?
 *   I.e. call $this->handler->handleBatch()
 */
class CooldownHandler extends BufferHandler {

  /**
   * The Drupal expirable key/value store factory service.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface
   */
  protected KeyValueExpirableFactoryInterface $keyValueExpirableFactory;

  /**
   * The key/value store name.
   *
   * @var string
   */
  private string $storeName;

  /**
   * The key/value store key name.
   *
   * @var string
   */
  protected const STORE_KEY = 'cooldown';

  /**
   * The minimum logging level for log records to be looked at.
   *
   * @var int
   */
  protected int $logLevel;

  /**
   * The time (in seconds) to wait before allowing a subsequent log through.
   *
   * @var int
   */
  protected int $time;

  /**
   * Constructor.
   *
   * @param \Monolog\Handler\HandlerInterface $handler
   *   The handler to wrap.
   *
   * @param string $storeName
   *   The machine name to store the time the last log message was allowed
   *   through. This is usually the log channel name.
   *
   * @param string|int $logLevel
   *   The minimum logging level for log records to be looked at.
   *
   * @param int $time
   *   The period (in seconds) during which entries should be suppressed after
   *   the first log is sent through. Defaults to 600 seconds or ten minutes.
   *
   * @param bool $bubble
   *   Whether the messages that are handled can bubble up the stack or not.
   */
  public function __construct(
    HandlerInterface $handler, string $storeName,
    string|int $logLevel = Logger::ERROR, int $time = 600, bool $bubble = true
  ) {

    parent::__construct($handler, 0, Logger::DEBUG, $bubble, false);

    $this->logLevel = Logger::toMonologLevel($logLevel);

    $this->storeName = $storeName;

    $this->time = $time;

  }

  /**
   * Set dependencies.
   *
   * This is done here rather than in the constructor so that arguments can be
   * passed to the constructor by a service that uses this as its parent service
   * without that child service needing to know about these dependencies.
   *
   * @param \Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface $keyValueExpirableFactory
   *   The Drupal expirable key/value store factory service.
   */
  public function setDependencies(
    KeyValueExpirableFactoryInterface $keyValueExpirableFactory
  ): void {
    // @keyvalue.expirable
    $this->keyValueExpirableFactory = $keyValueExpirableFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function flush(): void {

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreExpirableInterface */
    $store = $this->keyValueExpirableFactory->get($this->storeName);

    // Key exists and has not expired.
    if ($store->has(self::STORE_KEY)) {
    }

    // If this is true, the key did not exist and so the log message should be
    // let through.
    if ($store->setWithExpireIfNotExists(self::STORE_KEY, true, $this->time)) {
    }

    // @todo

  }

}
