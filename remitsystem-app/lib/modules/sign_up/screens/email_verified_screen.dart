import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class EmailVerifiedScreen extends StatelessWidget {
  const EmailVerifiedScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const EmailVerifiedView();
  }
}

class EmailVerifiedView extends StatelessWidget {
  const EmailVerifiedView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Padding(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Image.asset(
                SAppAssets.imageEmailSuccess,
                height: 160.h,
              ),
              16.sBHh,
              Text(
                "Email Verified",
                style: AppText.headlineSmall700,
              ),
              6.sBHh,
              Text(
                "Your email address has been verified \nsuccessfully",
                style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                textAlign: TextAlign.center,
              ),
              100.sBHh,
              AppButton(
                onPressed: () {
                  context.go(AppRoutes.accountSetup);
                },
                title: "Account Setup",
                trailingIcon: SAppAssets.iconArrowRight,
              )
            ],
          ),
        ),
      ),
    );
  }
}
