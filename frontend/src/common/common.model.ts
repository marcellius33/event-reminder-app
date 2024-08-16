import { Type, Expose } from 'class-transformer';

export class ApiError {
    message?: string;
    statusCode?: number;
    errors?: {[key:string]: string};
}

export class PaginationMeta {
    to?: number;
    total?: number;
    from?: number;
    path?: string;

    @Expose({ name: 'current_page '})
    currentPage?: number;

    @Expose({ name: 'last_page' })
    lastPage?: number;

    @Expose({ name: 'per_page' })
    perPage?: number;
}

export enum FilterBehaviour {
    Exact = 'exact',
    Partial = 'partial',
}

export class Filter {
    name?: string;
    label?: string;
    type?: string;
  
    behaviour?: FilterBehaviour;
    value?: string | null;
    default?: string;
}

export class Sort {
    options?: string[];
    default?: string;
    value?: string;
}

export class ApiResult<T> {
    data?: T;
    message?: string;
}

export class MessageResult {
    message?: string;
}

export class ExtendedApiResult<T> extends ApiResult<T> {
    @Type(() => PaginationMeta)
    meta?: PaginationMeta;
    @Type(() => Filter)
    filters?: Filter[];
    @Type(() => Sort)
    sorts?: Sort;
}

export class TimeModel {
    @Expose({ name: 'created_at' })
    @Type(() => Date)
    createdAt?: Date;
  
    @Expose({ name: 'updated_at' })
    @Type(() => Date)
    updatedAt?: Date;
}
  
export class CommonModel {
    id?: string;
    
    @Expose({ name: 'created_at' })
    @Type(() => Date)
    createdAt?: Date;
  
    @Expose({ name: 'updated_at' })
    @Type(() => Date)
    updatedAt?: Date;
}

export interface getParamsInput<
  Params extends { [key: string]: any } = object,
> {
  params: {
    page?: number;
    filter?: { [key: string]: any };
    sort?: string;
    limit?: number;
    q?: string;
  } & Params;
}