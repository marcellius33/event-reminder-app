import { useMutation, UseMutationOptions, UseMutationResult } from "@tanstack/react-query";
import { ApiError, ApiResult } from "../../common/common.model";
import { ChangePasswordInput, LoginInput, RegisterInput, TokenResultModel, UpdateProfileInput } from "./model";
import { API_LIST, callApi } from "../../common/utils/client";

export function useLogin(
    options?: UseMutationOptions<
      ApiResult<TokenResultModel>,
      ApiError,
      LoginInput
    >,
    ): UseMutationResult<ApiResult<TokenResultModel>, ApiError, LoginInput> {
    return useMutation<ApiResult<TokenResultModel>, ApiError, LoginInput>(
        async function (data) {
            return await callApi(
                {
                    url: `${API_LIST.Auth}/login`,
                    data,
                    method: 'POST',
                },
                TokenResultModel,
            );
        },
        options,
    );
}

export function useRegister(
    options?: UseMutationOptions<{ message: string }, ApiError, RegisterInput>
): UseMutationResult<{ message: string }, ApiError, RegisterInput> {
    return useMutation<{ message: string }, ApiError, RegisterInput>(
        async function (data) {
            return await callApi(
                {
                    url: `${API_LIST.Auth}/register`,
                    data,
                    method: 'POST',
                },
            );
        },
        options,
    );
}

export function useLogout(
    options?: UseMutationOptions<{ message: string }, ApiError, unknown>
): UseMutationResult<{ message: string }, ApiError, unknown> {
    return useMutation<{ message: string }, ApiError, unknown>(
        async function () {
            return await callApi(
                {
                    url: `${API_LIST.Auth}/logout`,
                    method: 'POST',
                },
            );
        },
        options,
    );
}

export function useChangePassword(
    options?: UseMutationOptions<{ message: string }, ApiError, ChangePasswordInput>
): UseMutationResult<{ message: string }, ApiError, ChangePasswordInput> {
    return useMutation<{ message: string }, ApiError, ChangePasswordInput>(
        async function (data) {
            return await callApi(
                {
                    url: `${API_LIST.Auth}/change-password`,
                    data,
                    method: 'POST',
                },
            );
        },
        options,
    );
}

export function useUpdateProfile(
    options?: UseMutationOptions<{ message: string }, ApiError, UpdateProfileInput>
): UseMutationResult<{ message: string }, ApiError, UpdateProfileInput> {
    return useMutation<{ message: string }, ApiError, UpdateProfileInput>(
        async function (data) {
            return await callApi(
                {
                    url: `${API_LIST.Auth}/update-profile`,
                    data,
                    method: 'PATCH',
                },
            );
        },
        options,
    );
}