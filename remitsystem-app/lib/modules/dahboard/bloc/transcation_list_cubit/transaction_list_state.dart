import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/dahboard/models/transaction_list_model.dart';

class TransactionListState extends Equatable {
  final AppState transactionListLoading;
  final ResModel<List<TransactionData>>? transactionList;
  final String? message;

  const TransactionListState({required this.transactionListLoading, this.transactionList, this.message});

  TransactionListState copyWith({AppState? transactionListLoading, ResModel<List<TransactionData>>? transactionList, String? message}) {
    return TransactionListState(
        transactionListLoading: transactionListLoading ?? this.transactionListLoading,
        transactionList: transactionList ?? this.transactionList,
        message: message);
  }

  factory TransactionListState.initial() {
    return TransactionListState(
      transactionListLoading: AppState.initial,
    );
  }

  @override
  List<Object?> get props => [transactionList, transactionListLoading, message];
}
