<?php

use yii\widgets\ListView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var yii\db\ActiveRecord $model */
/** @var boolean $editorMode */
/** @var boolean $listView */

?>
<div class="file-manager-application">
    <div class="content-area-wrapper">
        <div class="content-body">
            <div class="file-manager-main-content">
                <div class="file-manager-content-body ps">
                    <?= ListView::widget([
                        'layout' => "{items}",
                        'itemOptions' => ['tag' => false],
                        'options' => ['class' => 'view-container' . ($listView? ' list-view' : '')],
                        'dataProvider' => new ArrayDataProvider(['allModels' => $model->getFiles()]),
                        'itemView' => '@vendor/smateu/yii2-attachments/src/views/_attachment.php',
                        'viewParams' => ['editorMode' => $editorMode]
                        /*
                        'layout' => '{items}',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn'
                            ],
                            [
                                'label' => $this->getModule()->t('attachments', 'File name'),
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a("$model->name.$model->type", $model->getUrl());
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'visibleButtons' => ['delete' => $this->showDeleteButton],
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                            '#',
                                            [
                                                'class' => 'delete-button',
                                                'title' => Yii::t('yii', 'Delete'),
                                                'data-url' => Url::to(['/attachments/file/delete', 'id' => $model->id])
                                            ]
                                        );
                                    }
                                ]
                            ],
                        ]*/
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>