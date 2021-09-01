<?php

/**
 *
 */

namespace nemmo\attachments\assets;

use yii\web\AssetBundle;

/**
 * AttachmentsManagerAsset bundle for \nemmo\attachments<ºcomponents\AttachmentsManager.
 *
 * @author Sergi Mateu <sergi.mateu@gmail.com>
 * @since 1.0
 */
class AttachmentsManagerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/smateu/yii2-attachments/src/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/app-file-manager.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/app-file-manager.js'
    ];

}
