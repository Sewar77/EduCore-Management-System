
import { useMemo, useState } from "react";

export default function useTable(rows, columns) {
    const [search, setSearch] = useState("");
    const [page, setPage] = useState(0);
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [orderBy, setOrderBy] = useState(null);
    const [order, setOrder] = useState("asc");

    const filteredRows = useMemo(() => {
        const query = search.trim().toLowerCase();

        return rows.filter((row) =>
            columns.some((column) => {
                if (column.searchable === false) return false;

                const value = column.value
                    ? column.value(row)
                    : row[column.id];

                return String(value ?? "")
                    .toLowerCase()
                    .includes(query);
            })
        );
    }, [rows, columns, search]);

    const sortedRows = useMemo(() => {
        if (!orderBy) return filteredRows;

        const column = columns.find((col) => col.id === orderBy);
        if (!column) return filteredRows;

        return [...filteredRows].sort((a, b) => {
            const aValue = column.value
                ? column.value(a)
                : a[column.id];

            const bValue = column.value
                ? column.value(b)
                : b[column.id];

            const result =
                typeof aValue === "number" &&
                    typeof bValue === "number"
                    ? aValue - bValue
                    : String(aValue ?? "").localeCompare(
                        String(bValue ?? ""),
                        undefined,
                        { numeric: true, sensitivity: "base" }
                    );

            return order === "asc" ? result : -result;
        });
    }, [filteredRows, columns, orderBy, order]);

    const safePage = Math.min(
        page,
        Math.max(0, Math.ceil(sortedRows.length / rowsPerPage) - 1)
    );

    const paginatedRows = sortedRows.slice(
        safePage * rowsPerPage,
        (safePage + 1) * rowsPerPage
    );

    const handleSort = (columnId) => {
        if (orderBy === columnId) {
            setOrder((prev) => (prev === "asc" ? "desc" : "asc"));
        } else {
            setOrderBy(columnId);
            setOrder("asc");
        }

        setPage(0);
    };

    return {
        search,
        setSearch: (value) => {
            setSearch(value);
            setPage(0);
        },
        page: safePage,
        setPage,
        rowsPerPage,
        setRowsPerPage: (value) => {
            setRowsPerPage(value);
            setPage(0);
        },
        orderBy,
        order,
        handleSort,
        filteredCount: sortedRows.length,
        paginatedRows,
    };
}
