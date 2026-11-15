import { useQuery } from "@tanstack/react-query";
import { logsApi } from "../Features/Logs/logsApi";

export const useLogs = () =>
    useQuery({
        queryKey: ["logs"],
        queryFn: logsApi.getAll,
    });