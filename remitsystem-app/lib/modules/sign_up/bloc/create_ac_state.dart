import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/modules/sign_up/models/create_ac_model.dart';

class CreateAccountState extends Equatable {
  final AppState isCheckLoading;
  final AppState isVerifyLoading;
  final AppState isRegisterLoading;
  final bool isPasswordVisible;
  final bool isPasswordConfirmationVisible;
  final String? email;
  final String? password;
  final String? passwordConfrimation;
  final bool? checkStatus;
  final bool? verifyStatus;
  final bool? registrationStatus;
  final String? otp;
  final RegisterModel? registerModel;

  const CreateAccountState(
      {this.isCheckLoading = AppState.initial,
      this.isVerifyLoading = AppState.initial,
      this.isRegisterLoading = AppState.initial,
      this.isPasswordVisible = false,
      this.checkStatus,
      this.verifyStatus,
      this.isPasswordConfirmationVisible = false,
      this.email,
      this.password,
      this.passwordConfrimation,
      this.registrationStatus,
      this.otp,
      this.registerModel});

  CreateAccountState copyWith(
      {AppState? isCheckLoading,
      AppState? isVerifyLoading,
      AppState? isRegisterLoading,
      bool? isPasswordVisible,
      bool? isPasswordConfirmationVisible,
      String? password,
      String? email,
      String? passwordConfrimation,
      bool? checkStatus,
      bool? verifyStatus,
      bool? registrationStatus,
      String? otp,
      RegisterModel? registerModel}) {
    return CreateAccountState(
        isCheckLoading: isCheckLoading ?? this.isCheckLoading,
        isVerifyLoading: isVerifyLoading ?? this.isVerifyLoading,
        isRegisterLoading: isRegisterLoading ?? this.isRegisterLoading,
        isPasswordVisible: isPasswordVisible ?? this.isPasswordVisible,
        isPasswordConfirmationVisible:
            isPasswordConfirmationVisible ?? this.isPasswordConfirmationVisible,
        password: password ?? this.password,
        passwordConfrimation: passwordConfrimation ?? this.passwordConfrimation,
        checkStatus: checkStatus ?? this.checkStatus,
        verifyStatus: verifyStatus ?? this.verifyStatus,
        registrationStatus: registrationStatus ?? this.registrationStatus,
        otp: otp ?? this.otp,
        email: email ?? this.email,
        registerModel: registerModel ?? this.registerModel);
  }

  factory CreateAccountState.initial() {
    return CreateAccountState(
        isCheckLoading: AppState.initial,
        isVerifyLoading: AppState.initial,
        isRegisterLoading: AppState.initial);
  }

  @override
  List<Object?> get props => [
        isCheckLoading,
        isVerifyLoading,
        isRegisterLoading,
        isPasswordVisible,
        isPasswordConfirmationVisible,
        password,
        passwordConfrimation,
        checkStatus,
        verifyStatus,
        registrationStatus,
        otp,
        email,
        registerModel
      ];
}
