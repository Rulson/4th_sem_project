import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/utils/utils.dart';

class ForgotPasswordSucess extends StatelessWidget {
  const ForgotPasswordSucess({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          SizedBox(
            height: Utils.screenHeight(context),
            width: Utils.screenWidth(context),
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  150.sBHh,
                  SizedBox(
                    height: 200,
                    width: 200,
                    child: SvgPicture.asset(SAppAssets.iconSucces),
                  ),
                  64.sBHh,
                  Text("Password created", style: TextStyle(fontSize: 32, color: AppColor.primary, fontWeight: FontWeight.w500)),
                  10.sBHh,
                  Text("Your password has been created", textAlign: TextAlign.center, style: TextStyle(fontSize: 16, color: AppColor.disabledColor2)),
                ],
              ),
            ),
          ),
          Positioned(
            left: 0,
            right: 0,
            bottom: 20,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
              child: Builder(
                builder: (context) => AppButton(
                  onPressed: () {
                    context.push(AppRoutes.signin);
                  },
                  title: 'Back to login',
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
