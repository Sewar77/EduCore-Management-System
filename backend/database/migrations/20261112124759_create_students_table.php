<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStudentsTable extends AbstractMigration
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
        $table = $this->table('teachers');

        $table
            ->addColumn('full_name', 'string', [
                'limit' => 120,
                'null' => false
            ])
            ->addColumn('email', 'string', [
                'limit' => 254,
                'null' => false
            ])
            ->addColumn('phone', 'string', [
                'limit' => 30,
                'null' => false
            ])
            ->addColumn('status', 'string', [
                'limit' => 20,
                'default' => 'active',
                'null' => false
            ])
            ->addColumn('qualification', 'string', [
                'limit' => 150,
                'null' => true
            ])
            ->addColumn('hire_date', 'date', [
                'null' => true
            ])
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }

}
