import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/bloc/change_password_state.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/models/change_password_param.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/repo/change_password_repo.dart';

class ChangePasswordCubit extends Cubit<ChangePasswordState> {
  ChangePasswordCubit() : super(ChangePasswordState.initial());

  void changePassword(ChangePasswordParam param) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ChangePasswordRepo>().changePassword(params: param);

    emit(res.fold((l) {
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(isLoading: AppState.success, message: r.message);
    }));
  }

  void toggleOldPasswordVisibility() {
    emit(state.copyWith(isOldPasswordVisible: !state.isOldPasswordVisible));
  }

  void toggleNewPasswordVisibility() {
    emit(state.copyWith(isNewPasswordVisible: !state.isNewPasswordVisible));
  }

  void toggleConfirmPasswordVisibility() {
    emit(state.copyWith(
        isConfirmPasswordVisible: !state.isConfirmPasswordVisible));
  }

  void resetState() => emit(ChangePasswordState.initial());
}
