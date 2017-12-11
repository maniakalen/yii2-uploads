<?php
/**
 * PHP Version 5.5
 *
 *  Uploads manager
 *
 * @category FileUpload
 * @package  Maniakalen_FileUplad
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */

namespace maniakalen\uploads\components;

use maniakalen\uploads\interfaces\FileUploadModel;
use Yii;
use yii\base\Component;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class UploadsManager
 *
 *  Uploads manager
 *
 * @category FileUpload
 * @package  Maniakalen_FileUplad
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */
class UploadsManager extends Component
{
    /**
     * @param FileUploadModel $model
     *
     * @return bool
     */
    public function uploadModel(FileUploadModel $model)
    {
        if ($model->validate()) {
            $files = $this->getModelFilesAttrs($model);
            if (!empty($files)) {
                $directory = '@uploads' . DIRECTORY_SEPARATOR . $model->getModelFilesDirectory();
                foreach ($files as $file) {
                    $fileInstances = UploadedFile::getInstances($model, $file);
                    if (!empty($fileInstances)) {
                        foreach ($fileInstances as $fileInstance) {
                            $fileInstance->saveAs(Yii::getAlias($directory . DIRECTORY_SEPARATOR . $file) .
                                DIRECTORY_SEPARATOR . $fileInstance->baseName . '.' . $fileInstance->extension);
                        }
                    }
                }
            }
            return true;
        }

        return false;
    }

    public function getModelFilesAttrs(FileUploadModel $model)
    {
        $rules = $model->rules();
        $fileFieldsList = [];
        if (!empty($rules)) {
            foreach ($rules as $rule) {
                if (isset($rule[1]) && $rule[1] == 'file') {
                    $files = isset($rule[0])?is_array($rule[0])?$rule[0]:[$rule[0]]:[];
                    $fileFieldsList = ArrayHelper::merge($fileFieldsList, $files);
                }
            }
        }
        return $fileFieldsList;
    }
}