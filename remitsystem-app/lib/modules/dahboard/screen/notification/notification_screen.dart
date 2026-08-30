import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

import '../../../../core/common/app_state.dart';
import '../../../../core/utils/app_loader_indicator.dart';
import '../../../../core/utils/utils.dart';
import 'bloc/notification_list_cubit.dart';
import 'bloc/notification_list_state.dart';
import 'bloc/read_notification_cubit/read_notification_cubit.dart';
import 'bloc/read_notification_cubit/read_notification_state.dart';


class NotificationScreen extends StatelessWidget {
  const NotificationScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<ReadNotificationCubit>(
          create: (context) => ReadNotificationCubit(),
        ),
        BlocProvider<NotificationListCubit>.value(
          value: context.read<NotificationListCubit>()..resetState(),
        ),
      ],
      child: const NotificationView(),
    );
  }
}

class NotificationView extends StatefulWidget {
  const NotificationView({super.key});

  @override
  State<NotificationView> createState() => _NotificationViewState();
}

class _NotificationViewState extends State<NotificationView> {
  final _scrollController = ScrollController();
  @override
  void initState() {
    super.initState();

    _scrollController.addListener(() {
      if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
        context.read<NotificationListCubit>().loadMore();
      }
    });
  }

  @override
  void dispose() {
    _scrollController.dispose();

    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final state = context.watch<NotificationListCubit>().state;
    return Scaffold(
      // backgroundColor: AppColor.backgroundColor,
      appBar: CustomAppBar(
        centerTitle: false,
        title: "Notification",
        hideBackButton: true,
        actions: [
          GestureDetector(
            onTap: state.notificationData?.count?.unreadCount == 0
                ? null
                : () => context.read<ReadNotificationCubit>().markAllAsReadNotification(context: context),
            child: Padding(
              padding: const EdgeInsets.only(right: 4.0),
              child: Text("Mark all as read", style: AppText.labelMedium500.copyWith(color: AppColor.primary)),
            ),
          ),
          8.sBWw
          //   IconButton(
          //       onPressed: () => context.read<ReadNotificationCubit>().markAllAsReadNotification(context: context),
          //       icon: SvgPicture.asset(SAppAssets.iconMark))
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16),
        child: MultiBlocListener(
          listeners: [
            BlocListener<ReadNotificationCubit, ReadNotificationState>(
              listener: (context, state) {
                if (state.isLoading == AppState.success) {
                  context.read<ReadNotificationCubit>().resetState();
                }
              },
            ),
          ],
          child: BlocBuilder<NotificationListCubit, NotificationListState>(
            builder: (context, state) {
              if (state.notificationListLoading == AppState.error) {
                return Center(
                  child: Text(
                    state.message ?? "Error loading notifications",
                    style: AppText.labelLarge400,
                  ),
                );
              }
              if (state.notificationListLoading == AppState.loading) {
                return const Center(child: AppLoaderIndicator());
              }

              final notifications = state.notificationData?.response?.data ?? [];

              if (notifications.isEmpty && state.notificationListLoading == AppState.success) {
                return Center(
                  child: Text("No Notifications", style: AppText.labelLarge400),
                );
              }

              return ListView.separated(
                controller: _scrollController,
                physics: BouncingScrollPhysics(),
                padding: const EdgeInsets.symmetric(vertical: 16),
                itemCount: notifications.length,
                separatorBuilder: (_, __) => const SizedBox(height: 16),
                itemBuilder: (context, index) {
                  final notification = notifications[index];
                  return GestureDetector(
                    onTap: () => context.read<ReadNotificationCubit>().markAsReadNotification(
                      param: {"id": notification.id},
                      context: context,
                    ),
                    child: NotificationWidget(
                      title: notification.data?.title ?? '#${notification.data?.transactionId ?? ""}',
                      description: notification.data?.message ?? "Transaction created",
                      date: notification.createdAt ?? "",
                      readAt: notification.readAt,
                    ),
                  );
                },
              );
            },
          ),
        ),
      ),
    );
  }
}

class NotificationWidget extends StatelessWidget {
  final String title;
  final String description;
  final String date;
  final String? readAt;
  const NotificationWidget({
    super.key,
    required this.title,
    required this.description,
    required this.date,
    this.readAt,
  });

  bool get isUnread => readAt == null;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // 10.sBHh,
        Container(
          decoration: BoxDecoration(
            color: isUnread ? AppColor.lightBlue : AppColor.transparent,
            borderRadius: BorderRadius.circular(8),
          ),
          padding: EdgeInsets.all(12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Expanded(
                    child: Text(
                      title,
                      style: AppText.bodyMedium400.copyWith(
                        fontSize: 16,
                        fontWeight: isUnread ? FontWeight.w600 : FontWeight.normal,
                        color: isUnread ? AppColor.labelTextColor : AppColor.greyII,
                      ),
                    ),
                  ),
                  if (isUnread)
                    Container(
                      height: 10,
                      width: 10,
                      decoration: BoxDecoration(
                        color: AppColor.primary.withAlpha(200),
                        shape: BoxShape.circle,
                      ),
                      padding: EdgeInsets.all(6),
                    ),
                ],
              ),
              5.sBHh,
              Text(
                description,
                style: AppText.bodyMedium400.copyWith(
                  color: isUnread ? AppColor.labelTextColor : AppColor.grey,
                ),
              ),
              5.sBHh,
              Align(
                alignment: Alignment.bottomRight,
                child: Text(
                  Utils.formatTimeAgo(date),
                  style: AppText.labelMedium500.copyWith(
                    color: isUnread ? AppColor.primary : AppColor.gray400,
                  ),
                ),
              ),
            ],
          ),
        ),
        // 5.sBHh,
        if (!isUnread) Divider(),
      ],
    );
  }
}
