import { useMemo } from "react";
import DataTable from "../../Components/DataTable";
import { useLogs } from "../../Hooks/useLogs";
export default function LogsPanel() {
  const { data: logs = [], isPending, error } = useLogs();
  const columns = useMemo(
    () => [
      { id: "id", label: "ID" },
      { id: "action_type", label: "Action" },
      { id: "entity_type", label: "On" },
      { id: "description", label: "Description" },
      { id: "record_id", label: "Record ID" },
      { id: "created_at", label: "Time" },
    ],
    [],
  );

  return (
    <DataTable
      rows={logs}
      columns={columns}
      loading={isPending}
      error={error?.response?.data?.message || error?.message}
      emptyMessage="No Logs found"
    />
  );
}
