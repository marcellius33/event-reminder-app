import { plainToClass } from "class-transformer";
import { Filter, PaginationMeta, Sort } from "../common.model";
import { decamelizeKeys } from "humps";

export function ModelTransformer(res: any, dataType: any): any {
  return plainToClass(dataType, decamelizeKeys(res));
}

function QueryTransformer(res: any, dataType?: any) {
  const { data: json } = res;
  if (json === undefined) {
    return res;
  }

  const newJson = res?.data
    ? {
        ...res,
        ...(res?.data
          ? {
              data: dataType ? plainToClass(dataType, res?.data) : res?.data,
            }
          : {}),
        ...(res?.filters
          ? {
              filters: plainToClass(Filter, res.filters),
            }
          : {}),
        ...(res?.sorts
          ? {
              sorts: plainToClass(Sort, res.sorts),
            }
          : {}),
        ...(res?.meta
          ? {
              meta: plainToClass(PaginationMeta, res.meta),
            }
          : {}),
      }
    : {
        ...(dataType ? plainToClass(dataType, res) : res),
      };

  return {
    ...res,
    ...newJson,
  };
}
export default QueryTransformer;