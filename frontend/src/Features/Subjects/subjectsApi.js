import api from "../../api";

export const subjectsApi = {
    getAll: (signal) => api.get("/subjects", { signal }),
    create: (data) => api.post('/subjects', data),
    update: (id, data) => api.patch(`/subjects/${id}`, data),
    delete: (id) => api.delete(`/subjects/${id}`)
}