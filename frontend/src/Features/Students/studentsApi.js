import api from "../../api";

export const studentsApi = {
    getAll: () => api.get("/students"),
    create: (data) => api.post("/students", data),
    update: (id, data) => api.patch(`/students/${id}`, data),
    delete: (id) => api.delete(`/students/${id}`),
};