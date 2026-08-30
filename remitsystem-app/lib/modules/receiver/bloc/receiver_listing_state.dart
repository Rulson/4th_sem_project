import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

import '../../../core/common/app_state.dart';

class ReceiverListingState extends Equatable {
  final AppState? isLoading;
  final String? message;
  final ResModel<List<ReceiverData>>? receiverList;
  final List<ReceiverData> filteredReceiverList;

  const ReceiverListingState({this.isLoading, this.receiverList, this.message, this.filteredReceiverList = const []});

  ReceiverListingState copyWith({AppState? isLoading, ResModel<List<ReceiverData>>? receiverList, String? message, List<ReceiverData>? filteredReceiverList}) {
    return ReceiverListingState(
        isLoading: isLoading ?? this.isLoading,
        receiverList: receiverList ?? this.receiverList,
        message: message ?? this.message,
        filteredReceiverList: filteredReceiverList ?? this.filteredReceiverList);
  }

  factory ReceiverListingState.initial() {
    return const ReceiverListingState(isLoading: AppState.initial);
  }
  @override
  List<Object?> get props => [isLoading, receiverList, message, filteredReceiverList];
}
