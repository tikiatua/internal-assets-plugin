<?php
/**
* Internal Assets plugin for Craft CMS 4.x
*
* A simple plugin to restrict access to assets for permitted users only. Access 
* to a given asset is only granted if the user has view-permissions for the given 
* source (this can be set in the user- or group-settings). The asset source 
* folder should be moved out of the web root folder so the files are never 
* accessible without this plugin.
*
* @link      https://github.com/tikiatua
* @copyright Copyright (c) 2023 Ramon Saccilotto
*/

namespace saccilottoconsulting\internalassets\controllers;

use Craft;
use craft\web\Controller;
use craft\helpers\FileHelper;
use yii\web\HttpException;

/**
* Access Controller
* https://craftcms.com/docs/plugins/controllers
*
* @author    Ramon Saccilotto
* @package   InternalAssets
* @since     4.1.0
*/
class AccessController extends Controller
{
    // allow anonymous access to this function
    // protected $allowAnonymous = true;

    // check if the current user is allowed to access the given resource
    public function actionFetch(string $path)
    {

        // users must be logged in to access the resource
        $this->requireLogin();

        // fetch all volumes that are viewable by the current user
        $volumes = Craft::$app->volumes->getViewableVolumes();

        // prefix the path with internal
        $path = "internal/" . $path;

        $targetVolume = null;
        $volumeUrl = null;
        foreach ($volumes as $volume) {

            // get the public url of the volume
            $volumeUrl = $volume->rootUrl;

            // strip any prefixing slashes
            $volumeUrl = ltrim($volumeUrl, '/');
            
            // stripy anx suffix slashes from the volume url
            $volumeUrl = rtrim($volumeUrl, '/');

            // note: this might pose a problem, if the public url for 
            // one volume is a subpath of another. e.g. 
            // Volume A: /internal/assets
            // Volume B: /internal/assets/new
            // however, this sort of setup should probably not be used anyway
            // since it might conflict with folders created in the volume
            // with the shorter path name
            if (strpos($path, $volumeUrl.'/') === 0) {
                $targetVolume = $volume;
                break;
            }

        }

        if ($targetVolume === null) {
            throw new HttpException(401, "Sorry. You do not have permission to access this path");
            return;
        }

        // TODO: how to handle image transforms??
        $fs = $targetVolume->fs;

        // get the path of the asset relative to the root url of the volume
        $filepath = substr($path, strlen($volumeUrl));

        // replace any / with directory spearators if we are using the local
        // filesystem interface by CraftCMS
        if ($fs instanceof craft\base\LocalFsInterface) {
            $filepath = str_replace("/", DIRECTORY_SEPARATOR, $filepath);
        }

        // normalize the path
        // note: we need to watch out for .. in the file path which would allow
        // to escape the asset folder in the filesystem
        $filepath = FileHelper::normalizePath($filepath);

        // check if the given file exists
        if ($fs->fileExists($filepath) == false) {
            throw new HttpException(404, "Sorry. The given file was not found");
            return;
        }

        // get only the filename
        $filename = basename($filepath);

        // get the size of the file
        $filesize = $fs->getFileSize($filepath);

        // get the files mime type
        $mimeType = FileHelper::getMimeTypeByExtension($filename);

        // display pdfs inline
        $inline = false;
        if ($mimeType == "application/pdf") {
            $inline = true;
        }
        if (str_starts_with($mimeType, "image/")) {
            $inline = true;
        }
        if (str_starts_with($mimeType, "video/")) {
            $inline = true;
        }

        $stream = $fs->getFileStream($filepath);
        return $this->response->sendStreamAsFile($stream, $filename, [
            'fileSize' => $filesize,
            'mimeType' => $mimeType,
            'inline'   => $inline
        ]);

    }

}
