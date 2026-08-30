import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';

class EmailActivationState extends Equatable {
  final AppState isLoading;
  final String? message;

  const EmailActivationState({this.isLoading = AppState.initial, this.message});

  factory EmailActivationState.initial() {
    return const EmailActivationState(isLoading: AppState.initial);
  }

  EmailActivationState copyWith({AppState? isLoading, String? message}) {
    return EmailActivationState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, message];
}
