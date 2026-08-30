import 'package:equatable/equatable.dart';
import 'package:multi_image_picker_view/multi_image_picker_view.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

class SendMoneyState extends Equatable {
  final String subTotalConvertedAmt;
  final ReceiverData? selectedReceiver;
  final String? sendingAmount;
  final AppState? transactionLoading;
  final String? message;
  final List<ImageFile> images;
  const SendMoneyState(
      {this.subTotalConvertedAmt = "0.0", this.selectedReceiver, this.sendingAmount, this.transactionLoading, this.message, this.images = const <ImageFile>[]});

  SendMoneyState copyWith({
    String? subTotalConvertedAmt,
    ReceiverData? selectedReceiver,
    String? sendingAmount,
    AppState? transactionLoading,
    String? message,
    List<ImageFile>? images,
  }) {
    return SendMoneyState(
      subTotalConvertedAmt: subTotalConvertedAmt ?? this.subTotalConvertedAmt,
      sendingAmount: sendingAmount ?? this.sendingAmount,
      selectedReceiver: selectedReceiver ?? this.selectedReceiver,
      transactionLoading: transactionLoading ?? this.transactionLoading,
      message: message ?? this.message,
      images: images ?? this.images,
    );
  }

  factory SendMoneyState.initial() {
    return const SendMoneyState(subTotalConvertedAmt: "0.0", selectedReceiver: null, sendingAmount: "0.0", transactionLoading: AppState.initial);
  }

  @override
  List<Object?> get props => [subTotalConvertedAmt, selectedReceiver, sendingAmount, transactionLoading, message, images];
}
