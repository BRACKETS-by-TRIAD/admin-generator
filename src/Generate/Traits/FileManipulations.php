<?php namespace Brackets\AdminGenerator\Generate\Traits;

use Illuminate\Support\Facades\File;

trait FileManipulations {

    protected function strReplaceInFile($fileName, $ifExistsRegex, $find, $replaceWith) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }


}
