import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_string_const.dart';

import 'package:remit_management/core/utils/local_db.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    final accessToken = await LocalDb.getData(key: AppStringConst.apiToken);
    if (accessToken != null) {
      if (!mounted) return;
      context.go("${AppRoutes.otpPage}/${AppStringConst.validatePin}");
      return;
    }
    final onboardingSeen = await LocalDb.getData(key: AppStringConst.isOnboardingSeen);
    if (onboardingSeen == true) {
      if (!mounted) return;
      context.go(AppRoutes.signin);
      return;
    }
    if (!mounted) return;
    context.go(AppRoutes.onboarding);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: TweenAnimationBuilder<double>(
          tween: Tween<double>(begin: 0.0, end: 1.0),
          duration: const Duration(milliseconds: 800), 
          curve: Curves.easeIn,
          builder: (context, value, child) {
            return Opacity(
              opacity: value,
              child: child,
            );
          },
          child: Image.asset(
            SAppAssets.logoLogo,
            height: MediaQuery.of(context).size.height/2,
            width:  MediaQuery.of(context).size.width/2,
          
          ),
        ),
      ),
    );
  }
}
