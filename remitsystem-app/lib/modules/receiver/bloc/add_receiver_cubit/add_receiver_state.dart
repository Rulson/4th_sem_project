import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

class AddReceiverState extends Equatable {
  final AppState isLoading;
  final String? message;
  final ReceiverData? newReceiver;

  const AddReceiverState({
    this.isLoading = AppState.initial,
    this.message,
    this.newReceiver,
  });

  AddReceiverState copyWith({
    AppState? isLoading,
    String? message,
    ReceiverData? newReceiver,
  }) {
    return AddReceiverState(
      isLoading: isLoading ?? this.isLoading,
      message: message,
      newReceiver: newReceiver ?? this.newReceiver,
    );
  }

  factory AddReceiverState.initial() {
    return const AddReceiverState(isLoading: AppState.initial);
  }

  @override
  List<Object?> get props => [isLoading, message, newReceiver];
}
