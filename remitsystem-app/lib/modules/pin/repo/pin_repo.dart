import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/pin/model/pin_pm.dart';

abstract class PinRepo {
  AppResponse<bool> setPin({required PinPm params});
  AppResponse<bool> validatePin({required PinPm params});
}

class PinRepoImpl extends PinRepo {
  @override
  AppResponse<bool> setPin({required PinPm params}) async {
    var res = await sl<ApiService>().post(UrlConsts.setPin,
        baseUrl: UrlConsts.baseUrl,
        data: params.toJson(),
        addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? false : true,
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<bool> validatePin({required PinPm params}) async {
    var res = await sl<ApiService>().post(UrlConsts.validatePin,
        baseUrl: UrlConsts.baseUrl,
        data: params.toJson(),
        addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? false : true,
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
