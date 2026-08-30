import 'package:equatable/equatable.dart';

import '../../../../core/common/app_state.dart';
import '../../../../core/common/models/id_name_model.dart';
import '../../../../core/common/models/res_model.dart';

class AddressState extends Equatable {
  final ResModel<List<IdNameModel>>? countriesData;
  final ResModel<List<IdNameModel>>? statesData;
  final ResModel<List<IdNameModel>>? suburbsData;
  // AppState variables
  final AppState isCountryLoading;
  final AppState isStateLoading;
  final AppState isSuburbLoading;
  // App Message
  final String? countryMessage;
  final String? stateMessage;
  final String? suburbMessage;

  const AddressState({
    this.countriesData,
    this.statesData ,
    this.suburbsData,
    this.isCountryLoading = AppState.initial,
    this.isStateLoading = AppState.initial,
    this.isSuburbLoading = AppState.initial,
    this.countryMessage,
    this.stateMessage,
    this.suburbMessage,
  });

  factory AddressState.initial() {
    return const AddressState();
  }

  AddressState copyWith({
    ResModel<List<IdNameModel>>? countriesData,
    ResModel<List<IdNameModel>>? statesData,
    ResModel<List<IdNameModel>>? suburbsData,
    AppState? isCountryLoading,
    AppState? isStateLoading,
    AppState? isSuburbLoading,
    String? countryMessage,
    String? stateMessage,
    String? suburbMessage,
  }) {
    return AddressState(
      countriesData: countriesData ?? this.countriesData,
      statesData: statesData ?? this.statesData,
      suburbsData: suburbsData ?? this.suburbsData,
      isCountryLoading: isCountryLoading ?? this.isCountryLoading,
      isStateLoading: isStateLoading ?? this.isStateLoading,
      isSuburbLoading: isSuburbLoading ?? this.isSuburbLoading,
      countryMessage: countryMessage ?? this.countryMessage,
      stateMessage: stateMessage ?? this.stateMessage,
      suburbMessage: suburbMessage ?? this.suburbMessage,
    );
  }

  @override
  List<Object?> get props => [countriesData, statesData, suburbsData, isCountryLoading, isStateLoading, isSuburbLoading];

}
