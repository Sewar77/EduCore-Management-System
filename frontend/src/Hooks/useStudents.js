
import {
    useQuery,
    useMutation,
    useQueryClient,
} from "@tanstack/react-query";

import { studentsApi } from "../Features/Students/StudentsApi";

export const useStudents = () =>
    useQuery({
        queryKey: ["students"],
        queryFn: ({ signal }) => studentsApi.getAll(signal),
    });

export const useStudentMutations = () => {
    const queryClient = useQueryClient();

    const refresh = () => {
        queryClient.invalidateQueries({ queryKey: ["students"] });
        queryClient.invalidateQueries({ queryKey: ["logs"] });
    };

    const saveStudent = useMutation({
        mutationFn: ({ id, data }) =>
            id == null
                ? studentsApi.create(data)
                : studentsApi.update(id, data),
        onSuccess: refresh,
    });

    const deleteStudent = useMutation({
        mutationFn: studentsApi.delete,
        onSuccess: refresh,
    });

    return { saveStudent, deleteStudent };
};
