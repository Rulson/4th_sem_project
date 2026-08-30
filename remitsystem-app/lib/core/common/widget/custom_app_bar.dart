import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class CustomAppBar extends StatelessWidget implements PreferredSizeWidget {
  final bool centerTitle;
  final String? title;
  final Function()? backButtonTap;
  final List<Widget>? actions;
  final bool hideBackButton;
  const CustomAppBar({
    super.key,
    this.centerTitle = false,
    this.title = '',
    this.backButtonTap,
    this.actions,
    this.hideBackButton = false,
  });

  @override
  Widget build(BuildContext context) {
    return AppBar(
      centerTitle: centerTitle,
      surfaceTintColor: Colors.transparent,
      leading: hideBackButton
          ? null
          : GestureDetector(
              onTap: backButtonTap ?? () => context.pop(),
              child: Row(
                children: [
                  16.sBWw,
                  Icon(
              Icons.arrow_back_ios_new,
              size: 18.sp,
              color: AppColor.fgBrand,
            ),
            4.sBWw,
            Text(
              'Back',
              style: AppText.titleMedium500.copyWith(
                color: AppColor.fgBrand,
              ),
            ),
          ],
        ),
      ),
      title: Text(
        title ?? "",
        style: AppText.titleMedium500.copyWith(
          color: AppColor.textHeading,
        ),
      ),
      titleSpacing: 0,
      actions: actions,
      actionsPadding: EdgeInsets.only(right: 16.w),
      leadingWidth: 100.w,
    );
  }

  @override
  Size get preferredSize => Size(double.infinity, 80);
}
