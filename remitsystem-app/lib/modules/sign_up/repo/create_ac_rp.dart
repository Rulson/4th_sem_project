import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/sign_up/models/create_ac_model.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';

abstract class CheckEmailAvailabilityAndSendOtpRepo {
  AppResponse<CheckEmailAvailabilityAndSendOtpModel> checkEmailAvailabilityAndSendOtp({required CheckEmailAvailabilityAndSendOtpParam params});
}

class CheckEmailAvailabilityAndSendOtpRepoImpl implements CheckEmailAvailabilityAndSendOtpRepo {
  @override
  AppResponse<CheckEmailAvailabilityAndSendOtpModel> checkEmailAvailabilityAndSendOtp({required CheckEmailAvailabilityAndSendOtpParam params}) async {
    debugPrint("Making API call to: ${UrlConsts.baseUrl}${UrlConsts.checkEmailAvailabilityAndSendOtp}");
    debugPrint("Request data: ${params.toJson()}");

    var res =
        await sl<ApiService>().post(UrlConsts.checkEmailAvailabilityAndSendOtp, baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: false);

    if (res.error == 0) {
      return Right(ResModel(data: CheckEmailAvailabilityAndSendOtpModel.fromJson(res.data ?? {}), message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}

abstract class VerifyEmailNewRepo {
  AppResponse<String> verifyEmailNew({required VerifyEmailNewParam params});
  AppResponse<String> emailActivation({required EmailActivationParam params});
}

class VerifyEmailNewRepoImpl implements VerifyEmailNewRepo {
  @override
  AppResponse<String> verifyEmailNew({required VerifyEmailNewParam params}) async {
    var res = await sl<ApiService>().post(UrlConsts.verifyEmailNew, baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: false);
    if (res.error == 0) {
      return Right(ResModel(data: "Success", message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<String> emailActivation({required EmailActivationParam params}) async {
    var res = await sl<ApiService>().post(UrlConsts.verifyEmail, baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: false);
    if (res.error == 0) {
      return Right(ResModel(data: "Success", message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}

abstract class RegisterRepo {
  AppResponse<RegisterModel> register({required RegisterParam params});
}

class RegisterRepoImpl implements RegisterRepo {
  @override
  AppResponse<RegisterModel> register({required RegisterParam params}) async {
    final formData = FormData.fromMap({
      ...params.toJson(),
      "image": await MultipartFile.fromFile(params.image, filename: "image.jpg"),
      "image1": await MultipartFile.fromFile(params.image1, filename: "image1.jpg"),
      "address_proof": await MultipartFile.fromFile(params.addressProof, filename: "address_proof.jpg")
    });

    debugPrint("Files: ${formData.fields}");
    debugPrint("Files: ${formData.files}");

    var res = await sl<ApiService>().post(UrlConsts.register, baseUrl: UrlConsts.baseUrl, data: formData, addAuthInterceptor: false);
    debugPrint("data : ${res.data}");
    if (res.error == 0) {
      return Right(ResModel(data: RegisterModel.fromJson(res.data ?? {}), message: res.message, error: res.error));
    } else {
      debugPrint("Files: $res");
      return Left(res.message ?? "Something went wrong");
    }
  }
}
