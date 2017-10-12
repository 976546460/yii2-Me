<?php

use yii\db\Migration;

class m170624_010945_create_cart_atble extends Migration
{
    public function up()
    {
        $this->createTable('cart',[
            'id'=>$this->primaryKey(),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'amount'=>$this->integer()->notNull()->comment('购买数量'),
            'member_id'=>$this->integer()->notNull()->comment('用户id')
        ]);
    }

    public function down()
    {
        echo "m170624_010945_create_cart_atble cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
