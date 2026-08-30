import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/models/change_password_param.dart';

abstract class ChangePasswordRepo {
  AppResponse<dynamic> changePassword({required ChangePasswordParam params});
}

class ChangePasswordRepoImpl implements ChangePasswordRepo {
  @override
  AppResponse<dynamic> changePassword(
      {required ChangePasswordParam params}) async {
    var res = await sl<ApiService>().post(
      UrlConsts.changePassword,
      baseUrl: UrlConsts.baseUrl,
      data: params.toJson(),
      addAuthInterceptor: true,
    );

    if (res.error == 0) {
      return Right(
          ResModel(data: res.data, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
