import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';

class PinState extends Equatable {
  final AppState? isLoading;
  final String? message;

  const PinState({this.isLoading, this.message});

  factory PinState.initial() {
    return const PinState(isLoading: AppState.initial);
  }

  PinState copyWith({AppState? isLoading, String? message}) {
    return PinState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, message];
}
