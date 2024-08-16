import { TokenResultModel } from "../../api-hooks/auth/model";
import { isWindowUndefined } from "./string";

export const storage = {
    me: 'get-me',
    token: 'get-token',
} as const;

export function transformStorageToJson<T = any>(value: string | null) {
    if (!value) return undefined;
    const result = JSON.parse(value);
    return result as T;
}

export function getTokenStorage() {
    if (isWindowUndefined) return undefined;
    const value = localStorage.getItem(storage.token);
    return transformStorageToJson<TokenResultModel>(value);
}

export function getMeStorage() {
    if (isWindowUndefined) return undefined;
    const value = localStorage.getItem(storage.me);
    return transformStorageToJson(value);
}

export function setTokenStorage<T = any>(value?: T) {
    if (isWindowUndefined) return undefined;
    if (value) {
        const result = JSON.stringify(value);
        localStorage.setItem(storage.token, result);
        return;
    }
    localStorage.removeItem(storage.token);
}

export function setMeStorage<T = any>(value?: T) {
    if (isWindowUndefined) return undefined;
    if (value) {
        const result = JSON.stringify(value);
        localStorage.setItem(storage.me, result);
        return;
    }
    localStorage.removeItem(storage.me);
}

export function clearTokenStorage() {
    if (isWindowUndefined) return undefined;
    localStorage.removeItem(storage.token);
}
  
export function clearMeStorage() {
    if (isWindowUndefined) return undefined;
    localStorage.removeItem(storage.me);
}