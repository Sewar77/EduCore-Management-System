import { useState } from "react";
import { Alert, Stack, TextField } from "@mui/material";
import FormDialog from "../../Components/FormDialog";

export default function StudentFormDialog({
  open,
  student,
  onClose,
  onSave,
  loading,
  error,
}) {
  const [form, setForm] = useState({
    fullName: student?.full_name || "",
    email: student?.email || "",
    phone: student?.phone || "",
    age: student?.age ?? "",
    gradeClass: student?.grade_class || "",
  });

  const change = (field, value) => {
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const age = Number(form.age);

    if (!Number.isInteger(age) || age < 1 || age > 120) {
      return;
    }

    onSave({
      fullName: form.fullName.trim(),
      email: form.email.trim(),
      phone: form.phone.trim() || null,
      age,
      gradeClass: form.gradeClass.trim(),
    });
  };

  return (
    <FormDialog
      open={open}
      title={student ? "Edit Student" : "Add Student"}
      onClose={onClose}
      onSubmit={handleSubmit}
      loading={loading}
    >
      <Stack spacing={2} sx={{ pt: 1 }}>
        {error && <Alert severity="error">{error}</Alert>}

        <TextField
          label="Full Name"
          value={form.fullName}
          onChange={(e) => change("fullName", e.target.value)}
          required
          fullWidth
          inputProps={{ maxLength: 120, minLength: 2 }}
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
          fullWidth
          slotProps={{ htmlInput: { maxLength: 30 } }}
        />

        <TextField
          label="Age"
          type="number"
          value={form.age}
          onChange={(e) => change("age", e.target.value)}
          required
          fullWidth
          slotProps={{
            htmlInput: { min: 1, max: 120, step: 1 },
          }}
        />

        <TextField
          label="Grade / Class"
          value={form.gradeClass}
          onChange={(e) => change("gradeClass", e.target.value)}
          required
          fullWidth
          slotProps={{ htmlInput: { maxLength: 50 } }}
        />
      </Stack>
    </FormDialog>
  );
}
