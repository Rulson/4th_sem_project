import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_state.dart';
import 'package:remit_management/modules/dahboard/repo/home_repo.dart';

class HomeCubit extends Cubit<HomeState> {
  HomeCubit() : super(HomeState.initial());

  void getHomeData() async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<HomeRepo>().getHomeData();
    emit(res.fold(
        (l) => state.copyWith(isLoading: AppState.error, message: l),
        (r) => state.copyWith(
            message: r.message, isLoading: AppState.success, homeModel: r)));
  }
}
