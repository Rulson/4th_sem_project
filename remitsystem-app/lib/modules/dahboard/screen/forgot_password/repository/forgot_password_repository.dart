import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/model/params/forgot_password_params.dart';

abstract class ForgotPasswordRepository {
  AppResponse<dynamic> sendOtp(String email);
  AppResponse<dynamic> verifyOtp(String email, String otp);
  AppResponse<dynamic> changePassword(ForgotPasswordParams params);
}

class ForgotPasswordRepositoryImpl implements ForgotPasswordRepository {
  @override
  AppResponse<dynamic> sendOtp(String email) async {
    var res = await sl<ApiService>().post(
      UrlConsts.forgotPasswordOtp,
      data: {'email': email},
      addAuthInterceptor: false,
    );

    if (res.error == 0) {
      return Right(
          ResModel(data: res.data, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<dynamic> verifyOtp(String email, String otp) async {
    var res = await sl<ApiService>().post(
      UrlConsts.forgotPasswordOtp,
      data: {
        'email': email,
        'otp': otp,
      },
      addAuthInterceptor: false,
    );

    if (res.error == 0) {
      return Right(
          ResModel(data: res.data, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<dynamic> changePassword(ForgotPasswordParams params) async {
    var res = await sl<ApiService>().post(
      UrlConsts.forgotPasswordChange,
      data: params.toJson(),
      addAuthInterceptor: false,
    );

    if (res.error == 0) {
      return Right(
          ResModel(data: res.data, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
