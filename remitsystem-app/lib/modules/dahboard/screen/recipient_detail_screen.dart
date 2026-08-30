import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/screen/personal_detail_screen.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

class RecipientDetailScreen extends StatelessWidget {
  final ReceiverData receiver;
  const RecipientDetailScreen({super.key, required this.receiver});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColor.white,
      appBar: CustomAppBar(),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Column(
                  children: [
                    CircularProfileWidget(
                      title: receiver.fullName ?? "N/A",
                      size: 64.w,
                    ),
                    12.sBHh,
                    Text(
                      receiver.fullName ?? "N/A",
                      style: AppText.bodyMedium600.copyWith(color: AppColor.gray800, fontSize: 18.sp),
                    ),
                    5.sBHh,
                    Text(
                      receiver.accountNo ?? "N/A",
                      style: AppText.bodySmall400.copyWith(color: AppColor.textBody),
                    ),
                    32.sBHh,
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        InkWell(
                          borderRadius: BorderRadius.circular(12.r),
                          onTap: () {
                            context.push(AppRoutes.addReceiver, extra: {
                              'receiver': receiver,
                              'isEdit': true,
                            }).then((_) {
                              if (context.mounted) {
                                context.read<ReceiverListingCubit>().getReceiverList();
                              }
                            });
                          },
                          child: Container(
                            padding: EdgeInsets.symmetric(vertical: 8.h, horizontal: 12.w),
                            decoration: BoxDecoration(
                              border: Border.all(color: AppColor.gray200),
                              borderRadius: BorderRadius.circular(12.r),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(
                                  Icons.edit_outlined,
                                  color: AppColor.textBody,
                                  size: 16.sp,
                                ),
                                8.sBWw,
                                Text("Edit", style: AppText.bodyMedium500.copyWith(color: AppColor.textBody))
                              ],
                            ),
                          ),
                        ),
                        12.sBWw,
                        InkWell(
                          borderRadius: BorderRadius.circular(12.r),
                          onTap: () {
                            context.push(AppRoutes.sendMoney, extra: receiver);
                          },
                          child: Container(
                            padding: EdgeInsets.symmetric(vertical: 8.h, horizontal: 12.w),
                            decoration: BoxDecoration(
                              border: Border.all(color: AppColor.gray200),
                              borderRadius: BorderRadius.circular(12.r),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Transform.rotate(
                                  angle: -45 * 3.14 / 180,
                                  child: Icon(
                                    Icons.arrow_forward,
                                    color: AppColor.primary,
                                    size: 16.sp,
                                  ),
                                ),
                                8.sBWw,
                                Text("Send", style: AppText.bodyMedium500.copyWith(color: AppColor.primary))
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              32.sBHh,
              Column(
                spacing: 16.h,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Personal Details", style: AppText.bodyMedium600.copyWith(color: AppColor.gray800, fontSize: 16.sp)),
                  ProfileWidget(
                    title: "First Name",
                    value: receiver.fullName?.split(' ').first ?? "N/A",
                  ),
                  ProfileWidget(
                    title: "Last Name",
                    value: receiver.fullName?.split(' ').last ?? "N/A",
                  ),
                  ProfileWidget(
                    title: "Mobile Number",
                    value: receiver.number ?? "N/A",
                  ),
                  1.sBHh,
                  Text("Account Details", style: AppText.bodyMedium600.copyWith(color: AppColor.gray800, fontSize: 16.sp)),
                  ProfileWidget(
                    title: "Account Number",
                    value: receiver.accountNo ?? "N/A",
                  ),
                  ProfileWidget(
                    title: "Account Name",
                    value: receiver.accountName ?? "N/A",
                  ),
                  ProfileWidget(
                    title: "Bank Name",
                    value: receiver.bankName ?? "N/A",
                  ),
                  ProfileWidget(
                    title: "BSB",
                    value: receiver.bsb ?? "N/A",
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
