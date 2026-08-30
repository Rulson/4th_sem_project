import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';

class ForgotPasswordState extends Equatable {
  final AppState isLoading;
  final String? message;
  final String? email;
  final String? otp;
  final bool isPasswordVisible;
  final bool isConfirmPasswordVisible;
  final bool isOtpVerified;
  final bool isPasswordChanged;

  const ForgotPasswordState({
    this.isLoading = AppState.initial,
    this.message,
    this.email,
    this.otp,
    this.isPasswordVisible = false,
    this.isConfirmPasswordVisible = false,
    this.isOtpVerified = false,
    this.isPasswordChanged = false,
  });

  factory ForgotPasswordState.initial() {
    return const ForgotPasswordState();
  }

  ForgotPasswordState copyWith({
    AppState? isLoading,
    String? message,
    String? email,
    String? otp,
    bool? isPasswordVisible,
    bool? isConfirmPasswordVisible,
    bool? isOtpVerified,
    bool? isPasswordChanged,
  }) {
    return ForgotPasswordState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
      email: email ?? this.email,
      otp: otp ?? this.otp,
      isPasswordVisible: isPasswordVisible ?? this.isPasswordVisible,
      isConfirmPasswordVisible:
          isConfirmPasswordVisible ?? this.isConfirmPasswordVisible,
      isOtpVerified: isOtpVerified ?? this.isOtpVerified,
      isPasswordChanged: isPasswordChanged ?? this.isPasswordChanged,
    );
  }

  @override
  List<Object?> get props => [
        isLoading,
        message,
        email,
        otp,
        isPasswordVisible,
        isConfirmPasswordVisible,
        isOtpVerified,
        isPasswordChanged,
      ];
}
