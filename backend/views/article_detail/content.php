<style>
    .box1 {
        min-width: 1100px;
        margin: 0 auto;
    }

</style>
<div class="box">
    <div>
        <div><?= $model->content ?></div>
    </div>
        <a href="<?= \yii\helpers\Url::to(['article/index']) ?>" class="btn btn-info">返回列表</a>
    </div>


