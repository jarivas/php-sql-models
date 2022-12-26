<?php

declare(strict_types=1);

namespace SqlModels;

trait FolderHandler
{
    protected function createFolder(string $folder): bool|string
    {
        $error = shell_exec("rm -rf $folder");

        if ($error) {
            return $error;
        }

        $error = shell_exec("mkdir -p $folder");

        if ($error) {
            return $error;
        }

        return true;
    }

    protected function getStubsFolder(): bool|string
    {
        $folder = __DIR__.'/stubs/'; //I know this will not work on windows and I could not care less

        return file_exists($folder) ? $folder : false;
    }

    /**
     * @param string $fileName
     * @param string $newFileName
     * @param array<string> $search keys to search
     * @param array<string> $replace words to replace
     */
    protected function copyReplace(string $fileName, string $newFileName, array $search, array $replace): bool
    {
        $content = file_get_contents($fileName);

        if (!$content) {
            return false;
        }

        $content = str_replace($search, $replace, $content);

        $result = file_put_contents($newFileName, $content);

        return (is_numeric($result));
    }

}