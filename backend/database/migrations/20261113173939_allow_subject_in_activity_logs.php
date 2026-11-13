<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AllowSubjectInActivityLogs extends AbstractMigration
{
    public function up(): void
    {
        // Find the actual database type of entity_type
        $row = $this->fetchRow("
            SELECT
                format('%I.%I', ns.nspname, t.typname)
                    AS enum_name,
                t.typtype
            FROM pg_attribute a
            JOIN pg_class c ON c.oid = a.attrelid
            JOIN pg_type t ON t.oid = a.atttypid
            JOIN pg_namespace ns ON ns.oid = t.typnamespace
            WHERE c.oid = 'public.activity_logs'::regclass
              AND a.attname = 'entity_type'
              AND a.attnum > 0
              AND NOT a.attisdropped
        ");

        if ($row === false || $row['typtype'] !== 'e') {
            throw new RuntimeException(
                'activity_logs.entity_type is not a PostgreSQL enum'
            );
        }

        $enumName = $row['enum_name'];

        $this->execute(
            "ALTER TYPE {$enumName}
             ADD VALUE IF NOT EXISTS 'SUBJECT'"
        );
    }

    public function down(): void
    {
        throw new RuntimeException(
            'Removing an enum value requires a separate migration'
        );
    }
}
