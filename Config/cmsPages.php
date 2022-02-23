<?php

return [
  'admin' => [
    "index" => [
      "permission" => "media.medias.manage",
      "activated" => true,
      "path" => "/media/index",
      "name" => "app.media.index",
      "page" => "qmedia/_pages/admin/index",
      "layout" => "qsite/_layouts/master.vue",
      "title" => "media.cms.sidebar.adminIndex",
      "icon" => "fas fa-photo-video",
      "authenticated" => true,
      "subHeader" => [
        "refresh" => true
      ]
    ]
  ],
  'panel' => [],
  'main' => [
    "selectMediaCKEditor" => [
      "permission" => "media.medias.index",
      "activated" => true,
      "path" => "/media/select",
      "name" => "app.media.select",
      "page" => "qmedia/_pages/admin/selectCkEditor",
      "layout" => "qsite/_layouts/blank.vue",
      "title" => "media.cms.sidebar.adminIndex",
      "icon" => "fas fa-camera-retro",
      "authenticated" => true
    ]
  ]
];
