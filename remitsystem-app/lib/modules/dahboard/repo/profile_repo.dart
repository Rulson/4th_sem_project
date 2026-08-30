import 'package:dartz/dartz.dart';
import 'package:remit_management/core/api_service/api_service.dart';
import 'package:remit_management/core/api_service/url_consts.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/res_type.dart';
import 'package:remit_management/modules/dahboard/models/profile_model.dart';

abstract class ProfileRepo {
  AppResponse<ProfileModel> getProfile();
  AppResponse<ProfileModel> editProfile(Map<String, dynamic> param);
}

class ProfileRepoImpl implements ProfileRepo {
  @override
  AppResponse<ProfileModel> getProfile() async {
    final res = await sl<ApiService>().post(UrlConsts.profile, addAuthInterceptor: true);
    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? null : ProfileModel.fromJson(res.data ?? {}), message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }

  @override
  AppResponse<ProfileModel> editProfile(Map<String, dynamic> param) async {
    final res = await sl<ApiService>().post(UrlConsts.editProfile, addAuthInterceptor: true, data: param);
    if (res.error == 0) {
      return Right(ResModel(data: res.data == null ? null : ProfileModel.fromJson(res.data ?? {}), message: res.message, error: res.error));
    } else {
      return Left(res.message ?? "Something went wrong");
    }
  }
}
