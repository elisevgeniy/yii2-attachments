<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
use nemmo\attachments\components\AttachmentsInput;

/** @var yii\web\View $this */
/** @var yii\db\ActiveRecord $model */
/** @var boolean $editorMode */
/** @var boolean $listView */
/** @var string $searchLabel */

?>
<div class="file-manager-application">
    <div class="content-area-wrapper">
        <div class="content-body">
            <div class="file-manager-main-content">

                <!-- search area start -->
                <div class="file-manager-content-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="sidebar-toggle d-block d-xl-none float-left align-middle ml-1">
                            <i data-feather="menu" class="font-medium-5"></i>
                        </div>
                        <div class="input-group input-group-merge shadow-none m-0 flex-grow-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-0">
                                    <i data-feather="search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control files-filter border-0 bg-transparent" placeholder="<?=$searchLabel?>" />
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="file-actions">
                            <a href="<?=Url::to(['/attachments/file/download-multiple'])?>" class="font-medium-2 d-sm-inline-block d-none mr-50 download-multiple-button"><i data-feather="arrow-down-circle"></i></a>
                            <a href="<?=Url::to(['/attachments/file/delete-multiple'])?>" class="font-medium-2 d-sm-inline-block d-none mr-50 delete-multiple-button"><i data-feather="trash"></i></a>
                        </div>
                        <div class="btn-group btn-group-toggle view-toggle ml-50" data-toggle="buttons">
                            <label class="btn btn-outline-primary p-50 btn-sm active">
                                <input type="radio" name="view-btn-radio" data-view="grid" checked />
                                <i data-feather="grid"></i>
                            </label>
                            <label class="btn btn-outline-primary p-50 btn-sm">
                                <input type="radio" name="view-btn-radio" data-view="list" />
                                <i data-feather="list"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- search area ends here -->

                <div class="file-manager-content-body ps">
                    <?= ListView::widget([
                        'layout' => "{items}",
                        'itemOptions' => ['tag' => false],
                        'options' => ['class' => 'view-container' . ($listView? ' list-view' : '')],
                        'dataProvider' => new ArrayDataProvider(['allModels' => $model->getFiles()]),
                        'itemView' => '@vendor/smateu/yii2-attachments/src/views/_attachment.php',
                        'viewParams' => ['editorMode' => $editorMode]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= AttachmentsInput::widget([
    'id' => 'file-input', // Optional
    'model' => $model,
    'options' => [ // Options of the Kartik's FileInput widget
        'multiple' => true, // If you want to allow multiple upload, default to false
    ],
    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
        'initialPreview' => false, //Per no mostrar els fitxers ja pujats
        'showUpload' => false, // hide upload button
        'maxFileCount' => 10, // Client max files
        'theme'=>'explorer-fas',
        'preferIconicPreview' => true, // this will force thumbnails to display icons for following file extensions
        'previewFileIconSettings' => [ // configure your icon file extensions
            'doc' => '<i class="fas fa-file-word text-primary"></i>',
            'xls' => '<i class="fas fa-file-excel text-success"></i>',
            'ppt' => '<i class="fas fa-file-powerpoint text-danger"></i>',
            'pdf' => '<i class="fas fa-file-pdf text-danger"></i>',
            'zip' => '<i class="fas fa-file-archive text-muted"></i>',
            'htm' => '<i class="fas fa-file-code text-info"></i>',
            'txt' => '<i class="fas fa-file-text text-info"></i>',
            'mov' => '<i class="fas fa-file-video text-warning"></i>',
            'mp3' => '<i class="fas fa-file-audio text-warning"></i>',
            // note for these file types below no extension determination logic
            // has been configured (the keys itself will be used as extensions)
            'jpg' => '<i class="fas fa-file-image text-danger"></i>',
            'gif' => '<i class="fas fa-file-image text-muted"></i>',
            'png' => '<i class="fas fa-file-image text-primary"></i>'
        ],
    ]
]) ?>