import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/receiver/model/district_list_model.dart';

class CountryState extends Equatable {
  final AppState isProvinceLoading;
  final AppState isDistrictLoading;
  final ResModel<List<DistrictListModel>>? districtData;
  final ResModel<List<DistrictListModel>>? proviceData;
  final String? message;

  const CountryState({
    required this.isProvinceLoading,
    required this.isDistrictLoading,
    this.districtData,
    this.proviceData,
    this.message,
  });

  CountryState copyWith(
      {AppState? isProvinceLoading,
      AppState? isDistrictLoading,
      ResModel<List<DistrictListModel>>? districtData,
      ResModel<List<DistrictListModel>>? proviceData,
      String? message}) {
    return CountryState(
        isProvinceLoading: isProvinceLoading ?? this.isProvinceLoading,
        isDistrictLoading: isDistrictLoading ?? this.isDistrictLoading,
        districtData: districtData ?? this.districtData,
        proviceData: proviceData ?? this.proviceData,
        message: message);
  }

  factory CountryState.initial() {
    return const CountryState(
        isProvinceLoading: AppState.initial,
        isDistrictLoading: AppState.initial);
  }

  @override
  List<Object?> get props => [
        isDistrictLoading,
        isDistrictLoading,
        districtData,
        proviceData,
        message
      ];
}
