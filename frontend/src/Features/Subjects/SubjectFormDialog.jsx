import { useState } from "react";
import { Alert, Stack, TextField } from "@mui/material";
import FormDialog from "../../Components/FormDialog";

export default function SubjectFormDialog({
  open,
  subject,
  onClose,
  onSave,
  loading,
  error,
}) {
  const [form, setForm] = useState({
    name: subject?.name || "",
    code: subject?.code || "",
    description: subject?.description || "",
  });

  const change = (field, value) => {
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    onSave({
      name: form.name.trim(),
      code: form.code.trim() || null,
      description: form.description.trim() || null,
    });
  };

  return (
    <FormDialog
      open={open}
      title={subject ? "Edit Subject" : "Add Subject"}
      onClose={onClose}
      onSubmit={handleSubmit}
      loading={loading}
    >
      <Stack spacing={2} sx={{ pt: 1 }}>
        {error && <Alert severity="error">{error}</Alert>}

        <TextField
          label="Subject Name"
          value={form.name}
          onChange={(e) => change("name", e.target.value)}
          required
          fullWidth
          slotProps={{
            htmlInput: { maxLength: 100 },
          }}
        />

        <TextField
          label="Code"
          value={form.code}
          onChange={(e) => change("code", e.target.value)}
          fullWidth
          helperText="Optional. Example: MATH101"
          slotProps={{
            htmlInput: { maxLength: 20 },
          }}
        />

        <TextField
          label="Description"
          value={form.description}
          onChange={(e) => change("description", e.target.value)}
          multiline
          rows={3}
          fullWidth
        />
      </Stack>
    </FormDialog>
  );
}
