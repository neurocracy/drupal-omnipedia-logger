<?php

declare(strict_types=1);

namespace Drupal\omnipedia_logger\Hooks;

use Drupal\hux\Attribute\Hook;

/**
 * Mail hook implementations.
 */
class Mail {

  #[Hook('mail')]
  /**
   * Implements \hook_mail().
   *
   * @param string $key
   *   An identifier of the mail.
   *
   * @param array $message
   *   The message array to be filled in.
   *
   * @param array $params
   *   An array of parameters supplied by the caller of
   *   MailManagerInterface::mail().
   *
   * @see \Drupal\omnipedia_logger\Logger\Handler\DrupalMailHandler
   */
  public function mail(
    string $key, array &$message, array $params,
  ): void {

    $message['subject'] = $params['subject'];
    $message['body'][]  = $params['body'];

  }

}
