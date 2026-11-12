<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTeacherSubjectsTable extends AbstractMigration
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
        $table = $this->table('teacher_subjects', [
            'id' => false,
            'primary_key' => ['teacher_id', 'subject_id']
        ]);

        $table
            ->addColumn('teacher_id', 'integer', [
                'null' => false
            ])
            ->addColumn('subject_id', 'integer', [
                'null' => false
            ])
            ->addForeignKey(
                'teacher_id',
                'teachers',
                'id',
                ['delete' => 'CASCADE']
            )
            ->addForeignKey(
                'subject_id',
                'subjects',
                'id',
                ['delete' => 'RESTRICT']
            )
            ->create();
    }
}
