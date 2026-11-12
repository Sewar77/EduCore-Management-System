<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSubjectsTable extends AbstractMigration
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
        $table = $this->table('subjects');

        $table
            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false
            ])
            ->addColumn('code', 'string', [
                'limit' => 20,
                'null' => true
            ])
            ->addColumn('description', 'text', [
                'null' => true
            ])
            ->addTimestamps()
            ->addIndex(['name'], [
                'unique' => true
            ])
            ->addIndex(['code'], [
                'unique' => true
            ])
            ->create();
    }
}
