<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_020550_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string('80')->comment('收货人姓名'),
            'city'=>$this->string('100')->comment('地址'),
            'city_intro'=>$this->string('200')->comment('地址详情'),
            'tel'=>$this->smallInteger(11)->comment('联系电话'),
            'default_address'=>$this->smallInteger(2)->comment('默认收货地址  1是  0不是')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
