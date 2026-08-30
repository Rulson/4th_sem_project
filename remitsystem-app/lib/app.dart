import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:remit_management/core/common/routes/app_router.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';

import 'modules/dahboard/screen/notification/bloc/notification_list_cubit.dart';

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (context) => ProfileCubit()),
        BlocProvider(create: (context) => HomeCubit()),
        BlocProvider(create: (context) => ReceiverListingCubit()),
        BlocProvider(create: (context) => SendMoneyCubit()),
        BlocProvider(create: (context) => CreateAccountCubit()),
        BlocProvider(create: (context) => TransactionListCubit()),
        BlocProvider(
          create: (context) => NotificationListCubit(),
        ),
      ],
      child: ScreenUtilInit(
        useInheritedMediaQuery: true,
        minTextAdapt: true,
        designSize: const Size(390, 844),
        splitScreenMode: true,
        builder: (context, child) {
          return MaterialApp.router(
            debugShowCheckedModeBanner: false,
            title: AppStringConst.appName,
            scaffoldMessengerKey: rootScaffoldMessengerKey,
            theme: ThemeData(
              scaffoldBackgroundColor: AppColor.white,
              colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
              useMaterial3: true,
              textSelectionTheme: TextSelectionThemeData(
                  cursorColor: AppColor.primary,
                  selectionHandleColor: AppColor.primary,
                  selectionColor: AppColor.primary.withValues(alpha: 0.4)),
              appBarTheme: AppBarTheme(
                backgroundColor: Colors.transparent,
              ),
              checkboxTheme: CheckboxThemeData(
                fillColor: WidgetStateProperty.resolveWith((states) {
                  if (states.contains(WidgetState.selected)) {
                    return AppColor.color700;
                  }
                  return AppColor.white;
                }),
              ),
            ),
            routerConfig: router,
            builder: (context, child) {
              final mediaQuery = MediaQuery.of(context);
              final clampedTextScaler = TextScaler.noScaling;

              return MediaQuery(
                data: mediaQuery.copyWith(textScaler: clampedTextScaler),
                child: Stack(
                  children: [
                    Positioned.fill(
                      child: Container(
                        decoration: const BoxDecoration(
                          gradient: LinearGradient(
                            colors: [
                              Color(0xFFEEF0FC),
                              Color.fromARGB(255, 241, 237, 237)
                            ],
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                          ),
                        ),
                      ),
                    ),
                    Positioned(
                      top: -42,
                      left: -7,
                      child: _GradientBlob(
                        width: 182.875,
                        height: 100,
                        color: Color.fromARGB(109, 84, 121, 233),
                        blurSigma: 261.4,
                      ),
                    ),
                    Positioned(
                      top: -57.76,
                      right: -7,
                      child: _GradientBlob(
                        width: 114.50927734375,
                        height: 100,
                        color: Color.fromARGB(108, 199, 76, 171),
                        blurSigma: 261.4,
                      ),
                    ),
                    child!,
                  ],
                ),
              );
            },
          );
        },
      ),
    );
  }
}

class _GradientBlob extends StatelessWidget {
  final double width;
  final double height;
  final Color color;
  final double blurSigma;

  const _GradientBlob({
    required this.width,
    required this.height,
    required this.color,
    required this.blurSigma,
  });

  @override
  Widget build(BuildContext context) {
    return ImageFiltered(
      imageFilter: ImageFilter.blur(sigmaX: blurSigma, sigmaY: blurSigma),
      child: Container(
        width: width,
        height: height,
        decoration: BoxDecoration(
          color: color,
          shape: BoxShape.circle,
        ),
      ),
    );
  }
}
