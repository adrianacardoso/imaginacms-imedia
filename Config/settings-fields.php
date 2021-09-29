<?php
$filesystems = config("filesystems.disks");
$disksOptions = [];
foreach ($filesystems as $index => $disk){
  array_push($disksOptions,["label" => $index, "value" => $index]);
}

return [
  
  'filesystem' => [
    "onlySuperAdmin" => true,
    'name' => 'media::filesystem',
    'value' => config("asgard.media.config.filesystem"),
    'type' => 'select',
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.filesystem',
      'useInput' => false,
      'useChips' => false,
      'multiple' => false,
      'hideDropdownIcon' => true,
      'newValueMode' => 'add-unique',
      'options' => $disksOptions
    ]
  ],
  
  'allowedTypes' => [
    "onlySuperAdmin" => true,
    'name' => 'media::allowedTypes',
    'value' => ["jpg", "png", "pdf", "jpeg", "mp4", "webm", "ogg", "svg"],
    'type' => 'select',
    'columns' => 'col-12 col-md-6',
    'props' => [
      'useInput' => true,
      'useChips' => true,
      'multiple' => true,
      'hint' => 'media::settings.hint.allowedTypes',
      'hideDropdownIcon' => true,
      'newValueMode' => 'add-unique',
      'label' => 'media::settings.label.allowedTypes'
    ],
  ],
  
  'maxFileSize' => [
    "onlySuperAdmin" => true,
    'name' => 'media::maxFileSize',
    'value' => config("asgard.media.config.max-file-size"),
    'type' => 'input',
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.maxFileSize'
    ]
  ],
  'maxTotalSize' => [
    "onlySuperAdmin" => true,
    'name' => 'media::maxTotalSize',
    'value' => config("asgard.media.config.max-total-size"),
    'type' => 'input',
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.maxTotalSize'
    ]
  ],
  
  "thumbnails" => [
    "onlySuperAdmin" => true,
    'name' => 'media::thumbnails',
    "value" => config("asgard.media.config.defaultThumbnails"),
    'label' => 'Thumbnail Config',
    "type" => "input",
    "props" => [
      'label' => 'media::settings.label.thumbnails',
      "type" => "textarea"
    ]
  
  ],
  
  //configuring AWS in the Modules\Media\Providers\MediaServiceProvider::155
  'awsAccessKeyId' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsAccessKeyId',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsAccessKeyId'
    ]
  ],
  'awsSecretAccessKey' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsSecretAccessKey',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsSecretAccessKey'
    ]
  ],
  'awsDefaultRegion' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsDefaultRegion',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsDefaultRegion'
    ]
  ],
  'awsBucket' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsBucket',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsBucket'
    ]
  ],
  'awsUrl' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsUrl',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsUrl'
    ]
  ],
  'awsEndpoint' => [
    "onlySuperAdmin" => true,
    'name' => 'media::awsEndpoint',
    'value' => "",
    'type' => 'input',
    "group" => "media::settings.groups.aws",
    'columns' => 'col-12 col-md-6',
    'props' => [
      'label' => 'media::settings.label.awsEndpoint'
    ]
  ],
];
