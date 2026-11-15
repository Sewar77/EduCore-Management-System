import {
  Box,
  CircularProgress,
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TablePagination,
  TableRow,
  TableSortLabel,
  TextField,
  Typography,
} from "@mui/material";
import useTable from "../Hooks/useTable";

export default function DataTable({
  rows = [],
  columns,
  loading = false,
  error = null,
  emptyMessage = "No records found",
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

  return (
    <Paper variant="outlined" sx={{ borderRadius: 3, overflow: "hidden" }}>
      <Box sx={{ p: 2 }}>
        <TextField
          label="Search"
          size="small"
          value={search}
          onChange={(event) => setSearch(event.target.value)}
          sx={{ width: { xs: "100%", sm: 300 } }}
        />
      </Box>

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
            </TableRow>
          </TableHead>

          <TableBody>
            {loading || error || paginatedRows.length === 0 ? (
              <TableRow>
                <TableCell
                  colSpan={columns.length}
                  align="center"
                  sx={{ py: 6 }}
                >
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
                        : String(row[column.id] ?? "—")}
                    </TableCell>
                  ))}
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
        onRowsPerPageChange={(event) =>
          setRowsPerPage(Number(event.target.value))
        }
      />
    </Paper>
  );
}
