import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/receiver/bloc/bank_cubit/bank_state.dart';
import 'package:remit_management/modules/receiver/repo/receiver_repo.dart';


class BankCubit extends Cubit<BankState> {
  BankCubit() : super(BankState.initial());

  void getBankList() async {
    final res = await sl<ReceiverRepo>().getBankList();

    emit(res.fold((l) {
      return state.copyWith(isBankLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(
        isBankLoading: AppState.success,
        bankData: r,
      );
    }));
  }
}
