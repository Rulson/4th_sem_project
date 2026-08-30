import 'package:dartz/dartz.dart';
import 'package:remit_management/core/locator/locator.dart';

import '../../../core/api_service/api_service.dart';
import '../../../core/api_service/url_consts.dart';
import '../../../core/common/models/id_name_model.dart' show IdNameModel;
import '../../../core/common/models/res_model.dart';
import '../../../core/utils/res_type.dart';

abstract class AddressRepo {
  AppResponse<List<IdNameModel>> getCountries();
  AppResponse<List<IdNameModel>> getStates();
  AppResponse<List<IdNameModel>> getSuburbs(String query);
}

class AddressRepoImpl implements AddressRepo {
  @override
  AppResponse<List<IdNameModel>> getCountries() async {
    final res = await sl<ApiService>().post(UrlConsts.countries, addAuthInterceptor: true);

    if (res.error == 0) {
      final dynamic raw = res.data;
      final List<dynamic>? list = raw is List ? raw : (raw is Map<String, dynamic> ? raw['response'] ?? raw['data'] as List<dynamic>? : null);

      final value = list == null ? null : List<IdNameModel>.from(list.map((x) => IdNameModel.fromJson(x as Map<String, dynamic>)));

      return Right(ResModel(data: value, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<IdNameModel>> getStates() async {
    final res = await sl<ApiService>().post(UrlConsts.ausState, addAuthInterceptor: true);

    if (res.error == 0) {
      final dynamic raw = res.data;
      final List<dynamic>? list = raw is List ? raw : (raw is Map<String, dynamic> ? raw['response'] ?? raw['data'] as List<dynamic>? : null);

      final value = list == null ? null : List<IdNameModel>.from(list.map((x) => IdNameModel.fromJson(x as Map<String, dynamic>)));

      return Right(ResModel(data: value, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<List<IdNameModel>> getSuburbs(String query) async {
    final res = await sl<ApiService>().post(UrlConsts.getSuburb, addAuthInterceptor: true, queryParameters: {"search": query});

    if (res.error == 0) {
      final dynamic raw = res.data;
      final List<dynamic>? list = raw is List ? raw : (raw is Map<String, dynamic> ? raw['response'] ?? raw['data'] as List<dynamic>? : null);

      final value = list == null ? null : List<IdNameModel>.from(list.map((x) => IdNameModel.fromJson(x as Map<String, dynamic>)));

      return Right(ResModel(data: value, message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
