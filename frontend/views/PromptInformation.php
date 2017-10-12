<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->title ="$info";
?>
<div class="container">
    <div class="hint-main hint-error">
        <h1></h1>
        <h2>提示:</h2>
        <h3><?= nl2br(Html::encode($info)) ?></h3>
        <?php
        $str='';
        if(!empty($url)){
            $str.='<h4>You can select:';
            foreach($url as $item){
                $str.="<a href='".$item[1]."'>".$item[0]."</a>";
            }
            if($jumpSeconds>=0){

                $str.="<BR><BR>".Yii::t('app','After ').$jumpSeconds.Yii::t('app',' seconds').Yii::t('app',' will jump to')." '<a href='".$url[0][1]."'>".$url[0][0]."</a>'<meta http-equiv=refresh content=".$jumpSeconds.";url=".$url[0][1].">";
            }
            $str.='</h4>';
        }
        echo $str;
        ?>
    </div>
</div>