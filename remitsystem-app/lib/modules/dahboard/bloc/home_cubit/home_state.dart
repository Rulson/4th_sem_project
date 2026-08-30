import 'package:equatable/equatable.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/models/res_model.dart';
import 'package:remit_management/modules/dahboard/models/home_model.dart';

class HomeState extends Equatable {
  final String? message;
  final AppState isLoading;
  final ResModel<HomeModel>? homeModel;
  const HomeState({required this.isLoading, this.homeModel, this.message});

  factory HomeState.initial() {
    return HomeState(isLoading: AppState.initial);
  }

  HomeState copyWith(
      {AppState? isLoading, ResModel<HomeModel>? homeModel, String? message}) {
    return HomeState(
      isLoading: isLoading ?? this.isLoading,
      homeModel: homeModel ?? this.homeModel,
      message: message,
    );
  }

  @override
  List<Object?> get props => [isLoading, homeModel, message];
}
