import {
  Box,
  Button,
  CircularProgress,
  IconButton,
  Paper,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TablePagination,
  TableRow,
  TableSortLabel,
  TextField,
  Tooltip,
  Typography,
} from "@mui/material";

import { AddOutlined, EditOutlined } from "@mui/icons-material";
import DeleteIcon from "@mui/icons-material/Delete";
import useTable from "../Hooks/useTable";

export default function DataTable({
  rows = [],
  columns = [],
  loading = false,
  error = null,
  emptyMessage = "No records found",
  addLabel = "Add New",
  onAdd,
  onEdit,
  onDelete,
}) {
  const {
    search,
    setSearch,
    page,
    setPage,
    rowsPerPage,
    setRowsPerPage,
    orderBy,
    order,
    handleSort,
    filteredCount,
    paginatedRows,
  } = useTable(rows, columns);

  const hasActions = Boolean(onEdit || onDelete);
  const colSpan = columns.length + (hasActions ? 1 : 0);

  return (
    <Paper variant="outlined" sx={{ borderRadius: 3, overflow: "hidden" }}>
      {/* Toolbar */}
      <Stack
        direction={{ xs: "column", sm: "row" }}
        sx={{
          gap: 2,
          p: 3,
          justifyContent: "space-between",
          alignItems: { xs: "stretch", sm: "center" },
        }}
      >
        <TextField
          label="Search"
          size="small"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          sx={{ width: { xs: "100%", sm: 300 } }}
        />

        {onAdd && (
          <Button
            variant="contained"
            startIcon={<AddOutlined />}
            onClick={onAdd}
          >
            {addLabel}
          </Button>
        )}
      </Stack>

      {/* Table */}
      <TableContainer>
        <Table>
          <TableHead sx={{ bgcolor: "grey.50" }}>
            <TableRow>
              {columns.map((column) => (
                <TableCell key={column.id}>
                  {column.sortable === false ? (
                    column.label
                  ) : (
                    <TableSortLabel
                      active={orderBy === column.id}
                      direction={orderBy === column.id ? order : "asc"}
                      onClick={() => handleSort(column.id)}
                    >
                      {column.label}
                    </TableSortLabel>
                  )}
                </TableCell>
              ))}

              {hasActions && <TableCell align="center">Actions</TableCell>}
            </TableRow>
          </TableHead>

          <TableBody>
            {loading || error || paginatedRows.length === 0 ? (
              <TableRow>
                <TableCell colSpan={colSpan} align="center" sx={{ py: 5 }}>
                  {loading ? (
                    <CircularProgress size={28} />
                  ) : (
                    <Typography color={error ? "error" : "text.secondary"}>
                      {error || emptyMessage}
                    </Typography>
                  )}
                </TableCell>
              </TableRow>
            ) : (
              paginatedRows.map((row) => (
                <TableRow hover key={row.id}>
                  {columns.map((column) => (
                    <TableCell key={column.id}>
                      {column.render
                        ? column.render(row)
                        : String(
                            column.value
                              ? (column.value(row) ?? "—")
                              : (row[column.id] ?? "—"),
                          )}
                    </TableCell>
                  ))}

                  {hasActions && (
                    <TableCell align="center">
                      <Stack direction="row" justifyContent="center">
                        {onEdit && (
                          <Tooltip title="Edit">
                            <IconButton
                              color="primary"
                              size="small"
                              aria-label="Edit record"
                              onClick={() => onEdit(row)}
                            >
                              <EditOutlined fontSize="small" />
                            </IconButton>
                          </Tooltip>
                        )}

                        {onDelete && (
                          <Tooltip title="Delete">
                            <IconButton
                              color="error"
                              size="small"
                              aria-label="Delete record"
                              onClick={() => onDelete(row)}
                            >
                              <DeleteIcon fontSize="small" />
                            </IconButton>
                          </Tooltip>
                        )}
                      </Stack>
                    </TableCell>
                  )}
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </TableContainer>

      <TablePagination
        component="div"
        count={filteredCount}
        page={page}
        rowsPerPage={rowsPerPage}
        rowsPerPageOptions={[5, 10, 25]}
        onPageChange={(_, newPage) => setPage(newPage)}
        onRowsPerPageChange={(e) => setRowsPerPage(Number(e.target.value))}
      />
    </Paper>
  );
}
