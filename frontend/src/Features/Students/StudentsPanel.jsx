import { useMemo } from "react";
import DataTable from "../../Components/DataTable";
export default function StudentsPanel() {
  // Temporary data until we connect the API
  const students = [
    {
      id: 1,
      full_name: "Ahmad Ali",
      email: "ahmad@example.com",
      age: 18,
      grade_class: "12",
    },
    {
      id: 2,
      full_name: "Sara Omar",
      email: "sara@example.com",
      age: 17,
      grade_class: "11",
    },
  ];

  const columns = useMemo(
    () => [
      { id: "id", label: "ID" },
      { id: "full_name", label: "Full Name" },
      { id: "email", label: "Email" },
      { id: "age", label: "Age" },
      { id: "grade_class", label: "Grade" },
    ],
    [],
  );

  return <DataTable rows={students} columns={columns} />;
}
