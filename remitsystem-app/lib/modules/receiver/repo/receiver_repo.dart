import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/receiver/model/district_list_model.dart';
import 'package:remit_management/modules/receiver/model/receiver_pm.dart';

import '../../../core/common/models/res_model.dart';
import '../model/bank_list_model.dart';

abstract class ReceiverRepo {
  AppResponse<List<DistrictListModel>> getDistrictList();
  AppResponse<List<DistrictListModel>> getProviceList();
  AppResponse<bool> addReceiver(ReceiverPm params);
  AppResponse<bool> updateReceiver(ReceiverPm params, dynamic beneficiaryId);
  AppResponse<List<BankListModel>> getBankList();
}

class ReceiverRepoImpl implements ReceiverRepo {
  @override
  AppResponse<List<DistrictListModel>> getDistrictList() async {
    final res = await sl<ApiService>().post(UrlConsts.districts, addAuthInterceptor: false);
    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? null : List<DistrictListModel>.from(res.data?['response'].map((x) => DistrictListModel.fromJson(x))),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<DistrictListModel>> getProviceList() async {
    final res = await sl<ApiService>().post(UrlConsts.province, addAuthInterceptor: false);
    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? null : List<DistrictListModel>.from(res.data?['response'].map((x) => DistrictListModel.fromJson(x))),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<bool> addReceiver(ReceiverPm params) async {
    final res = await sl<ApiService>().post(UrlConsts.addReceiver, baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? false : true, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<bool> updateReceiver(ReceiverPm params, dynamic beneficiaryId) async {
    final res =
        await sl<ApiService>().post('${UrlConsts.updateReceiver}/$beneficiaryId', baseUrl: UrlConsts.baseUrl, data: params.toJson(), addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? false : true, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<BankListModel>> getBankList() async {
    final res = await sl<ApiService>().post(UrlConsts.banks, addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? null : List<BankListModel>.from(res.data?['response'].map((x) => BankListModel.fromJson(x))),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
