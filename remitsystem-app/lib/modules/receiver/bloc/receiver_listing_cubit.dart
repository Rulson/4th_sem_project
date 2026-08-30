import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/repo/home_repo.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_state.dart';

class ReceiverListingCubit extends Cubit<ReceiverListingState> {
  ReceiverListingCubit() : super(ReceiverListingState.initial());

  void getReceiverList() async {
    emit(state.copyWith(isLoading: AppState.loading));

    final res = await sl<HomeRepo>().getReceiverList();

    emit(res.fold((l) {
      return state.copyWith(isLoading: AppState.error, message: l);
    }, (r) {
      return state.copyWith(isLoading: AppState.success, receiverList: r);
    }));
  }

  void searchReceiver(String query) {
    final filteredList = state.receiverList?.data?.where((receiver) {
      final name = receiver.fullName?.toLowerCase() ?? '';
      return name.contains(query.toLowerCase());
    }).toList();

    emit(state.copyWith(filteredReceiverList: filteredList));
  }
}
