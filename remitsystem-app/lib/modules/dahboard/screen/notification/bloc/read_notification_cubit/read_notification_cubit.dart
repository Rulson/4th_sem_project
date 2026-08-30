import 'package:flutter/widgets.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/modules/dahboard/screen/notification/bloc/read_notification_cubit/read_notification_state.dart';

import '../../../../../../core/common/app_state.dart';
import '../../../../repo/home_repo.dart' show HomeRepo;
import '../notification_list_cubit.dart';




class ReadNotificationCubit extends Cubit<ReadNotificationState> {
  ReadNotificationCubit() : super(ReadNotificationState.initial());

  void markAllAsReadNotification({required BuildContext context}) async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<HomeRepo>().markAsReadNotification(null);
    emit(res.fold((l) => state.copyWith(isLoading: AppState.error, message: l),
        (r) {
      context.read<NotificationListCubit>().markAllNotificationsAsRead();
      return state.copyWith(
          message: r.message, isLoading: AppState.success, result: r);
    }));
  }

  void markAsReadNotification(
      {required Map<String, dynamic> param,
      required BuildContext context}) async {
    emit(state.copyWith(isLoading: AppState.loading));
    final res = await sl<HomeRepo>().markAsReadNotification(param);
    emit(res.fold((l) => state.copyWith(isLoading: AppState.error, message: l),
        (r) {
      context.read<NotificationListCubit>().markNotificationAsRead(param['id']);
      return state.copyWith(
          message: r.message, isLoading: AppState.success, result: r);
    }));
  }

  void resetState() {
    emit(ReadNotificationState.initial());
  }
}