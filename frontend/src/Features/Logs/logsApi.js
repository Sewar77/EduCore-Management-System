import api from "../../api";

export const logsApi = {
    getAll: () => api.get("/logs"),
};