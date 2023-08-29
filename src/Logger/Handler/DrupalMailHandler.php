<?php

declare(strict_types=1);

namespace Drupal\omnipedia_logger\Logger\Handler;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Monolog\Handler\MailHandler;
use Monolog\Logger;

/**
 * Monolog handler to send email using Drupal core mail manager.
 *
 * This is adapted from the Monolog module's class and uses dependency injection
 * when defined as an abstract service in your monolog.services.yml like so:
 *
 * @code
 *   monolog.handler.mail_abstract:
 *     class: Drupal\omnipedia_logger\Logger\Handler\DrupalMailHandler
 *     abstract: true
 *     calls:
 *       -
 *         - setDependencies
 *         -
 *           - '@language_manager'
 *           - '@plugin.manager.mail'
 *           - '@string_translation'
 * @endcode
 *
 * You can then set this handler as the parent to your own handler like so:
 *
 * @code
 *   monolog.handler.your_mail_handler:
 *     parent: monolog.handler.mail_abstract
 *     arguments:
 *       - 'example@example.com'
 *       - Etc.
 * @endcode
 *
 * Note how when defined like this your handler doesn't need to know about or
 * pass the required services which makes configuring multiple uses of this much
 * simpler.
 *
 * @see https://symfony.com/doc/4.4/service_container/parent_services.html
 *   Symfony parent service documentation.
 *
 * @see \omnipedia_logger_mail()
 *   Minimal \hook_mail() implementation to pass $param values to $message.
 *
 * @see \Drupal\monolog\Logger\Handler\DrupalMailHandler
 *   Copied from this, altered to use dependency injection, and customized the
 *   $params array structure sent to \omnipedia_logger_mail().
 */
class DrupalMailHandler extends MailHandler {

  use StringTranslationTrait;

  /**
   * The Drupal language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected readonly LanguageManagerInterface $languageManager;

  /**
   * The Drupal mail plug-in manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected readonly MailManagerInterface $mailManager;

  /**
   * The email address to send log emails to.
   *
   * @var string
   */
  private string $to;

  /**
   * Constructor.
   *
   * @param string $to
   *   The email address to send log emails to.
   *
   * @param int|string $level
   *   The minimum logging level at which this handler will be triggered.
   *
   * @param bool $bubble
   *   The bubbling behavior.
   */
  public function __construct(
    string      $to,
    int|string  $level = Logger::ERROR,
    bool        $bubble = true,
  ) {

    parent::__construct($level, $bubble);

    $this->to = $to;

  }

  /**
   * Set dependencies.
   *
   * This is done here rather than in the constructor so that arguments can be
   * passed to the constructor by a service that uses this as its parent service
   * without that child service needing to know about these dependencies.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The Drupal language manager service.
   *
   * @param \Drupal\Core\Mail\MailManagerInterface $mailManager
   *   The Drupal mail plug-in manager service.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function setDependencies(
    LanguageManagerInterface  $languageManager,
    MailManagerInterface      $mailManager,
    TranslationInterface      $stringTranslation,
  ): void {
    $this->languageManager    = $languageManager;
    $this->mailManager        = $mailManager;
    $this->stringTranslation  = $stringTranslation;
  }

  /**
   * {@inheritdoc}
   */
  protected function send(string $content, array $records): void {

    /** @var \Drupal\Core\Language\LanguageInterface The default site language object. */
    $defaultLanguage = $this->languageManager->getDefaultLanguage();

    /** @var array Parameters to send to the mail manager. */
    $params = [
      'subject' => $this->t(
        'A new @level message has been logged for channel "@channel"',
        [
          '@level'    => $records[0]['level_name'],
          '@channel'  => $records[0]['channel'],
        ],
      ),
      'body' => $content,
    ];

    $this->mailManager->mail(
      'omnipedia_logger', 'default', $this->to, $defaultLanguage->getName(),
      $params,
    );

  }

}
