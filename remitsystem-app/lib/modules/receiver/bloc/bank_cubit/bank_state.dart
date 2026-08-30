import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/models/res_model.dart';

import '../../../../core/common/app_state.dart';
import '../../model/bank_list_model.dart';

class BankState extends Equatable {
  final AppState isBankLoading;
  final ResModel<List<BankListModel>>? bankData;
  final String? message;

  const BankState({
    required this.isBankLoading,
    this.bankData,
    this.message,
  });

  BankState copyWith({
    AppState? isBankLoading,
    ResModel<List<BankListModel>>? bankData,
    String? message,
  }) {
    return BankState(
      isBankLoading: isBankLoading ?? this.isBankLoading,
      bankData: bankData ?? this.bankData,
      message: message,
    );
  }

  factory BankState.initial() {
    return const BankState(isBankLoading: AppState.initial);
  }

  @override
  List<Object?> get props => [isBankLoading, bankData, message];
}
