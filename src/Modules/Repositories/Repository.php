<?php
namespace Yrevilla\Modules\Repositories;

use Illuminate\Filesystem\Filesystem;
use Yrevilla\Modules\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    protected $files;
    protected $path;


    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function getAllBaseNames()
    {
        $path = $this->getPath();

        try{
            $collection = collect($this->files->directories($path));
            $basenames = $collection->map(function($item, $key){
                return basename($item);
            });
            return $basenames;
        } catch (\InvalidArgumentException $e) {
            return collect(array());
        }
    }

    public function getManifest($slug)
    {
        if(!is_null($slug))
        {
            $module     = str_slug($slug);
            $path       = $this->getManifestPath($module);
            $contents   = $this->files->get($path);
            $collection = collect(json_decode($contents, true));
            return $collection;
        }

        return;
    }

    protected function getManifestPath($slug)
    {
        return $this->getModulePath($slug).'module.json';
    }

    public function getModulePath($slug)
    {
        $module = studly_case($slug);
        return $this->getPath()."/{$module}/";
    }

    public function getPath()
    {
        // TODO: change hardcode path in getPath()
        return $this->path ?: 'C:\xampp\htdocs\nueva_extranet\extranet\Modules';
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getNamespace()
    {
        // TODO: change hardcode namespace in getNameSpace
        return rtrim('Extranet\Modules\\', '/\\');
    }

}