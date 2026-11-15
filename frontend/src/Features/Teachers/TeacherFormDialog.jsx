import { useState } from "react";
import { Alert, Autocomplete, MenuItem, Stack, TextField } from "@mui/material";

import FormDialog from "../../Components/FormDialog";

export default function TeacherFormDialog({
  open,
  teacher,
  subjects = [],
  subjectsLoading = false,
  subjectsError,
  onClose,
  onSave,
  loading,
  error,
}) {
  const [form, setForm] = useState({
    fullName: teacher?.full_name || "",
    email: teacher?.email || "",
    phone: teacher?.phone || "",
    qualification: teacher?.qualification || "",
    hireDate: teacher?.hire_date?.slice(0, 10) || "",
    status: teacher?.status || "active",
    subjectIds: teacher?.subjects?.map((s) => s.id) || [],
  });

  const change = (name, value) => {
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (event) => {
    event.preventDefault();

    onSave({
      fullName: form.fullName.trim(),
      email: form.email.trim(),
      phone: form.phone.trim(),
      qualification: form.qualification.trim(),
      hireDate: form.hireDate || null,
      status: form.status,
      subjectIds: form.subjectIds,
    });
  };

  return (
    <FormDialog
      open={open}
      title={teacher ? "Edit Teacher" : "Add Teacher"}
      onClose={onClose}
      onSubmit={handleSubmit}
      loading={loading}
    >
      <Stack spacing={2} sx={{ pt: 1 }}>
        {error && <Alert severity="error">{error}</Alert>}
        {subjectsError && (
          <Alert severity="warning">
            Could not load subjects. Please try again.
          </Alert>
        )}

        <TextField
          label="Full Name"
          value={form.fullName}
          onChange={(e) => change("fullName", e.target.value)}
          required
          fullWidth
        />

        <TextField
          label="Email"
          type="email"
          value={form.email}
          onChange={(e) => change("email", e.target.value)}
          required
          fullWidth
        />

        <TextField
          label="Phone"
          value={form.phone}
          onChange={(e) => change("phone", e.target.value)}
          required
          fullWidth
        />

        <TextField
          label="Qualification"
          value={form.qualification}
          onChange={(e) => change("qualification", e.target.value)}
          fullWidth
        />

        <TextField
          label="Hire Date"
          type="date"
          value={form.hireDate}
          onChange={(e) => change("hireDate", e.target.value)}
          slotProps={{ inputLabel: { shrink: true } }}
          fullWidth
        />

        <TextField
          select
          label="Status"
          value={form.status}
          onChange={(e) => change("status", e.target.value)}
          fullWidth
        >
          <MenuItem value="active">Active</MenuItem>
          <MenuItem value="inactive">Inactive</MenuItem>
        </TextField>

        <Autocomplete
          multiple
          options={subjects}
          loading={subjectsLoading}
          disabled={subjectsLoading || !!subjectsError}
          getOptionLabel={(option) => option.name}
          isOptionEqualToValue={(option, value) => option.id === value.id}
          value={subjects.filter((subject) =>
            form.subjectIds.includes(subject.id),
          )}
          onChange={(_, selected) =>
            change(
              "subjectIds",
              selected.map((subject) => subject.id),
            )
          }
          renderInput={(params) => (
            <TextField
              {...params}
              label="Subjects"
              placeholder="Select subjects"
            />
          )}
        />
      </Stack>
    </FormDialog>
  );
}
