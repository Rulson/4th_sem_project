import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';

import '../../../core/api_service/url_consts.dart';

abstract class SendMoneyRepo {
  AppResponse<bool> sendMoney(Map<String, dynamic> sendMoneyPM);
}

class SendMoneyRepoImpl implements SendMoneyRepo {
  @override
  AppResponse<bool> sendMoney(Map<String, dynamic> sendMoneyPM) async {
    FormData formData = FormData.fromMap(sendMoneyPM);
    final res = await sl<ApiService>().post(UrlConsts.storeTransaction, baseUrl: UrlConsts.baseUrl, data: formData, addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? false : true, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
