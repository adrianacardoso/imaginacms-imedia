<?php

namespace Modules\Media\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Media\Http\Requests\MoveMediaRequest;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\Movers\Mover;

class MoveMediaController extends BaseCrudController
{
    /**
     * @var FileRepository
     */
    private $file;

    /**
     * @var FolderRepository
     */
    private $folder;

    /**
     * @var Mover
     */
    private $mover;

    public function __construct(
        FileRepository $file,
        FolderRepository $folder,
        Mover $mover
    ) {
        $this->file = $file;
        $this->folder = $folder;
        $this->mover = $mover;
    }

    public function __invoke(MoveMediaRequest $request): JsonResponse
    {
        $destination = $this->folder->findFolderOrRoot($request->get('destinationFolder'));

        $failedMoves = 0;
        foreach ($request->get('files') as $file) {
            $failedMoves = $this->mover->move($this->file->find($file['id']), $destination);
        }

        return response()->json([
            'errors' => $failedMoves > 0,
            'message' => $this->getResponseMessage($failedMoves),
            'folder_id' => $destination->id,
        ]);
    }

    protected function getResponseMessage(int $failedMoves): string
    {
        if ($failedMoves > 0) {
            return trans('media::media.some files not moved');
        }

        return trans('media::media.files moved successfully');
    }
}