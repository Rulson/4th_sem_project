import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:new_version_plus/new_version_plus.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/local_db.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_state.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_state.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';

import '../../../core/common/widget/app_confirmation_dialog.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProfileCubit, ProfileState>(
      builder: (context, state) {
        final fullName = state.profileModel?.data?.fullName ?? "N/A";
        final email = state.profileModel?.data?.email ?? "N/A";

        return SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              20.sBHh,

              // Profile card
              Container(
                padding: EdgeInsets.all(20.w),
                decoration: BoxDecoration(
                  color: AppColor.white,
                  borderRadius: BorderRadius.circular(16.r),
                  border: Border.all(color: AppColor.gray100),
                ),
                child: Column(
                  children: [
                    // Avatar + email + verified
                    Row(
                      children: [
                        CircularProfileWidget(title: fullName, size: 56),
                        16.sBWw,
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(email, style: AppText.bodyMedium600.copyWith(color: AppColor.gray900)),
                            8.sBHh,
                            Container(
                              padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 4.h),
                              decoration: BoxDecoration(
                                color: const Color(0xFFE8F5E9),
                                borderRadius: BorderRadius.circular(400.r),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Container(
                                    width: 7,
                                    height: 7,
                                    decoration: const BoxDecoration(shape: BoxShape.circle, color: Color(0xFF4CAF50)),
                                  ),
                                  6.sBWw,
                                  Text(
                                    "Verified",
                                    style: AppText.bodySmall500.copyWith(color: const Color(0xFF4CAF50)),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    20.sBHh,
                    Divider(color: AppColor.gray100, height: 1),
                    20.sBHh,
                    // Stats row
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceAround,
                      children: [
                        BlocBuilder<TransactionListCubit, TransactionListState>(
                          builder: (context, state) {
                            return _StatWidget(
                              value: state.transactionList?.data?.length.toString() ?? "0",
                              unit: "TXNs",
                              label: "COMPLETED",
                            );
                          },
                        ),
                        Container(width: 1, height: 32, color: AppColor.gray200),
                        // _StatWidget(
                        //   value: Utils.formatExpiryDate("${state.profileModel?.data?.expiryDate ?? "N/A"}"),
                        //   unit: "",
                        //   label: "Expires in",
                        // ),
                        _StatWidget(
                          value: "\$${context.read<HomeCubit>().state.homeModel?.data?.allTimeAu ?? "N/A"}",
                          unit: "Aud",
                          label: "Total",
                        ),
                        Container(width: 1, height: 32, color: AppColor.gray200),
                        _StatWidget(
                          value: state.profileModel?.data?.createdAt != null
                              ? "${DateTime.now().difference(state.profileModel!.data!.createdAt!).inDays}"
                              : "N/A",
                          unit: "/Day",
                          label: "MEMBER SINCE",
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              20.sBHh,

              // Quick Actions card
              Container(
                padding: EdgeInsets.all(20.w),
                decoration: BoxDecoration(
                  color: AppColor.white,
                  borderRadius: BorderRadius.circular(16.r),
                  border: Border.all(color: AppColor.gray100),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text("Quick Actions", style: AppText.bodyMedium600.copyWith(color: AppColor.gray900)),
                    16.sBHh,
                    Row(
                      children: [
                        Expanded(
                          child: _QuickActionWidget(
                            icon: SAppAssets.iconProfile,
                            label: "Refer",
                            onTap: () => context.push(AppRoutes.referralCode),
                          ),
                        ),
                        12.sBWw,
                        Expanded(
                          child: _QuickActionWidget(
                            icon: SAppAssets.iconInfo,
                            label: "Help",
                            onTap: () => context.push(AppRoutes.aboutUs),
                          ),
                        ),
                        12.sBWw,
                        Expanded(
                          child: _QuickActionWidget(
                            icon: SAppAssets.iconChangePwd,
                            label: "Security",
                            onTap: () => context.push(AppRoutes.changePassword),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              20.sBHh,

              // Essentials card
              Container(
                decoration: BoxDecoration(
                  color: AppColor.white,
                  borderRadius: BorderRadius.circular(16.r),
                  border: Border.all(color: AppColor.gray100),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: EdgeInsets.fromLTRB(20.w, 20.h, 20.w, 12.h),
                      child: Text("Essentials", style: AppText.bodyMedium600.copyWith(color: AppColor.gray900)),
                    ),
                    _MoreItem(
                      title: 'Personal detail',
                      icon: SAppAssets.iconProfile,
                      onTap: () => context.push(AppRoutes.personalDetails),
                    ),
                    _MoreItem(
                      title: 'About Us',
                      icon: SAppAssets.iconInfo,
                      onTap: () => context.push(AppRoutes.aboutUs),
                    ),
                    _MoreItem(
                      title: 'Change Password',
                      icon: SAppAssets.iconChangePwd,
                      onTap: () => context.push(AppRoutes.changePassword),
                    ),
                    _MoreItem(
                      title: 'Check for Updates',
                      icon: SAppAssets.iconCheckUpdates,
                      onTap: () async {
                        try {
                          final newVersionPlus = NewVersionPlus(androidId: 'com.RemitSystem.remit');
                          final status = await newVersionPlus.getVersionStatus();
                          if (status != null && status.canUpdate) {
                            if (!context.mounted) return;
                            newVersionPlus.showUpdateDialog(
                              context: context,
                              versionStatus: status,
                              dialogTitle: 'Update Available',
                              dialogText: 'A new version of the app is available. Please update to the latest version.',
                              updateButtonText: 'Update Now',
                              dismissButtonText: 'Later',
                            );
                          } else {
                            if (!context.mounted) return;
                            AppSnackbar.showSnackBar(
                              context: context,
                              message: 'You are already using the latest version.',
                              type: SnackBarType.success,
                            );
                          }
                        } catch (e) {
                          AppSnackbar.showSnackBar(
                            context: context,
                            message: 'Unable to check for updates. Please try again later.',
                            type: SnackBarType.error,
                          );
                        }
                      },
                    ),
                    _MoreItem(
                      title: 'Log Out',
                      icon: SAppAssets.iconLogout,
                      isDestructive: true,
                      onTap: () {
                        AppConfirmationDialog.show(
                          context: context,
                          title: 'Are you sure?',
                          content: 'Do you want to logout?',
                          yesText: 'Yes',
                          noText: 'No',
                          onYes: () async {
                            await LocalDb.removeToken().then((_) {
                              if (context.mounted) context.pop();
                              if (context.mounted) context.go(AppRoutes.signin);
                            });
                          },
                          onNo: () => context.pop(),
                          icon: Icons.logout_rounded,
                        );

                        // showDialog(
                        //   context: context,
                        //   builder: (context) => AlertDialog(
                        //     title: const Text('Are you sure?'),
                        //     content: const Text('Do you want to logout?'),
                        //     actions: [
                        //       TextButton(
                        //         onPressed: () => context.pop(),
                        //         child: const Text('No'),
                        //       ),
                        //       TextButton(
                        //         onPressed: () async {
                        //           await LocalDb.removeToken().then((_) {
                        //             if (context.mounted) context.pop();
                        //             if (context.mounted) context.go(AppRoutes.signin);
                        //           });
                        //         },
                        //         child: const Text('Yes'),
                        //       ),
                        //     ],
                        //   ),
                        // );
                      },
                    ),
                  ],
                ),
              ),
              24.sBHh,
            ],
          ),
        );
      },
    );
  }
}

class _StatWidget extends StatelessWidget {
  final String value;
  final String unit;
  final String label;

  const _StatWidget({required this.value, required this.unit, required this.label});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.baseline,
          textBaseline: TextBaseline.alphabetic,
          children: [
            Text(
              value,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: AppText.headlineSmall700.copyWith(color: AppColor.primary600),
            ),
            Text(unit, style: AppText.bodySmall500.copyWith(color: AppColor.gray500)),
          ],
        ),
        4.sBHh,
        Text(label, style: AppText.labelMedium400.copyWith(color: AppColor.gray500, fontSize: 10, letterSpacing: 0.5)),
      ],
    );
  }
}

class _QuickActionWidget extends StatelessWidget {
  final String icon;
  final String label;
  final VoidCallback onTap;

  const _QuickActionWidget({required this.icon, required this.label, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(vertical: 12.h, horizontal: 12.w),
        decoration: BoxDecoration(
          color: AppColor.gray50,
          borderRadius: BorderRadius.circular(12.r),
          border: Border.all(color: AppColor.gray200),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SvgPicture.asset(
              icon,
              width: 24.w,
              colorFilter: const ColorFilter.mode(AppColor.textBodySubtle, BlendMode.srcIn),
            ),
            8.sBHh,
            Text(label, style: AppText.bodySmall500.copyWith(color: AppColor.textBody)),
          ],
        ),
      ),
    );
  }
}

class _MoreItem extends StatelessWidget {
  final String title;
  final String icon;
  final VoidCallback? onTap;
  final bool isDestructive;

  const _MoreItem({required this.title, required this.icon, this.onTap, this.isDestructive = false});

  @override
  Widget build(BuildContext context) {
    final color = isDestructive ? AppColor.errorRed : AppColor.gray700;

    return Column(
      children: [
        Divider(height: 1, color: AppColor.gray100),
        InkWell(
          onTap: onTap,
          child: Padding(
            padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 16.h),
            child: Row(
              children: [
                SvgPicture.asset(icon, width: 20.w, colorFilter: ColorFilter.mode(color, BlendMode.srcIn)),
                12.sBWw,
                Expanded(
                  child: Text(title, style: AppText.bodyMedium500.copyWith(color: color)),
                ),
                Icon(
                  Icons.arrow_forward_ios_rounded,
                  size: 14.sp,
                  color: isDestructive ? AppColor.errorRed : AppColor.gray400,
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
