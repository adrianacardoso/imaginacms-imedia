<?php

if (!function_exists('mimesAvailable')) {
  
  function mimesAvailableRule()
  {
    return 'mimes:' . join(',', json_decode(setting('media::allowedImageTypes', null, config("asgard.media.config.allowedImageTypes"))))
      . "," . join(',', json_decode(setting('media::allowedFileTypes', null, config("asgard.media.config.allowedFileTypes"))))
      . "," . join(',', json_decode(setting('media::allowedVideoTypes', null, config("asgard.media.config.allowedVideoTypes"))))
      . "," . join(',', json_decode(setting('media::allowedAudioTypes', null, config("asgard.media.config.allowedAudioTypes"))));
  
  }
}
