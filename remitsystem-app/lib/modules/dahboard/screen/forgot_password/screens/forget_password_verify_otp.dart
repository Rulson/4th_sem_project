import 'dart:async';

import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:pin_code_fields/pin_code_fields.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_state.dart';

class ForgotPasswordVerifyOtp extends StatelessWidget {
  final String email;

  const ForgotPasswordVerifyOtp({
    super.key,
    required this.email,
  });

  @override
  Widget build(BuildContext context) {
    return ForgotPasswordVerifyOtpView(email: email);
  }
}

class ForgotPasswordVerifyOtpView extends StatefulWidget {
  final String email;

  const ForgotPasswordVerifyOtpView({
    super.key,
    required this.email,
  });

  @override
  State<ForgotPasswordVerifyOtpView> createState() => _ForgotPasswordVerifyOtpViewState();
}

class _ForgotPasswordVerifyOtpViewState extends State<ForgotPasswordVerifyOtpView> {
  TextEditingController otpController = TextEditingController();
  int remainingTime = 30;
  late Timer _timer;
  bool canResend = false;

  @override
  void initState() {
    super.initState();
    startTimer();
  }

  void resendCode() {
    if (!canResend) return;
    setState(() {
      remainingTime = 30;
      canResend = false;
    });
    startTimer();
    context.read<ForgotPasswordCubit>().sendOtp(widget.email);
  }

  void startTimer() {
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (remainingTime > 0) {
        setState(() {
          remainingTime--;
        });
      } else {
        setState(() {
          canResend = true;
          _timer.cancel();
        });
      }
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ForgotPasswordCubit, ForgotPasswordState>(
      listener: (context, state) {
        //TODO: verify otp should be handled before navigating to reset password screen
        // if (state.isOtpVerified) {
        //   context.go(
        //     AppRoutes.forgotPasswordReset,
        //     extra: {
        //       'email': widget.email,
        //       'otp': state.otp ?? otpController.text,
        //     },
        //   );
        // } else if (state.isLoading == AppState.error) {
        //   AppSnackbar.showSnackBar(
        //     context: context,
        //     message: state.message ?? "Failed to verify OTP",
        //     type: SnackBarType.error,
        //   );
        // }
      },
      builder: (context, state) {
        return Scaffold(
          appBar: AppBar(
            leading: SizedBox(
              child: GestureDetector(
                onTap: () {
                  if (context.canPop()) {
                    context.pop();
                  } else {
                    context.go(AppRoutes.signin);
                  }
                },
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: SvgPicture.asset(height: 10, width: 10, SAppAssets.iconArrowBack),
                ),
              ),
            ),
            backgroundColor: AppColor.white,
          ),
          body: Stack(
            children: [
              SizedBox(
                height: Utils.screenHeight(context),
                child: SingleChildScrollView(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(
                          height: Utils.statusBarHeight(context),
                        ),
                        Text(
                          "Forgot Password?",
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 32),
                        ),
                        20.sBHh,
                        Text("Don't worry! it happens. Please enter the code sent to "),
                        Text(Utils.hideText(widget.email)),
                        20.sBHh,
                        PinCodeTextField(
                          appContext: context,
                          length: 6,
                          controller: otpController,
                          autoFocus: true,
                          obscureText: false,
                          animationType: AnimationType.fade,
                          keyboardType: TextInputType.number,
                          pinTheme: PinTheme(
                            shape: PinCodeFieldShape.box,
                            borderRadius: BorderRadius.circular(5),
                            fieldHeight: 50,
                            fieldWidth: 45,
                            activeFillColor: Colors.white,
                            inactiveFillColor: Colors.white,
                            inactiveColor: Colors.black26,
                            selectedColor: AppColor.primary,
                            activeColor: AppColor.primary,
                          ),
                          onChanged: (value) {},
                        ),
                        15.sBHh,
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            RichText(
                              text: TextSpan(
                                children: [
                                  TextSpan(
                                    text: 'Didn\'t receive the code? ',
                                    style: TextStyle(color: AppColor.black),
                                  ),
                                  TextSpan(
                                    style: TextStyle(color: AppColor.primary),
                                    recognizer: TapGestureRecognizer()
                                      ..onTap = () {
                                        resendCode();
                                      },
                                    text: 'Resend it',
                                  ),
                                ],
                              ),
                            ),
                            if (remainingTime != 0)
                              Row(
                                children: [
                                  SvgPicture.asset(SAppAssets.iconClock),
                                  4.sBWw,
                                  Text(Utils.formatDuration(remainingTime)),
                                ],
                              ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ),
              Positioned(
                left: 0,
                right: 0,
                bottom: 20,
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                  child: AppButton(
                    isDisabled: state.isLoading == AppState.loading,
                    onPressed: () {
                      final email = (widget.email ?? '').toLowerCase().trim();
                      if (otpController.text.length == 6) {
                        context.pushNamed(
                          AppRoutes.forgotPasswordReset,
                          extra: {
                            'email': email,
                            'otp': state.otp ?? otpController.text,
                          },
                        );
                        // context.read<ForgotPasswordCubit>().verifyOtp(
                        //       email,
                        //       otpController.text,
                        //     );
                      } else {
                        AppSnackbar.showSnackBar(
                          context: context,
                          message: "Please enter a valid OTP",
                          type: SnackBarType.error,
                        );
                      }
                    },
                    title: 'Next',
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
