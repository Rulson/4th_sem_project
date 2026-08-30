import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/sign_in/models/param/sign_in_param.dart';
import 'package:remit_management/modules/sign_in/models/sign_in_model.dart';

abstract class SignInRp {
  AppResponse<SignInModel> login({required SignInParam params});
}

class SignInRpImpl implements SignInRp {
  @override
  AppResponse<SignInModel> login({required SignInParam params}) async {
    var res = await sl<ApiService>().post(UrlConsts.login, baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: false);

    if (res.error == 0) {
      final data = SignInModel.fromJson(res.data ?? {});
      // debugPrint(data.toString());
      return Right(ResModel(data: data, message: res.message, error: res.error));
    } else {
      String msg = res.message ?? "Something went wrong";
      if (res.error == 401) {
        msg = "$msg. status ${res.error}";
      }
      return Left(msg);
    }
  }
}
