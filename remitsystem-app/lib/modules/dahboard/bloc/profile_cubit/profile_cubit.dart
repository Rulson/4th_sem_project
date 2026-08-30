import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_state.dart';
import 'package:remit_management/modules/dahboard/models/profile_model.dart';
import 'package:remit_management/modules/dahboard/repo/profile_repo.dart';

class ProfileCubit extends Cubit<ProfileState> {
  ProfileCubit() : super(ProfileState.initial());

  void getProfile() async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<ProfileRepo>().getProfile();
    emit(res.fold(
        (l) => state.copyWith(isLoading: AppState.error, message: l), (r) => state.copyWith(message: r.message, isLoading: AppState.success, profileModel: r)));
  }

  void editProfile(ProfileParam param) async {
    emit(state.copyWith(isEditLoading: AppState.loading));
    final res = await sl<ProfileRepo>().editProfile(param.toJson());
    // debugPrint(res.toString());
    emit(
        res.fold((l) => state.copyWith(isEditLoading: AppState.error, message: l), (r) => state.copyWith(message: r.message, isEditLoading: AppState.success)));
  }

  void resetEditState() {
    emit(state.copyWith(isEditLoading: AppState.initial, message: null));
  }
}
