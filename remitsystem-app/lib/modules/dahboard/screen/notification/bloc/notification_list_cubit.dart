import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/modules/dahboard/screen/notification/bloc/notification_list_state.dart';

import '../../../../../core/common/app_state.dart';
import '../../../../../core/locator/locator.dart';
import '../../../repo/home_repo.dart';
import '../model/notification_model.dart';

class NotificationListCubit extends Cubit<NotificationListState> {
  NotificationListCubit() : super(NotificationListState.initial());

  final int _limit = 10;
  int _currentPage = 1;
  bool _hasMore = true;
  bool _isFetching = false;

  void resetState() {
    _currentPage = 1;
    _hasMore = true;
    emit(NotificationListState.initial());
    getNotificationList();
  }

  Future<void> getNotificationList({bool isLoadMore = false}) async {
    if (_isFetching) return;
    if (isLoadMore && !_hasMore) return;

    _isFetching = true;

    final params = {
      'per_page': _limit,
      'page': _currentPage,
    };

    final res = await sl<HomeRepo>().getNotificationList(params);

    res.fold(
      (l) {
        _isFetching = false;

        emit(state.copyWith(
          notificationListLoading: AppState.error,
          message: l,
        ));
      },
      (r) {
        _isFetching = false;

        final List<NotificationData> newList = r.data?.response?.data ?? <NotificationData>[];

        final List<NotificationData> updatedList = isLoadMore ? [...(state.notificationData?.response?.data ?? <NotificationData>[]), ...newList] : newList;

        _hasMore = newList.length == _limit;
        if (_hasMore) _currentPage++;

        emit(state.copyWith(
          notificationListLoading: AppState.success,
          notificationData: updatedList.isEmpty ? null : r.data?.copyWith(response: r.data?.response?.copyWith(data: updatedList)),
          message: r.message,
        ));
      },
    );
  }

  void loadMore() {
    getNotificationList(isLoadMore: true);
  }

  void markNotificationAsRead(String? notificationId) {
    if (notificationId == null) return;

    final notificationData = state.notificationData;
    final response = notificationData?.response;
    final notifications = response?.data;

    if (notificationData == null || response == null || notifications == null) {
      return;
    }

    var didUpdate = false;
    final updatedNotifications = notifications.map((notification) {
      if (notification.id == notificationId && notification.readAt == null) {
        didUpdate = true;
        return notification.copyWith(readAt: DateTime.now().toIso8601String());
      }

      return notification;
    }).toList();

    if (!didUpdate) return;

    final unreadCount = notificationData.count?.unreadCount;

    emit(state.copyWith(
      notificationData: notificationData.copyWith(
        response: response.copyWith(data: updatedNotifications),
        count: notificationData.count?.copyWith(
          unreadCount: unreadCount == null
              ? null
              : unreadCount > 0
                  ? unreadCount - 1
                  : 0,
        ),
      ),
    ));
  }

  void markAllNotificationsAsRead() {
    final notificationData = state.notificationData;
    final response = notificationData?.response;
    final notifications = response?.data;

    if (notificationData == null || response == null || notifications == null) {
      return;
    }

    final now = DateTime.now().toIso8601String();
    final updatedNotifications = notifications.map((notification) {
      return notification.readAt == null ? notification.copyWith(readAt: now) : notification;
    }).toList();

    emit(state.copyWith(
      notificationData: notificationData.copyWith(
        response: response.copyWith(data: updatedNotifications),
        count: notificationData.count?.copyWith(unreadCount: 0),
      ),
    ));
  }

  bool get hasMore => _hasMore;
}
