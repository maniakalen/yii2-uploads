<?php
/**
 * PHP Version 5.5
 *
 *  $DESCRIPTION$ $END$
 *
 * @category $Category$ $END$
 * @package  $Package$ $END$
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     $LINK$ $END$
 */

namespace maniakalen\uploads\interfaces;


interface FileUploadModel
{
    public function getModelFilesDirectory();
    public function validate();
    public function rules();
    public function getFileForDownload($attr, $idx);
    public function loadObject($id);

    public static function downloadHash();
}