<?php

namespace App\Helpers;

use Request;
use File;

use App\Models\Admin;
use COM;
use Exception;

class ReturnPathHelper
{

    public static function getAdminImage($admin_id)
    {
        $admin = Admin::find($admin_id);


        if (!empty($admin)) {
            if (empty($admin->avatar)) {
                $image_url = 'public/assets/images/admins/default.png';

                // Find Gravator image from Gravaton
                if (GravatarHelper::validate_gravatar($admin->email)) {
                    return GravatarHelper::gravatar_image($admin->email, 200, "identicon");
                }
            } else {
                if (File::exists('public/assets/images/admins/' . $admin->avatar)) {
                    $image_url = 'public/assets/images/admins/' . $admin->avatar;
                } else {
                    // Find Gravator image from Gravaton
                    if (GravatarHelper::validate_gravatar($admin->email)) {
                      return GravatarHelper::gravatar_image($admin->email, 200, "identicon");
                    }
                    $image_url = 'public/assets/images/admins/default.png';
                }
            }
        } else {
            $image_url = 'public/assets/images/admins/default.png';
        }

        return asset($image_url);
    }

    /**
     * getUserImage
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public static function getUserImage($user_id)
    {
        $user = Admin::find($user_id);

        if ($user->profile_picture == NULL || $user->profile_picture == "") {
            $image_url = 'public/images/users/user.png';
            //Find Gravator image from Gravaton
            if (GravatarHelper::validate_gravatar($user->email)) {
                return GravatarHelper::gravatar_image($user->email, 200, "identicon");
            }
        } else {
            if (File::exists('public/images/users/' . $user->profile_picture)) {
                $image_url = 'public/images/users/' . $user->profile_picture;
            } else {
                //Find Gravator image from Gravaton
                if (GravatarHelper::validate_gravatar($user->email)) {
                    return GravatarHelper::gravatar_image($user->email, 200, "identicon");
                }
                $image_url = 'public/images/users/user.png';
            }
        }
        return url($image_url);
    }
    /**
     * @param string $file
     * Recover all file sizes larger than > 2GB.
     * Works on php 32bits and 64bits and supports linux
     * @return int|string
     */
    public static function fm_get_size($file)
    {
        static $iswin;
        static $isdarwin;
        if (!isset($iswin)) {
            $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
        }
        if (!isset($isdarwin)) {
            $isdarwin = (strtoupper(substr(PHP_OS, 0)) == "DARWIN");
        }

        static $exec_works;
        if (!isset($exec_works)) {
            $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
        }

        // try a shell command
        if ($exec_works) {
            $arg = escapeshellarg($file);
            $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : ($isdarwin ? "stat -f%z $arg" : "stat -c%s $arg");
            @exec($cmd, $output);
            if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
                return $size;
            }
        }

        // try the Windows COM interface
        if ($iswin && class_exists("COM")) {
            try {
                $fsobj = new COM('Scripting.FileSystemObject');
                $f = $fsobj->GetFile( realpath($file) );
                $size = $f->Size;
            } catch (Exception $e) {
                $size = null;
            }
            if (ctype_digit($size)) {
                return $size;
            }
        }

        // if all else fails
        return filesize($file);
    }
    /**
     * Get nice filesize
     * @param int $size
     * @return string
     */
    public static function fm_get_filesize($size)
    {
        $size = (float) $size;
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = ($size > 0) ? floor(log($size, 1024)) : 0;
        $power = ($power > (count($units) - 1)) ? (count($units) - 1) : $power;
        return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
    }
}
