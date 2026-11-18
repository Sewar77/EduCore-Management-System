import { useState } from "react";
import { Alert, Snackbar } from "@mui/material";

import DataTable from "../../Components/DataTable";
import SubjectFormDialog from "./SubjectFormDialog";

import { useSubjects, useSubjectMutations } from "../../Hooks/useSubjects";

const columns = [
  { id: "id", label: "ID" },
  { id: "name", label: "Subject Name" },
  { id: "code", label: "Code" },
  { id: "description", label: "Description" },
];

export default function SubjectsPanel() {
  const { data: subjects = [], isPending, error } = useSubjects();
  const { saveSubject } = useSubjectMutations();

  const [formOpen, setFormOpen] = useState(false);
  const [formError, setFormError] = useState("");
  const [success, setSuccess] = useState(false);

  const handleAdd = () => {
    setFormError("");
    setFormOpen(true);
  };

  const handleSave = async (data) => {
    setFormError("");

    try {
      await saveSubject.mutateAsync({ id: null, data });
      setFormOpen(false);
      setSuccess(true);
    } catch (error) {
      setFormError(
        error.response?.data?.message ||
          error.message ||
          "Failed to add subject",
      );
    }
  };

  return (
    <>
      <DataTable
        rows={Array.isArray(subjects) ? subjects : []}
        columns={columns}
        loading={isPending}
        error={error?.response?.data?.message || error?.message}
        emptyMessage="No subjects found"
        addLabel="Add Subject"
        onAdd={handleAdd}
      />

      {formOpen && (
        <SubjectFormDialog
          open={formOpen}
          subject={null}
          loading={saveSubject.isPending}
          error={formError}
          onClose={() => setFormOpen(false)}
          onSave={handleSave}
        />
      )}

      <Snackbar
        open={success}
        autoHideDuration={3000}
        onClose={() => setSuccess(false)}
        anchorOrigin={{
          vertical: "bottom",
          horizontal: "right",
        }}
      >
        <Alert severity="success" variant="filled">
          Subject added successfully
        </Alert>
      </Snackbar>
    </>
  );
}
