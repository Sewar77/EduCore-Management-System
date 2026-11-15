
import {
    useMutation,
    useQuery,
    useQueryClient,
} from "@tanstack/react-query";

import {teacherApi} from "../Features/Teachers/teachersApi"
export const useTeachers = () =>
    useQuery({
        queryKey: ["teachers"],
        queryFn: ({ signal }) => teacherApi.getAll(signal),
    });

export const useTeacherMutations = () => {
    const queryClient = useQueryClient();

    const refreshData = () => {
        queryClient.invalidateQueries({ queryKey: ["teachers"] });
        queryClient.invalidateQueries({ queryKey: ["logs"] });
    };

    const saveTeacher = useMutation({
        mutationFn: ({ id, data }) =>
            id == null
                ? teacherApi.create(data)
                : teacherApi.update(id, data),
        onSuccess: refreshData,
    });

    const deleteTeacher = useMutation({
        mutationFn: (id) => teacherApi.delete(id),
        onSuccess: refreshData,
    });

    return { saveTeacher, deleteTeacher };
};
