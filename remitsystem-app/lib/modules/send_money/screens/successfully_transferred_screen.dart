import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';

class SuccessfullyTransferredScreen extends StatelessWidget {
  const SuccessfullyTransferredScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(27.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              SizedBox(
                height: 120,
                width: 120,
                child: Image.asset(SAppAssets.imageTransferCreated),
              ),
              32.sBHh,
              Text(
                "Transfer Created",
                style: TextStyle(
                  fontSize: 28,
                  color: AppColor.primary,
                  fontWeight: FontWeight.w600,
                ),
                textAlign: TextAlign.center,
              ),
              16.sBHh,
              Text(
                "Your transfer has been successfully initiated and is now being processed.",
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 16,
                  color: AppColor.disabledColor2,
                ),
              ),
              32.sBHh,
              SizedBox(
                width: 200,
                child: AppButton(
                  borderRadius: 09999,
                  onPressed: () {
                    context.go(AppRoutes.dashboard);
                  },
                  title: 'Back to Dashboard',
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
