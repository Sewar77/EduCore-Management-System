<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateActivityLogsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('activity_logs');

        $table
            ->addColumn('action_type', 'enum', [
                'values' => ['ADD', 'UPDATE', 'DELETE']
            ])
            ->addColumn('entity_type', 'enum', [
                'values' => ['STUDENT', 'TEACHER']
            ])
            ->addColumn('record_id', 'integer', [
                'null' => false
            ])
            ->addColumn('description', 'text', [
                'null' => false
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->addIndex(['created_at'])
            ->create();
    }
}
