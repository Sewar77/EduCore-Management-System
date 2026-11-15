import { useState } from "react";
import { Alert, Snackbar } from "@mui/material";

import DataTable from "../../Components/DataTable";
import ConfirmDialog from "../../Components/ConfirmDialog";
import StudentFormDialog from "./StudentFormDialog";

import { useStudents, useStudentMutations } from "../../Hooks/useStudents";

const columns = [
  { id: "id", label: "ID" },
  { id: "full_name", label: "Full Name" },
  { id: "email", label: "Email" },
  { id: "phone", label: "Phone No." },
  { id: "age", label: "Age" },
  { id: "grade_class", label: "Grade / Class" },
];

const getErrorMessage = (error) => {
  const response = error?.response?.data;
  const details = response?.errors
    ? Object.values(response.errors).flat().join(", ")
    : "";

  return details || response?.message || error?.message || "Request failed";
};

export default function StudentsPanel() {
  const { data: students = [], isPending, error } = useStudents();
  const { saveStudent, deleteStudent } = useStudentMutations();

  const [formOpen, setFormOpen] = useState(false);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [deleteTarget, setDeleteTarget] = useState(null);

  const [formError, setFormError] = useState("");
  const [deleteError, setDeleteError] = useState("");
  const [notice, setNotice] = useState("");

  const handleAdd = () => {
    setSelectedStudent(null);
    setFormError("");
    setFormOpen(true);
  };

  const handleEdit = (student) => {
    setSelectedStudent(student);
    setFormError("");
    setFormOpen(true);
  };

  const handleSave = async (data) => {
    setFormError("");

    try {
      await saveStudent.mutateAsync({
        id: selectedStudent?.id,
        data,
      });

      setFormOpen(false);
      setNotice(
        selectedStudent
          ? "Student updated successfully"
          : "Student added successfully",
      );
    } catch (error) {
      setFormError(getErrorMessage(error));
    }
  };

  const handleDelete = (student) => {
    setDeleteError("");
    setDeleteTarget(student);
  };

  const confirmDelete = async () => {
    if (!deleteTarget) return;

    setDeleteError("");

    try {
      await deleteStudent.mutateAsync(deleteTarget.id);
      setDeleteTarget(null);
      setNotice("Student deleted successfully");
    } catch (error) {
      setDeleteError(getErrorMessage(error));
    }
  };

  return (
    <>
      <DataTable
        rows={Array.isArray(students) ? students : []}
        columns={columns}
        loading={isPending}
        error={error ? getErrorMessage(error) : null}
        emptyMessage="No students found"
        addLabel="Add Student"
        onAdd={handleAdd}
        onEdit={handleEdit}
        onDelete={handleDelete}
      />

      {formOpen && (
        <StudentFormDialog
          key={selectedStudent?.id ?? "new"}
          open={formOpen}
          student={selectedStudent}
          loading={saveStudent.isPending}
          error={formError}
          onClose={() => setFormOpen(false)}
          onSave={handleSave}
        />
      )}

      <ConfirmDialog
        open={Boolean(deleteTarget)}
        title="Delete Student"
        message={`Are you sure you want to delete ${
          deleteTarget?.full_name || "this student"
        }? This action cannot be undone.`}
        loading={deleteStudent.isPending}
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
