<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStudentDetails extends AbstractMigration
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
        $table = $this->table('students');

        $table
            ->addColumn('status', 'string', [
                'limit' => 20,
                'default' => 'active',
                'null' => false
            ])
            ->addColumn('enrollment_date', 'date', [
                'null' => true
            ])
            ->addColumn('guardian_phone', 'string', [
                'limit' => 30,
                'null' => true
            ])
            ->update();
    }

}
