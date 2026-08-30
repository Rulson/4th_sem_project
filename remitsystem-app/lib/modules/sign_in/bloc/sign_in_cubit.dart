import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/core/utils/local_db.dart';
import 'package:remit_management/modules/sign_in/bloc/sign_in_state.dart';
import 'package:remit_management/modules/sign_in/bloc/global_state.dart';
import 'package:remit_management/modules/sign_in/models/param/sign_in_param.dart';
import 'package:remit_management/modules/sign_in/repo/sign_in_rp.dart';

class SignInCubit extends Cubit<SignInState> {
  SignInCubit() : super(SignInState.initial());

  void login(SignInParam param) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<SignInRp>().login(params: param);

    emit(res.fold((l) {
      // print("Login error: $l");
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      if (state.rememberMe == true) {
        LocalDb.saveData(
            key: AppStringConst.apiToken, value: r.data?.apiToken ?? "");
        LocalDb.saveData(
            key: AppStringConst.pinSet, value: r.data?.pinSet ?? false);
      } else {
        // TokenCubit().setToken(r.data?.apiToken ?? "");
        GlobalState.instance.token = r.data?.apiToken ?? "";
      }
      return state.copyWith(isLoading: AppState.success, data: r);
    }));
  }

  void changePasswordVisibility() {
    emit(state.copyWith(isPasswordVisible: !state.isPasswordVisible));
  }

  void setRememberMe() => emit(state.copyWith(rememberMe: !state.rememberMe));

  void resetState() => emit(SignInState.initial());
}
