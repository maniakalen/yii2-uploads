<?php
/**
 * PHP Version 5.5
 *
 *  Yii2 File upload handling module
 *
 * @category I18n
 * @package  Maniakalen_I18n
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */

namespace maniakalen\uploads;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Module as BaseModule;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 *
 *  Yii2 File upload handling module definition.
 *
 * @category FileUpload
 * @package  Maniakalen_FileUplad
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */
class Module extends BaseModule implements BootstrapInterface
{
    public $directory;
    public $urlRules;
    public $modelDirs;
    /**
     * Module initialisation
     *
     * @return null
     */
    public function init()
    {
        Yii::setAlias('@uploadModule', dirname(__FILE__));
        $config = include Yii::getAlias('@translations/config/main.php');
        Yii::configure($this, $config);
        if (!$this->controllerNamespace) {
            $this->controllerNamespace = 'maniakalen\uploads\controllers';
        }
        parent::init();

        $this->prepareEvents();
        $this->prepareContainer();

        return null;
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @return null
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            if (is_array($this->urlRules) && !empty($this->urlRules)) {
                $app->getUrlManager()->addRules($this->urlRules, true);
            }
        }
        Yii::setAlias('@uploads', $this->directory);

        return null;
    }

    /**
     * Protected method to register events defined in config
     *
     * @return null
     */
    protected function prepareEvents()
    {
        if (!empty($this->events)) {
            foreach ($this->events as $event) {
                if (isset($event['class']) && isset($event['event'])
                    && isset($event['callback']) && is_callable($event['callback'])
                ) {
                    Event::on($event['class'], $event['event'], $event['callback']);
                }
            }
        }

        return null;
    }

    /**
     * Protected method to add container definition from the config file
     *
     * @return null
     */
    protected function prepareContainer()
    {
        if (!empty($this->container)) {
            if (isset($this->container['definitions'])) {
                $definitions = ArrayHelper::merge(Yii::$container->getDefinitions(), $this->container['definitions']);
                Yii::$container->setDefinitions($definitions);
            }
        }
        if (!empty($this->modelDirs)) {
            $pre = get_declared_classes();
            foreach ($this->modelDirs as &$dir) {
                $dir = Yii::getAlias($dir);
                if (is_dir($dir)) {
                    foreach (scandir($dir) as $item) {
                        if (!in_array($item, ['.', '..'])) {
                            $temp = $dir . DIRECTORY_SEPARATOR . $item;
                            if (is_file($temp) && strpos(file_get_contents($temp), 'class') !== false) {
                                include_once $temp;
                            }
                        }
                    }

                }
            }
            $diff = array_diff(get_declared_classes(), $pre);
            if (!empty($diff)) {
                foreach ($diff as $class) {
                    if (is_subclass_of($class, 'maniakalen\uploads\interfaces\FileUploadModel')) {
                        Yii::$container->set($class::downloadHash(), ['class' => $class]);
                    }
                }
            }
        }
        return null;
    }
}