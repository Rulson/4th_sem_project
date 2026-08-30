import 'package:flutter/cupertino.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:multi_image_picker_view/multi_image_picker_view.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_state.dart';
import 'package:remit_management/modules/send_money/repository/send_money_repo.dart';

class SendMoneyCubit extends Cubit<SendMoneyState> {
  final TextEditingController audController = TextEditingController(text: "1");
  final TextEditingController nprController = TextEditingController();

  SendMoneyCubit() : super(SendMoneyState.initial());

  void syncAmounts(String audValue, double rate) {
    String clean = audValue.replaceAll(RegExp(r'[^0-9.]'), '');
    if (clean.isEmpty) clean = '0';

    final double aud = double.tryParse(clean) ?? 0;
    final double npr = aud * rate;
    final double fee = 5.0;
    nprController.text = npr.toStringAsFixed(2);

    emit(state.copyWith(
      sendingAmount: clean,
      subTotalConvertedAmt: (aud + fee).toStringAsFixed(2),
    ));
  }

  void setSubTotalConvertedAmt({required String subTotalConvertedAmt, required String sendingAmount}) {
    emit(state.copyWith(sendingAmount: sendingAmount, subTotalConvertedAmt: subTotalConvertedAmt));
  }

  void setSelectedReceiver(ReceiverData? selectedReceiver) {
    emit(state.copyWith(selectedReceiver: selectedReceiver));
  }

  void resetSelectedReceiver() {
    emit(state.copyWith(selectedReceiver: null));
  }

  void setImages(List<ImageFile> images) {
    emit(state.copyWith(images: images));
  }

  void storeTransaction(Map<String, dynamic> param) async {
    emit(state.copyWith(transactionLoading: AppState.loading));

    final res = await sl<SendMoneyRepo>().sendMoney(param);
    emit(res.fold((l) {
      return state.copyWith(transactionLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(transactionLoading: AppState.success, message: r.message);
    }));
  }

  void resetSendMoneyState() {
    audController.text = "1";
    nprController.text = "";
    emit(SendMoneyState.initial());
  }

  void resetControllers() {
    audController.text = "1";
    nprController.text = "";
  }

  @override
  Future<void> close() {
    audController.dispose();
    nprController.dispose();
    return super.close();
  }
}
