import { useQuery, UseQueryOptions, UseQueryResult } from "@tanstack/react-query";
import { ApiError, ApiResult } from "../../common/common.model";
import { MeModel } from "./model";
import { API_LIST, callApi } from "../../common/utils/client";

export function getProfile(
    props?: {
        options?: UseQueryOptions<ApiResult<MeModel>, ApiError>;
    }
): UseQueryResult<ApiResult<MeModel>, ApiError> {
    return useQuery({
        queryKey: ['get-me'],
        queryFn: () => callApi({ url: `${API_LIST.Auth}/profile`}, MeModel),
        staleTime: Infinity,
        cacheTime: Infinity,
        ...props?.options,
    });
}