import 'dart:async';

import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:pin_code_fields/pin_code_fields.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_state.dart';
import 'package:remit_management/modules/sign_up/bloc/email_activation_state.dart';
import 'package:remit_management/modules/sign_up/bloc/resend_otp_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/resend_otp_state.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';

import '../bloc/email_activation_cubit.dart';

class VerifyEmailOtpScreen extends StatelessWidget {
  final String? pageType;
  const VerifyEmailOtpScreen({super.key, this.pageType});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (context) => ResendOtpCubit(),
        ),
        BlocProvider(
          create: (context) => EmailActivationCubit(),
        ),
      ],
      child: VerifyEmailOtpView(pageType: pageType),
    );
  }
}

class VerifyEmailOtpView extends StatefulWidget {
  final String? pageType;
  const VerifyEmailOtpView({super.key, this.pageType});

  @override
  State<VerifyEmailOtpView> createState() => _VerifyEmailOtpViewState();
}

class _VerifyEmailOtpViewState extends State<VerifyEmailOtpView> {
  final TextEditingController otpController = TextEditingController();
  final ValueNotifier<int> remainingTime = ValueNotifier(30);
  final ValueNotifier<bool> canResend = ValueNotifier(false);
  late Timer _timer;

  @override
  void initState() {
    super.initState();
    startTimer();
  }

  void resendCode() {
    if (!canResend.value) return;
    final email = context.read<CreateAccountCubit>().state.email;
    if (email != null) {
      if (widget.pageType == AppRoutes.emailActivation) {
        context.read<ResendOtpCubit>().resendEmailActivationCode(email);
      } else {
        context.read<ResendOtpCubit>().resendOtp(email);
      }
    }
    remainingTime.value = 30;
    canResend.value = false;
    startTimer();
  }

  void startTimer() {
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (remainingTime.value > 0) {
        remainingTime.value--;
      } else {
        canResend.value = true;
        _timer.cancel();
      }
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    otpController.dispose();
    remainingTime.dispose();
    canResend.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    // print("Page type: ${widget.pageType}");
    return Scaffold(
      appBar: CustomAppBar(
          // title: "Verify Email",
          ),
      body: MultiBlocListener(
        listeners: [
          // for resend otp
          BlocListener<ResendOtpCubit, ResendOtpState>(
            listener: (context, state) {
              if (state.isLoading == AppState.success) {
                AppSnackbar.showSnackBar(
                    context: context,
                    message: state.message ?? "OTP resent successfully",
                    type: SnackBarType.success);
                context.read<ResendOtpCubit>().resetState();
              } else if (state.isLoading == AppState.error) {
                AppSnackbar.showSnackBar(
                    context: context,
                    message: state.message ?? "Failed to resend OTP",
                    type: SnackBarType.error);
                context.read<ResendOtpCubit>().resetState();
              }
            },
          ),

          // to verify email after signup and navigate to email activated screen
          BlocListener<EmailActivationCubit, EmailActivationState>(
            listener: (context, state) {
              if (state.isLoading == AppState.success) {
                AppSnackbar.showSnackBar(
                    context: context,
                    message: state.message ?? "Email activated successfully",
                    type: SnackBarType.success);
                context.read<EmailActivationCubit>().resetState();
                context.go(AppRoutes.emailActivation);
              } else if (state.isLoading == AppState.error) {
                AppSnackbar.showSnackBar(
                    context: context,
                    message: state.message ?? "Failed to activate email",
                    type: SnackBarType.error);
                context.read<EmailActivationCubit>().resetState();
              }
            },
          ),
        ],
        child: BlocConsumer<CreateAccountCubit, CreateAccountState>(
          listener: (context, state) {
            if (state.verifyStatus == true &&
                state.isVerifyLoading == AppState.success) {
              AppSnackbar.showSnackBar(
                  context: context,
                  message: "Otp verified successfully",
                  type: SnackBarType.success);
              context.read<CreateAccountCubit>().resetVerifyLoading();
              context.push(AppRoutes.emailVerified);
            } else if (state.isVerifyLoading == AppState.error &&
                state.verifyStatus == false) {
              AppSnackbar.showSnackBar(
                  context: context,
                  message: "Please check your otp and try again",
                  type: SnackBarType.error);
              context.read<CreateAccountCubit>().resetVerifyLoading();
            }
          },
          builder: (context, state) {
            return Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  24.sBHh,

                  // Title row with verified badge
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        widget.pageType == AppRoutes.emailActivation
                            ? "Email Activation"
                            : "Verify Account",
                        style: AppText.headlineMedium400.copyWith(
                          color: AppColor.gray900,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                      Icon(Icons.verified,
                          color: Color(0xFF09b285), size: 42.sp),
                    ],
                  ),
                  8.sBHh,
                  Text(
                    "Input the 6-digit code sent to",
                    style:
                        AppText.bodyMedium400.copyWith(color: AppColor.gray600),
                  ),
                  Text(
                    Utils.hideText(state.email ?? ""),
                    style:
                        AppText.bodyMedium500.copyWith(color: AppColor.gray800),
                  ),
                  32.sBHh,

                  // OTP fields
                  PinCodeTextField(
                    appContext: context,
                    length: 6,
                    controller: otpController,
                    autoDisposeControllers: false,
                    autoFocus: true,
                    obscureText: false,
                    animationType: AnimationType.fade,
                    keyboardType: TextInputType.number,
                    pinTheme: PinTheme(
                      shape: PinCodeFieldShape.box,
                      borderRadius: BorderRadius.circular(12.r),
                      fieldHeight: 50.h,
                      fieldWidth: 50.w,
                      activeFillColor: AppColor.white,
                      inactiveFillColor: AppColor.gray200,
                      selectedFillColor: AppColor.white,
                      inactiveColor: Colors.transparent,
                      selectedColor: AppColor.primary,
                      activeColor: AppColor.primary,
                      borderWidth: 1.5,
                    ),
                    enableActiveFill: true,
                    textStyle: AppText.headlineSmall700
                        .copyWith(color: AppColor.gray900),
                    onChanged: (value) {},
                  ),
                  20.sBHh,

                  ListenableBuilder(
                    listenable: Listenable.merge([remainingTime, canResend]),
                    builder: (context, _) {
                      final showTimer = remainingTime.value > 0;
                      return Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: "Didn't get the code? ",
                                  style: AppText.bodySmall400
                                      .copyWith(color: AppColor.gray700),
                                ),
                                TextSpan(
                                  text: "Resend it",
                                  style: AppText.bodySmall600.copyWith(
                                    color: canResend.value
                                        ? AppColor.primary
                                        : AppColor.gray400,
                                  ),
                                  recognizer: TapGestureRecognizer()
                                    ..onTap = resendCode,
                                ),
                              ],
                            ),
                          ),
                          if (showTimer)
                            Row(
                              children: [
                                Icon(Icons.access_time_rounded,
                                    size: 16, color: AppColor.gray500),
                                4.sBWw,
                                Text(
                                  Utils.formatDuration(remainingTime.value),
                                  style: AppText.bodySmall500
                                      .copyWith(color: AppColor.gray700),
                                ),
                              ],
                            ),
                        ],
                      );
                    },
                  ),

                  const Spacer(),

                  BlocBuilder<ResendOtpCubit, ResendOtpState>(
                      builder: (context, rState) {
                    return AppButton(
                      height: 54,
                      trailingIcon: SAppAssets.iconArrowRight,
                      isLoading: rState.isLoading == AppState.loading ||
                          state.isVerifyLoading == AppState.loading ||
                          context
                                  .watch<EmailActivationCubit>()
                                  .state
                                  .isLoading ==
                              AppState.loading,
                      onPressed: () {
                        if (otpController.text.length != 6) {
                          AppSnackbar.showSnackBar(
                              context: context,
                              message: "Please enter a valid 6-digit OTP.",
                              type: SnackBarType.error);
                          return;
                        } else {
                          // email verification after signup and navigate to email activated screen
                          if (widget.pageType == AppRoutes.emailActivation) {
                            context
                                .read<EmailActivationCubit>()
                                .emailActivation(
                                  EmailActivationParam(
                                    email: state.email ?? "",
                                    verificationCode: otpController.text,
                                  ),
                                );
                          } else {
                            context
                                .read<CreateAccountCubit>()
                                .storeOtp(otpController.text);
                            context.read<CreateAccountCubit>().verifyEmailNew(
                                  VerifyEmailNewParam(
                                    email: state.email!,
                                    otp: otpController.text,
                                  ),
                                );
                          }
                        }
                      },
                      title: 'Continue',
                    );
                  }),
                  32.sBHh,
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
