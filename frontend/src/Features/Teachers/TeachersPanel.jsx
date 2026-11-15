import { useState } from "react";
import { Alert, Chip, Snackbar, Stack } from "@mui/material";

import DataTable from "../../Components/DataTable";
import ConfirmDialog from "../../Components/ConfirmDialog";
import TeacherFormDialog from "./TeacherFormDialog";

import { useTeachers, useTeacherMutations } from "../../Hooks/useTeachers";
import { useSubjects } from "../../Hooks/useSubjects";

const columns = [
  { id: "id", label: "ID" },
  { id: "full_name", label: "Full Name" },
  { id: "email", label: "Email" },
  { id: "phone", label: "Phone No." },
  { id: "qualification", label: "Qualification" },
  { id: "hire_date", label: "Hired" },
  {
    id: "status",
    label: "Status",
    render: (teacher) => (
      <Chip
        label={teacher.status}
        color={teacher.status === "active" ? "success" : "default"}
        size="small"
      />
    ),
  },
  {
    id: "subjects",
    label: "Subjects",
    value: (teacher) => (teacher.subjects || []).map((s) => s.name).join(", "),
    render: (teacher) => (
      <Stack direction="row" gap={0.5} flexWrap="wrap">
        {(teacher.subjects || []).map((subject) => (
          <Chip
            key={subject.id}
            label={subject.name}
            size="small"
            variant="outlined"
          />
        ))}
      </Stack>
    ),
  },
];

const getErrorMessage = (error) =>
  error?.response?.data?.message || error?.message || "Something went wrong";

export default function TeachersPanel() {
  const { data: teachers = [], isPending, error } = useTeachers();
  console.log(teachers);
  const {
    data: subjects = [],
    isPending: subjectsLoading,
    error: subjectsError,
  } = useSubjects();

  const { saveTeacher, deleteTeacher } = useTeacherMutations();

  const [formOpen, setFormOpen] = useState(false);
  const [selectedTeacher, setSelectedTeacher] = useState(null);
  const [deleteTarget, setDeleteTarget] = useState(null);

  const [formError, setFormError] = useState("");
  const [deleteError, setDeleteError] = useState("");
  const [notice, setNotice] = useState("");

  const handleAdd = () => {
    setSelectedTeacher(null);
    setFormError("");
    setFormOpen(true);
  };

  const handleEdit = (teacher) => {
    setSelectedTeacher(teacher);
    setFormError("");
    setFormOpen(true);
  };

  const handleSave = async (data) => {
    setFormError("");

    try {
      await saveTeacher.mutateAsync({
        id: selectedTeacher?.id,
        data,
      });

      setFormOpen(false);
      setNotice(
        selectedTeacher
          ? "Teacher updated successfully"
          : "Teacher added successfully",
      );
    } catch (error) {
      setFormError(getErrorMessage(error));
    }
  };

  const handleDelete = (teacher) => {
    setDeleteError("");
    setDeleteTarget(teacher);
  };

  const confirmDelete = async () => {
    try {
      await deleteTeacher.mutateAsync(deleteTarget.id);
      setDeleteTarget(null);
      setNotice("Teacher deleted successfully");
    } catch (error) {
      setDeleteError(getErrorMessage(error));
    }
  };

  return (
    <>
      <DataTable
        rows={Array.isArray(teachers) ? teachers : []}
        columns={columns}
        loading={isPending}
        error={error && getErrorMessage(error)}
        emptyMessage="No teachers found"
        addLabel="Add Teacher"
        onAdd={handleAdd}
        onEdit={handleEdit}
        onDelete={handleDelete}
      />

      {formOpen && (
        <TeacherFormDialog
          open={formOpen}
          teacher={selectedTeacher}
          subjects={Array.isArray(subjects) ? subjects : []}
          subjectsLoading={subjectsLoading}
          subjectsError={subjectsError}
          loading={saveTeacher.isPending}
          error={formError}
          onClose={() => setFormOpen(false)}
          onSave={handleSave}
        />
      )}

      <ConfirmDialog
        open={Boolean(deleteTarget)}
        title="Delete Teacher"
        message={`Are you sure you want to delete ${
          deleteTarget?.full_name || "this teacher"
        }? This action cannot be undone.`}
        loading={deleteTeacher.isPending}
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
