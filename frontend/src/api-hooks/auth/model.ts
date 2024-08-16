import { Expose } from "class-transformer";
import { CommonModel } from "../../common/common.model";

export class TokenResultModel {
    @Expose({ name: 'token_type' }) 
    tokenType?: string;

    @Expose({ name: 'expires_in' })
    expiresIn?: number;

    @Expose({ name: 'access_token' })
    accessToken?: string;

    @Expose({ name: 'refresh_token' })
    refreshToken?: string;
}

export class MeModel extends CommonModel {
  name?: string;
  email?: string;
}

export type LoginInput = {
  email: string;
  password: string;
}

export type RegisterInput = {
  name: string;
  email: string;
  password: string;
  passwordConfirmation: string
}

export type ChangePasswordInput = {
  oldPassword: string;
  password: string;
  passwordConfirmation: string;
}

export type UpdateProfileInput = {
  name: string;
  email: string;
}