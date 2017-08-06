<?php namespace Brackets\AdminGenerator\Generate\Traits;

trait Helpers {

    // FIXME this does not belong here
    public function option($key = null) {
        return ($key === null || $this->hasOption($key)) ? parent::option($key) : null;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

}
