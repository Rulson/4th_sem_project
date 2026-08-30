import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/sign_up/models/param/resend_otp_param.dart';

abstract class ResendOtpRepo {
  AppResponse<String> resendOtp({required ResendOtpParam params});
  AppResponse<String> resendEmailActivationCode({required ResendOtpParam params});
}

class ResendOtpRepoImpl implements ResendOtpRepo {
  @override
  AppResponse<String> resendOtp({required ResendOtpParam params}) async {
    var res = await sl<ApiService>().post(
      UrlConsts.resendOtp,
      data: params.toJson(),
      addAuthInterceptor: false,
    );

    if (res.error == 0) {
      return Right(ResModel(data: "Success", message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
  

  @override
  AppResponse<String> resendEmailActivationCode({required ResendOtpParam params}) async{
    var res = await sl<ApiService>().post(
      UrlConsts.resendActivationCode,
      data: params.toJson(),
      addAuthInterceptor: false,
    );

    if (res.error == 0) {
      return Right(ResModel(data: "Success", message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
