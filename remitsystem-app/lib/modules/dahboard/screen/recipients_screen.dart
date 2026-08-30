import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/receiver/screen/widget/recipients_container_widget.dart';

import '../../../core/utils/app_loader_indicator.dart';

class RecipientsScreen extends StatelessWidget {
  const RecipientsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ReceiverListingCubit, ReceiverListingState>(
      builder: (context, state) {
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            12.sBHh,
            // Search bar
            CustomFormFieldWidget(
              hint: "Search existing receiver",
              prefixIcon: CupertinoIcons.search,
              hideParentBorder: true,
              onChanged: (value) {
                context.read<ReceiverListingCubit>().searchReceiver(value);
              },
            ),
            20.sBHh,

            // Frequent recipients
            RecipientsContainerWidget(
              title: "Frequent Recipients",
             
              onRecipientTap: (ReceiverData data) {
                context.push(AppRoutes.sendMoney, extra: data);
              },
            ),
            20.sBHh,

            Expanded(
              child: Container(
                decoration: BoxDecoration(
                  // color: AppColor.white,
                  borderRadius: BorderRadius.circular(16.r),
                  border: Border.all(color: Color(0xFFE5E7EB)),
                  gradient: LinearGradient(colors: [Color(0xFFF9FAFB), AppColor.white], begin: Alignment.topCenter, end: Alignment.bottomCenter),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: EdgeInsets.fromLTRB(20.w, 20.h, 20.w, 12.h),
                      child: Text(
                        "My Recipients",
                        style: AppText.titleMedium600.copyWith(
                          color: AppColor.gray1000,
                        ),
                      ),
                    ),
                    Expanded(
                      child: BlocBuilder<ReceiverListingCubit, ReceiverListingState>(
                        builder: (context, state) {
                          final items = state.filteredReceiverList.isNotEmpty ? state.filteredReceiverList : state.receiverList?.data ?? [];

                          if (state.isLoading == AppState.loading) {
                            return AppLoaderIndicator();
                          }

                          if (items.isEmpty) {
                            return Center(
                              child: Text(
                                "No receivers found",
                                style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                              ),
                            );
                          }

                          return ListView.separated(
                            padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 4.h),
                            physics: const BouncingScrollPhysics(),
                            itemCount: items.length,
                            separatorBuilder: (_, __) => 16.sBHh,
                            itemBuilder: (context, index) => Recipient(
                              receiver: items[index],
                            ),
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),
            ),
            16.sBHh,
          ],
        );
      },
    );
  }
}

class Recipient extends StatelessWidget {
  final ReceiverData receiver;

  const Recipient({
    super.key,
    required this.receiver,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: () {
        context.push(AppRoutes.recipientDetail, extra: receiver);
      },
      child: Row(
        children: [
          CircularProfileWidget(
            title: receiver.fullName ?? "",
            size: 48,
          ),
          14.sBWw,
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  receiver.fullName ?? "",
                  style: AppText.bodyMedium600.copyWith(color: AppColor.gray900),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                4.sBHh,
                Text(
                  receiver.number ?? "",
                  style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
                ),
              ],
            ),
          ),
          GestureDetector(
            onTap: () => context.push(AppRoutes.sendMoney, extra: receiver),
            child: Container(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 10.h),
              decoration: BoxDecoration(
                color: AppColor.white,
                borderRadius: BorderRadius.circular(400.r),
                border: Border.all(color: AppColor.gray200),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    Icons.arrow_outward,
                    size: 14.sp,
                    color: AppColor.gray700,
                  ),
                  6.sBWw,
                  Text(
                    "Send",
                    style: AppText.bodySmall600.copyWith(color: AppColor.gray700),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
