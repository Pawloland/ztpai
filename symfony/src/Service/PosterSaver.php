<?php

namespace App\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PosterSaver
{
    public static function save(string $src_absolute_file_path, string $dst_file_name): void
    {
        #strip the src path from trailing slashes or spaces
        $src_absolute_file_path = rtrim($src_absolute_file_path);
        $src_absolute_file_path = rtrim($src_absolute_file_path, '/');

        # change owner user and group of the file to the user specified in FILESAVER_USER env var
        chmod($src_absolute_file_path, 0604); // keeps owner perms as is, allows other users to read the file,
        # so we can read it as FILESAVER_USER, thus can copy it to the destination dir before the tmp file gets deleted automatically

        $process = new Process([
            #this is done to fix permissions issue when saving files to a dir which is a bind mount from docker
            #this is a workaround for the fact that the process is run as www-data
            #and the save_poster script is run as the user specified in FILESAVER_USER env var
            #which is the user that has permissions to write to the /app/uploads/posters dir
            #FILESAVER_USER should be configured in docker-compose file to have the same UID:GID
            #as the owner user and owner group of the /app/uploads/posters dir
            'sudo', '-u', getenv('FILESAVER_USER'), '/sbin/poster_saver', $src_absolute_file_path, $dst_file_name
        ]);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new \RuntimeException('Failed to save poster: ' . $e->getMessage());
        }
    }
}
