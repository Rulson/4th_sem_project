import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_state.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/model/params/forgot_password_params.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/repository/forgot_password_repository.dart';

class ForgotPasswordCubit extends Cubit<ForgotPasswordState> {
  ForgotPasswordCubit() : super(ForgotPasswordState.initial());

  void sendOtp(String email) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ForgotPasswordRepository>().sendOtp(email);

    emit(res.fold(
      (l) => state.copyWith(isLoading: AppState.error, message: l),
      (r) => state.copyWith(
        isLoading: AppState.success,
        message: r.message,
        email: email,
      ),
    ));
  }

  void verifyOtp(String email, String otp) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ForgotPasswordRepository>().verifyOtp(email, otp);

    emit(res.fold(
      (l) => state.copyWith(isLoading: AppState.error, message: l),
      (r) => state.copyWith(
        isLoading: AppState.success,
        message: r.message,
        isOtpVerified: true,
        otp: otp,
      ),
    ));
  }

  void changePassword(
      String email, String otp, String password, String confirmPassword) async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<ForgotPasswordRepository>().changePassword(
      ForgotPasswordParams(
        email: email,
        password: password,
        passwordConfirmation: confirmPassword,
        otp: otp,
      ),
    );

    emit(res.fold(
      (l) => state.copyWith(isLoading: AppState.error, message: l),
      (r) => state.copyWith(
        isLoading: AppState.success,
        message: r.message,
        isPasswordChanged: true,
      ),
    ));
  }

  void togglePasswordVisibility() {
    emit(state.copyWith(isPasswordVisible: !state.isPasswordVisible));
  }

  void toggleConfirmPasswordVisibility() {
    emit(state.copyWith(
        isConfirmPasswordVisible: !state.isConfirmPasswordVisible));
  }

  void updateOtp(String otp) {
    emit(state.copyWith(otp: otp));
  }

  void resetState() => emit(ForgotPasswordState.initial());
}
