<?php
use yii\db\Migration;

/**
 * Handles the creation for table `account_table`.
 */
class m160508_112614_create_accounting_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $accountTable = "account";
        $transactionTable = "transaction";
        $transactionPartTable = "transaction_part";
        
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }        
        
        $this->createTable('{{%' . $accountTable . '}}', [
            'account_id' => $this->primaryKey(),
            'accounttype' => $this->char(1)->notNull(),
            'name' => $this->string(128)->notNull(),
            'balance_debit' => $this->money(16,4)->notNull()->defaultValue(0),
            'balance_credit' => $this->money(16,4)->notNull()->defaultValue(0),
            'balance' => $this->money(16,4)->notNull()->defaultValue(0),
        ], $tableOptions);
        
        $this->createTable('{{%' . $transactionTable . '}}', [
            'transaction_id' => $this->primaryKey(),
            'description' => $this->text()->notNull(),
            'date' => $this->date()->notNull(),
            'value' => $this->money(16,4)->notNull(),
            'hash' => $this->string(128)->notNull(),
        ], $tableOptions);   
        
        $this->createTable('{{%' . $transactionPartTable . '}}', [
            'transactionpart_id' => $this->primaryKey(),
            'transaction_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'accountside' => $this->char(1)->notNull(),
            'value' => $this->money(16,4)->notNull(),
            'description' => $this->text(),
        ], $tableOptions);
        
        
        $this->createIndex(
            'idx-' . \Yii::$app->db->tablePrefix . $transactionPartTable . '-transaction_id',
            '{{%' . $transactionPartTable . '}}',
            'transaction_id'
        );

        if ($this->db->driverName !== 'sqlite') {
            $this->addForeignKey(
                'fk-' . \Yii::$app->db->tablePrefix . $transactionPartTable . '-transaction_id',
                '{{%' . $transactionPartTable . '}}',
                'transaction_id',
                '{{%' . $transactionTable . '}}',
                'transaction_id',
                'CASCADE'
            );
        }
        
        $this->createIndex(
            'idx-' . \Yii::$app->db->tablePrefix . $transactionPartTable . '-account_id',
            '{{%' . $transactionPartTable . '}}',
            'account_id'
        );

        if ($this->db->driverName !== 'sqlite') {
            $this->addForeignKey(
                'fk-' . \Yii::$app->db->tablePrefix . $transactionPartTable . '-account_id',
                '{{%' . $transactionPartTable . '}}',
                'account_id',
                '{{%' . $accountTable . '}}',
                'account_id',
                'CASCADE'
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return false;
    }
}
