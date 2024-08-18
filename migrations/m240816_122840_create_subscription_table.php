<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription}}`.
 */
class m240816_122840_create_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'phone' => $this->string(15)->null()->comment('Phone number for guest subscriptions')
        ]);
        
        $this->addForeignKey('fk-subscription-user', '{{%subscription}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-subscription-author', '{{%subscription}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-user', '{{%subscription}}');
        $this->dropForeignKey('fk-subscription-author', '{{%subscription}}');
        $this->dropTable('{{%subscription}}');
    }
}
