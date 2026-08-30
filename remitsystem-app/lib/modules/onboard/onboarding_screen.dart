import 'package:flutter/material.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/utils/local_db.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  OnboardingScreenState createState() => OnboardingScreenState();
}

class OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _imagesController = PageController();
  int currentPage = 0;

  final List<Map<String, String>> _onboardingData = [
    {
      'title': 'Save Smart, Earn More Rewards',
      'description': 'Build savings automatically and earn rewards on every transfer.',
      'image': SAppAssets.imageImageOnboarding1,
    },
    {
      'title': 'Transfer Money Fast and Securely',
      'description': 'Send money effortlessly to anyone, anytime, from your phone.',
      'image': SAppAssets.imageImageOnboarding2,
    },
    {
      'title': 'Secure Transfers You Can Trust',
      'description': 'Advanced protection keeps every transaction safe and reliable.',
      'image': SAppAssets.imageImageOnboarding3,
    },
  ];

  @override
  void initState() {
    super.initState();
    _imagesController.addListener(() {
      final page = _imagesController.page?.round() ?? 0;
      if (page != currentPage) {
        setState(() => currentPage = page);
      }
    });
  }

  @override
  void dispose() {
    _imagesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final screenHeight = MediaQuery.of(context).size.height;
    final screenWidth = MediaQuery.of(context).size.width;
    final horizontalPadding = screenWidth * 0.06;

    return Scaffold(
      body: SafeArea(
        child: Column(
          children: [
            40.sBHh,
            Expanded(
              child: AnimatedBuilder(
                animation: _imagesController,
                builder: (context, _) {
                  final page = _imagesController.hasClients && _imagesController.page != null ? _imagesController.page! : currentPage.toDouble();

                  return Column(
                    children: [
                      16.sBHh,
                      SizedBox(
                        height: screenHeight * 0.18,
                        child: Stack(
                          children: List.generate(_onboardingData.length, (index) {
                            final offset = page - index;
                            final opacity = (1 - offset.abs()).clamp(0.0, 1.0);
                            final translateX = offset * screenWidth;

                            return Opacity(
                              opacity: opacity,
                              child: Transform.translate(
                                offset: Offset(-translateX, 0),
                                child: Padding(
                                  padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.center,
                                    children: [
                                      Text(
                                        _onboardingData[index]['title']!,
                                        textAlign: TextAlign.center,
                                        style: AppText.titleExtraLargeBold.copyWith(
                                          color: AppColor.gray900,
                                          fontWeight: FontWeight.w800,
                                        ),
                                      ),
                                      12.sBHh,
                                      Text(
                                        _onboardingData[index]['description']!,
                                        textAlign: TextAlign.center,
                                        style: AppText.bodyMedium400.copyWith(
                                          color: AppColor.textBody,
                                          fontSize: screenHeight < 700 ? 13 : 14,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            );
                          }),
                        ),
                      ),

                      // Image PageView
                      Expanded(
                        child: PageView.builder(
                          controller: _imagesController,
                          onPageChanged: (index) => setState(() => currentPage = index),
                          itemCount: _onboardingData.length,
                          itemBuilder: (context, index) {
                            return Center(
                              child: SizedBox(
                                width: screenWidth * 0.75,
                                child: Image.asset(
                                  _onboardingData[index]['image']!,
                                  fit: BoxFit.contain,
                                ),
                              ),
                            );
                          },
                        ),
                      ),

                      // Page indicator
                      Padding(
                        padding: EdgeInsets.symmetric(vertical: screenHeight * 0.025),
                        child: SmoothPageIndicator(
                          controller: _imagesController,
                          count: _onboardingData.length,
                          effect: ExpandingDotsEffect(
                            activeDotColor: AppColor.primary,
                            dotColor: AppColor.gray300,
                            dotHeight: screenHeight < 700 ? 6 : 8,
                            dotWidth: screenHeight < 700 ? 6 : 8,
                            expansionFactor: 3,
                          ),
                        ),
                      ),
                    ],
                  );
                },
              ),
            ),

            // Bottom buttons + terms
            Padding(
              padding: EdgeInsets.fromLTRB(
                horizontalPadding,
                0,
                horizontalPadding,
                screenHeight * 0.02,
              ),
              child: Column(
                children: [
                  AppButton(
                    height: screenHeight < 700 ? 48 : 54,
                    onPressed: () async {
                      await LocalDb.saveData(key: AppStringConst.isOnboardingSeen, value: true);
                      if (context.mounted) context.push(AppRoutes.signup);
                    },
                    title: "Get Started",
                    trailingIcon: SAppAssets.iconArrowRight,
                  ),
                  12.sBHh,
// Secondary CTA
                  AppButton(
                    height: screenHeight < 700 ? 48 : 54,
                    variant: AppButtonVariant.outline,
                    onPressed: () async {
                      await LocalDb.saveData(key: AppStringConst.isOnboardingSeen, value: true);
                      if (context.mounted) context.go(AppRoutes.signin);
                    },
                    title: "Already have an Account",
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
