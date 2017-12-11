<?php
/**
 * PHP Version 5.5
 *
 *  Download controller to download files
 *
 * @category FileUpload
 * @package  Maniakalen_FileUplad
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */

namespace maniakalen\uploads\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class DownloadsController
 *
 *  CLASSDESCRIPTION
 *
 * @category FileUpload
 * @package  Maniakalen_FileUplad
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */
class DownloadsController extends Controller
{
    /**
     * @param $hash
     * @param $id
     * @param $attr
     * @param $idx
     *
     * @return Response
     * @throws HttpException
     */
    public function actionFile($hash, $id, $attr, $idx)
    {
        try {
            $obj = Yii::createObject(['class' => $hash]);
            $obj->loadObject($id);
            $file = $obj->getFileForDownload($attr, $idx);
            return Yii::$app->getResponse()->sendFile($file, basename($file));
        } catch (InvalidConfigException $ice) {
            Yii::error($ice, 'downloads');
        }

        throw new HttpException("Unable to download file");
    }
}