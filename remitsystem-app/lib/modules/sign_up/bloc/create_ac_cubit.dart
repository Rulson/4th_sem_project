import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_state.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';
import 'package:remit_management/modules/sign_up/repo/create_ac_rp.dart';

class CreateAccountCubit extends Cubit<CreateAccountState> {
  CreateAccountCubit() : super(CreateAccountState.initial());

  void checkEmailAvailabilityAndSendOtp(CheckEmailAvailabilityAndSendOtpParam params) async {
    emit(state.copyWith(isCheckLoading: AppState.loading));

    final res = await sl<CheckEmailAvailabilityAndSendOtpRepo>().checkEmailAvailabilityAndSendOtp(params: params);

    emit(res.fold((l) {
      return state.copyWith(isCheckLoading: AppState.error, checkStatus: false);
    }, (r) {
      debugPrint("Dataa: ${r.data}");
      return state.copyWith(isCheckLoading: AppState.success, checkStatus: r.error == 0);
    }));
  }

  void verifyEmailNew(VerifyEmailNewParam params) async {
    emit(state.copyWith(isVerifyLoading: AppState.loading));

    final res = await sl<VerifyEmailNewRepo>().verifyEmailNew(params: params);

    emit(res.fold((l) {
      return state.copyWith(isVerifyLoading: AppState.error, verifyStatus: false);
    }, (r) {
      return state.copyWith(isVerifyLoading: AppState.success, verifyStatus: true);
    }));
  }

  void register(RegisterParam params) async {
    emit(state.copyWith(isRegisterLoading: AppState.loading));

    final res = await sl<RegisterRepo>().register(params: params);

    debugPrint(res.toString());
    emit(res.fold((l) {
      return state.copyWith(isRegisterLoading: AppState.error, registrationStatus: false);
    }, (r) {
      return state.copyWith(isRegisterLoading: AppState.success, registrationStatus: r.error == 0);
    }));
  }

  void changePasswordVisibility() {
    emit(state.copyWith(isPasswordVisible: !state.isPasswordVisible));
  }

  void changePasswordConfirmationVisibility() {
    emit(state.copyWith(isPasswordConfirmationVisible: !state.isPasswordConfirmationVisible));
  }

  void storePassword(String password) {
    emit(state.copyWith(password: password));
  }

  void storePasswordConfrimation(String passwordConfrimation) {
    emit(state.copyWith(passwordConfrimation: passwordConfrimation));
  }

  void storeEmail(String email) {
    emit(state.copyWith(email: email));
  }

  void storeOtp(String otp) {
    emit(state.copyWith(otp: otp));
  }

  void resetCheckLoading() {
    emit(state.copyWith(isCheckLoading: AppState.initial));
  }

  void resetVerifyLoading() {
    emit(state.copyWith(isVerifyLoading: AppState.initial));
  }

  void resetRegisterLoading() {
    emit(state.copyWith(isRegisterLoading: AppState.initial));
  }
}
