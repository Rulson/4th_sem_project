import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/sign_in/models/sign_in_model.dart';

class SignInState extends Equatable {
  final AppState isLoading;
  final String? message;
  final ResModel<SignInModel>? data;
  final bool isPasswordVisible;
  final bool rememberMe;

  const SignInState(
      {this.isLoading = AppState.initial,
      this.data,
      this.message,
      this.isPasswordVisible = false,
      this.rememberMe = false});

  factory SignInState.initial() {
    return SignInState(isLoading: AppState.initial);
  }

  SignInState copyWith(
      {AppState? isLoading,
      ResModel<SignInModel>? data,
      String? message,
      bool? isPasswordVisible,
      bool? rememberMe}) {
    return SignInState(
        isLoading: isLoading ?? this.isLoading,
        data: data ?? this.data,
        message: message,
        isPasswordVisible: isPasswordVisible ?? this.isPasswordVisible,
        rememberMe: rememberMe ?? this.rememberMe);
  }

  @override
  List<Object?> get props =>
      [isLoading, data, message, isPasswordVisible, rememberMe];
}
