import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';

class ChangePasswordState extends Equatable {
  final AppState isLoading;
  final String? message;
  final bool isOldPasswordVisible;
  final bool isNewPasswordVisible;
  final bool isConfirmPasswordVisible;

  const ChangePasswordState({
    this.isLoading = AppState.initial,
    this.message,
    this.isOldPasswordVisible = false,
    this.isNewPasswordVisible = false,
    this.isConfirmPasswordVisible = false,
  });

  factory ChangePasswordState.initial() {
    return const ChangePasswordState(isLoading: AppState.initial);
  }

  ChangePasswordState copyWith({
    AppState? isLoading,
    String? message,
    bool? isOldPasswordVisible,
    bool? isNewPasswordVisible,
    bool? isConfirmPasswordVisible,
  }) {
    return ChangePasswordState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
      isOldPasswordVisible: isOldPasswordVisible ?? this.isOldPasswordVisible,
      isNewPasswordVisible: isNewPasswordVisible ?? this.isNewPasswordVisible,
      isConfirmPasswordVisible:
          isConfirmPasswordVisible ?? this.isConfirmPasswordVisible,
    );
  }

  @override
  List<Object?> get props => [
        isLoading,
        message,
        isOldPasswordVisible,
        isNewPasswordVisible,
        isConfirmPasswordVisible,
      ];
}
