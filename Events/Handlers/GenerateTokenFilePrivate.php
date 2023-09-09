<?php

namespace Modules\Media\Events\Handlers;

use Modules\Media\Events\FileWasCreated;

class GenerateTokenFilePrivate
{
  /**
   * @var Factory
   */

  public function __construct()
  {
  }

  public function handle(FileWasCreated $event)
  {
    $file = $event->file;

    if ($file->disk == 'privatemedia') {
      $file->tokenable = $file->generateToken(0, 99999*10);
    }
  }
}
