import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/common/app_state.dart';
import '../../../../core/locator/locator.dart';
import '../../repo/address_repo.dart';
import 'address_state.dart';

class AddressCubit extends Cubit<AddressState> {
  AddressCubit() : super(AddressState.initial());

  void getCountries() async {
    emit(state.copyWith(isCountryLoading: AppState.loading));
    final res = await sl<AddressRepo>().getCountries();
    emit(res.fold((l) {
      return state.copyWith(isCountryLoading: AppState.error, countryMessage: l);
    }, (r) {
      return state.copyWith(isCountryLoading: AppState.success, countriesData: r);
    }));
  }

  void getStates() async {
    emit(state.copyWith(isStateLoading: AppState.loading));
    final res = await sl<AddressRepo>().getStates();
    emit(res.fold((l) {
      return state.copyWith(isStateLoading: AppState.error, stateMessage: l);
    }, (r) {
      return state.copyWith(isStateLoading: AppState.success, statesData: r);
    }));
  }

  Future<void> getSuburbs(String query) async {
    emit(state.copyWith(isSuburbLoading: AppState.loading));
    final res = await sl<AddressRepo>().getSuburbs(query);
    emit(res.fold((l) {
      return state.copyWith(isSuburbLoading: AppState.error, suburbMessage: l);
    }, (r) {
      return state.copyWith(isSuburbLoading: AppState.success, suburbsData: r);
    }));
  }
}
