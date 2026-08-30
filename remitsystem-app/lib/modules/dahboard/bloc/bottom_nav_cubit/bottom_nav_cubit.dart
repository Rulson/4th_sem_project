import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/modules/dahboard/bloc/bottom_nav_cubit/bottom_nav_state.dart';

class BottomNavCubit extends Cubit<BottomNavState> {
  BottomNavCubit() : super(BottomNavState.initial());

  void toggleBottomNav(int index) {
    emit(state.copyWith(bottomIndex: index));
  }

  void resetState() => emit(BottomNavState.initial());
}
