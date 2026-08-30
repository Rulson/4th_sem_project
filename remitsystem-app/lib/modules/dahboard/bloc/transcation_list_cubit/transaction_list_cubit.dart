import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_state.dart';
import 'package:remit_management/modules/dahboard/repo/home_repo.dart';

class TransactionListCubit extends Cubit<TransactionListState> {
  TransactionListCubit() : super(TransactionListState.initial());

  void getTransactionList() async {
    emit(state.copyWith(transactionListLoading: AppState.loading));

    final res = await sl<HomeRepo>().getTransactionList();

    emit(res.fold((l) {
      return state.copyWith(transactionListLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(
          transactionListLoading: AppState.success, transactionList: r);
    }));
  }
}
