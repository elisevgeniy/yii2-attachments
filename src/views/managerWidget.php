<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
use nemmo\attachments\components\AttachmentsInput;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var nemmo\attachments\Module $module */
/** @var yii\db\ActiveRecord $model */
/** @var boolean $editorMode */
/** @var boolean $listView */
/** @var string $searchLabel */
/** @var string $downloadLabel */
/** @var string $deleteLabel */

?>
<div class="file-manager-application">
    <div class="content-area-wrapper">
        <div class="content-body">
            <div class="file-manager-main-content">

                <!-- search area start -->
                <div class="file-manager-content-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
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
                            <a href="<?=Url::to(['/attachments/file/download-multiple'])?>" class="font-medium-2 d-sm-inline-block d-none mr-50 download-multiple-button" title="<?=$downloadLabel?>"><i data-feather="download"></i></a>
                            <a href="<?=Url::to(['/attachments/file/delete-multiple'])?>" class="font-medium-2 d-sm-inline-block d-none mr-50 delete-multiple-button" title="<?=$deleteLabel?>"><i data-feather="trash"></i></a>
                            <div class="dropdown d-inline-block d-sm-none">
                                <i class="font-medium-2 cursor-pointer" data-feather="more-vertical" role="button" id="fileActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="fileActions">
                                    <a class="dropdown-item download-multiple-button" href="<?=Url::to(['/attachments/file/download-multiple'])?>">
                                        <i data-feather="download" class="mr-50"></i>
                                        <span class="align-middle"><?=$downloadLabel?></span>
                                    </a>
                                    <a class="dropdown-item delete-multiple-button" href="<?=Url::to(['/attachments/file/delete-multiple'])?>">
                                        <i data-feather="trash" class="mr-50"></i>
                                        <span class="align-middle"><?=$deleteLabel?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group btn-group-toggle view-toggle ml-50" data-toggle="buttons">
                            <label class="btn btn-outline-primary p-50 btn-sm <?=$listView? '' : 'active'?>">
                                <input type="radio" name="view-btn-radio" data-view="grid" <?=$listView? '' : 'checked'?> />
                                <i data-feather="grid"></i>
                            </label>
                            <label class="btn btn-outline-primary p-50 btn-sm <?=!$listView? '' : 'active'?>">
                                <input type="radio" name="view-btn-radio" data-view="list" <?=!$listView? '' : 'checked'?> />
                                <i data-feather="list"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- search area ends here -->

                <div class="file-manager-content-body">
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
<?php if ($editorMode) : ?>
    <?= AttachmentsInput::widget([
        'id' => 'file-input', // Optional
        'model' => $model,
        'options' => [ // Options of the Kartik's FileInput widget
            'multiple' => true, // If you want to allow multiple upload, default to false
        ],
        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
            'initialPreview' => false, //Per no mostrar els fitxers ja pujats
            'showUpload' => false, // hide upload button
            'fileActionSettings' => ['showUpload' => false],
            'maxFileCount' => ArrayHelper::getValue($module->rules, 'maxFiles', 3), // Client max files
            'maxFileSize' => ArrayHelper::getValue($module->rules, 'maxSize', 0), // File max file size
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
<?php endif; ?>