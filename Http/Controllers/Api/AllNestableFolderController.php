<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Media\Repositories\FolderRepository;

class AllNestableFolderController extends BaseCrudController
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function __construct(FolderRepository $folder)
    {
        $this->folder = $folder;
    }

    public function __invoke(): JsonResponse
    {
        $array = [];
        $folders = $this->folder->allNested()->nest()->listsFlattened('filename', null, 0, $array, '--- ');

        return response()->json($folders);
    }
}
