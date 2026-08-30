import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/dahboard/models/home_model.dart';
import 'package:remit_management/modules/dahboard/models/transaction_list_model.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

import '../screen/notification/model/notification_model.dart';

abstract class HomeRepo {
  AppResponse<HomeModel> getHomeData();
  AppResponse<List<ReceiverData>> getReceiverList();
  AppResponse<List<TransactionData>> getTransactionList();
  AppResponse<NotificationListModel> getNotificationList(Map<String, dynamic> param);
  AppResponse<String> markAsReadNotification(Map<String, dynamic>? param);
  AppResponse<String> markAllAsReadNotification();
}

class HomeRepoImpl implements HomeRepo {
  @override
  AppResponse<HomeModel> getHomeData() async {
    final res = await sl<ApiService>().post(UrlConsts.home, addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? null : HomeModel.fromJson(res.data ?? {}), message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<ReceiverData>> getReceiverList() async {
    final res = await sl<ApiService>().post(UrlConsts.beneficiaries, addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? null : List<ReceiverData>.from(res.data?['response'].map((x) => ReceiverData.fromJson(x))),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<TransactionData>> getTransactionList() async {
    final res = await sl<ApiService>().post(UrlConsts.transactions, addAuthInterceptor: true);

    if (res.error == 0) {
      return Right(ResModel(
          data: res.data == null ? null : List<TransactionData>.from(res.data?['response'].map((x) => TransactionData.fromJson(x))),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }


  @override
  AppResponse<NotificationListModel> getNotificationList(Map<String, dynamic> param) async {
    final res = await sl<ApiService>().post(UrlConsts.getNotificationList, data: param, addAuthInterceptor: true);

    if (res.error == 0) {
      final data = {
        "response": res.data,
        "message": res.message,
        "status": res.error,
        "count": res.count?.toJson(),
      };
      return Right(ResModel(
          count: res.count,
          data: res.data == null ? null : NotificationListModel.fromJson(data),
          message: res.message,
          error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<String> markAsReadNotification(Map<String, dynamic>? param) async {
    var res = await sl<ApiService>()
        .post(UrlConsts.markNotificationAsRead, baseUrl: UrlConsts.baseUrl, data: param, addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(message: res.message, error: res.error, data: res.data['message'] ?? ""));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<String> markAllAsReadNotification() async {
    var res = await sl<ApiService>()
        .post(UrlConsts.markNotificationAsRead, baseUrl: UrlConsts.baseUrl, addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(message: res.message, error: res.error, data: res.data['message'] ?? ""));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
