
import {
    useQuery,
    useMutation,
    useQueryClient,
} from "@tanstack/react-query";

import { subjectsApi } from "../Features/Subjects/subjectsApi";

export const useSubjects = () =>
    useQuery({
        queryKey: ["subjects"],
        queryFn: ({ signal }) => subjectsApi.getAll(signal),
    });

export const useSubjectMutations = () => {
    const queryClient = useQueryClient();

    const refresh = () => {
        queryClient.invalidateQueries({ queryKey: ["subjects"] });
        queryClient.invalidateQueries({ queryKey: ["teachers"] });
        queryClient.invalidateQueries({ queryKey: ["logs"] });
    };

    const saveSubject = useMutation({
        mutationFn: ({ id, data }) =>
            id == null
                ? subjectsApi.create(data)
                : subjectsApi.update(id, data),
        onSuccess: refresh,
    });

    const deleteSubject = useMutation({
        mutationFn: subjectsApi.delete,
        onSuccess: refresh,
    });

    return { saveSubject, deleteSubject };
};
