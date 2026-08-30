import 'package:equatable/equatable.dart';

import '../../../../../core/common/app_state.dart';
import '../model/notification_model.dart';


class NotificationListState extends Equatable {
  final AppState notificationListLoading;
  final NotificationListModel? notificationData;
  final String? message;

  const NotificationListState({required this.notificationListLoading, this.notificationData, this.message});

  NotificationListState copyWith(
      {AppState? notificationListLoading, NotificationListModel? notificationData, String? message}) {
    return NotificationListState(
        notificationListLoading: notificationListLoading ?? this.notificationListLoading,
        notificationData: notificationData ?? this.notificationData,
        message: message);
  }

  factory NotificationListState.initial() {
    return NotificationListState(
      notificationListLoading: AppState.initial,
    );
  }

  @override
  List<Object?> get props => [notificationData, notificationListLoading, message];
}
