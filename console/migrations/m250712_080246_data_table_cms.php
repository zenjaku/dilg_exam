<?php

use yii\db\Migration;

class m250712_080246_data_table_cms extends Migration
{
    public $table = '{{%data_table}}';
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'age' => $this->tinyInteger()->unsigned()->notNull(),
            'contact_number' => $this->string('20')->notNull(),
            'status' => $this->string()->notNull(),
            'street' => $this->string()->notNull(),
            'barangay' => $this->string()->notNull(),
            'zipcode' => $this->string()->notNull(),
            'region_id' => $this->integer()->notNull(),
            'province_id' => $this->integer()->notNull(),
            'citymun_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

    }
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
