<?php

namespace Modules\Media\Repositories\Cache;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Media\Entities\File;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\ZoneRepository;
use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CacheFileDecorator extends BaseCacheCrudDecorator implements FileRepository
{
    public function __construct(FileRepository $repository)
    {
        parent::__construct();
        $this->entityName = 'media.files';
        $this->repository = $repository;
    }

    public function createFromFile(UploadedFile $file, $parentId = 0, $disk = null)
    {
      $this->cache->tags($this->getTags())->flush();
      return $this->repository->createFromFile($file, $parentId, $disk);
    }

    public function findFileByZoneForEntity($zone, $entity)
    {
      return $this->repository->findFileByZoneForEntity($zone, $entity);
    }

    public function findMultipleFilesByZoneForEntity($zone, $entity)
    {
      return $this->repository->findMultipleFilesByZoneForEntity($zone, $entity);
    }

    public function serverPaginationFilteringFor(Request $request)
    {
      return $this->repository->serverPaginationFilteringFor($request);
    }

    public function allChildrenOf($folderId): Collection
    {
      return $this->repository->allChildrenOf($folderId);
    }

    public function findByAttributes(array $attributes)
    {
      return $this->repository->findByAttributes($attributes);
    }

    public function allForGrid(): Collection
    {
      return $this->repository->allForGrid();
    }

    public function move(File $file, File $destination): File
    {
      return $this->repository->move($file, $destination);
    }

    public function findForVirtualPath($criteria)
    {
      return $this->repository->findForVirtualPath($criteria);
    }
}
