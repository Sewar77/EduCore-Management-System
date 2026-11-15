import api from "../../api";

export const teacherApi = {
    getAll: (signal) => api.get("teachers", { signal }),
    create: (data) => api.post('/teachers', data),
    update: (id, data) => api.patch(`/teachers/${id}`, data),
    delete: (id) => api.delete(`/teachers/${id}`)
}