import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';

class ResendOtpState extends Equatable {
  final AppState isLoading;
  final String? message;

  const ResendOtpState({this.isLoading = AppState.initial, this.message});

  factory ResendOtpState.initial() {
    return const ResendOtpState();
  }

  ResendOtpState copyWith({AppState? isLoading, String? message}) {
    return ResendOtpState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, message];
}
