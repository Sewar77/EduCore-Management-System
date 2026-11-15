import { useState } from "react";
import { Alert, Snackbar } from "@mui/material";

import DataTable from "../../Components/DataTable";
import ConfirmDialog from "../../Components/ConfirmDialog";
import SubjectFormDialog from "./SubjectFormDialog";

import { useSubjects, useSubjectMutations } from "../../Hooks/useSubjects";

const columns = [
  { id: "id", label: "ID" },
  { id: "name", label: "Subject Name" },
  { id: "code", label: "Code" },
  { id: "description", label: "Description" },
];

const getErrorMessage = (error) => {
  const response = error?.response?.data;
  const details = response?.errors
    ? Object.values(response.errors).flat().join(", ")
    : "";

  return details || response?.message || error?.message || "Request failed";
};

export default function SubjectsPanel() {
  const { data: subjects = [], isPending, error } = useSubjects();
  const { saveSubject, deleteSubject } = useSubjectMutations();

  const [formOpen, setFormOpen] = useState(false);
  const [selectedSubject, setSelectedSubject] = useState(null);
  const [deleteTarget, setDeleteTarget] = useState(null);

  const [formError, setFormError] = useState("");
  const [deleteError, setDeleteError] = useState("");
  const [notice, setNotice] = useState("");

  const handleAdd = () => {
    setSelectedSubject(null);
    setFormError("");
    setFormOpen(true);
  };

  const handleEdit = (subject) => {
    setSelectedSubject(subject);
    setFormError("");
    setFormOpen(true);
  };

  const handleSave = async (data) => {
    setFormError("");

    try {
      await saveSubject.mutateAsync({
        id: selectedSubject?.id,
        data,
      });

      setFormOpen(false);
      setNotice(
        selectedSubject
          ? "Subject updated successfully"
          : "Subject added successfully",
      );
    } catch (error) {
      setFormError(getErrorMessage(error));
    }
  };

  const handleDelete = (subject) => {
    setDeleteError("");
    setDeleteTarget(subject);
  };

  const confirmDelete = async () => {
    if (!deleteTarget) return;

    setDeleteError("");

    try {
      await deleteSubject.mutateAsync(deleteTarget.id);
      setDeleteTarget(null);
      setNotice("Subject deleted successfully");
    } catch (error) {
      setDeleteError(getErrorMessage(error));
    }
  };

  return (
    <>
      <DataTable
        rows={Array.isArray(subjects) ? subjects : []}
        columns={columns}
        loading={isPending}
        error={error ? getErrorMessage(error) : null}
        emptyMessage="No subjects found"
        addLabel="Add Subject"
        onAdd={handleAdd}
        onEdit={handleEdit}
        onDelete={handleDelete}
      />

      {formOpen && (
        <SubjectFormDialog
          key={selectedSubject?.id ?? "new"}
          open={formOpen}
          subject={selectedSubject}
          loading={saveSubject.isPending}
          error={formError}
          onClose={() => setFormOpen(false)}
          onSave={handleSave}
        />
      )}

      <ConfirmDialog
        open={Boolean(deleteTarget)}
        title="Delete Subject"
        message={`Are you sure you want to delete ${
          deleteTarget?.name || "this subject"
        }? This action cannot be undone.`}
        loading={deleteSubject.isPending}
        error={deleteError}
        onClose={() => setDeleteTarget(null)}
        onConfirm={confirmDelete}
      />

      <Snackbar
        open={Boolean(notice)}
        autoHideDuration={3500}
        onClose={() => setNotice("")}
        anchorOrigin={{ vertical: "bottom", horizontal: "right" }}
      >
        <Alert
          severity="success"
          variant="filled"
          onClose={() => setNotice("")}
        >
          {notice}
        </Alert>
      </Snackbar>
    </>
  );
}
