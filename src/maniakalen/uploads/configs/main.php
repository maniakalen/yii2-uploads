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

return [
    'urlRules' => [
        'uploads/download/<model>/<id>/<attr>/<idx>' => 'maniakalen/downloads/file',
    ],
    'container' => [
        'definitions' => [
            'yii\console\controllers\MigrateController' => [
                'class' => 'yii\console\controllers\MigrateController',
                'migrationPath' => [
                    '@uploadModule/migrations'
                ]
            ],
            'filesManager' => [
                'class' => 'maniakalen\uploads\UploadsManager'
            ]
        ]
    ]
];