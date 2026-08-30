import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/pin/bloc/pin_state.dart';
import 'package:remit_management/modules/pin/model/pin_pm.dart';
import 'package:remit_management/modules/pin/repo/pin_repo.dart';

class PinCubit extends Cubit<PinState> {
  PinCubit() : super(PinState.initial());

  void setPin(PinPm params) async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<PinRepo>().setPin(params: params);
    emit(res.fold((l) {
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(isLoading: AppState.success, message: r.message);
    }));
  }

  void validatePin(PinPm params) async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<PinRepo>().validatePin(params: params);
    emit(res.fold((l) {
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      // LocalDb.saveData(key: AppStringConst.validatePin, value: r.data.);
      return state.copyWith(isLoading: AppState.success, message: r.message);
    }));
  }

  void resetState() => emit(PinState.initial());
}
