import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/receiver/bloc/country_state.dart';
import 'package:remit_management/modules/receiver/repo/receiver_repo.dart';

class CountryCubit extends Cubit<CountryState> {
  CountryCubit() : super(CountryState.initial());

  void getDistrictList() async {
    final res = await sl<ReceiverRepo>().getDistrictList();

    emit(res.fold((l) {
      return state.copyWith(isDistrictLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(
        isDistrictLoading: AppState.success,
        districtData: r,
      );
    }));
  }

  void getProvinceList() async {
    final res = await sl<ReceiverRepo>().getProviceList();

    emit(res.fold((l) {
      return state.copyWith(isProvinceLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(
        isProvinceLoading: AppState.success,
        proviceData: r,
      );
    }));
  }
}
