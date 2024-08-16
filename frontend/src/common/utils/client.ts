import axios, { AxiosRequestConfig, AxiosResponse } from "axios";
import { getTokenStorage } from "./storage";
import { decamelizeKeys } from "humps";
import { ClassConstructor } from "class-transformer";
import QueryTransformer from "./query-transformer";

export const BASE_URL = 'https://event-reminder-api.varrelmarcellius.xyz/api/' as const;

export const API_LIST = {
    Auth: 'auth',
    Events: 'events',
} as const;

const client = axios.create({
    baseURL: BASE_URL,
});

client.interceptors.request.use((value) => {
    // Authorization
    const token = getTokenStorage()?.accessToken;
    value.headers.Authorization = token ? `Bearer ${token}` : undefined;

    if (value.params) {
        value.params = decamelizeKeys(value.params);
    }

    if (value.data && value.headers['Content-Type'] !== 'multipart/form-data') {
        value.data = decamelizeKeys(value.data);
    }
    return value;
});

export async function callApi<T = any>(
    args: AxiosRequestConfig,
    dataType?: ClassConstructor<T>,
) {
    const { method = 'GET', headers, ...rest} = args;
    const token = getTokenStorage()?.accessToken;

    return client({
        method,
        headers: {
            Authorization: token ? `Bearer ${token}` : undefined,
            ...headers,
        },
        ...rest,
    })
        .then(async (value: AxiosResponse<T>) => {
            if (value.headers['content-type'] === 'application/json') {
                return QueryTransformer(value.data, dataType);
            }
            return value.data;
        })
        .catch((error) => Promise.reject(error.response.data));
}

export default client;