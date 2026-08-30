import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_state.dart';

class ForgotPasswordSendOtp extends StatelessWidget {
  const ForgotPasswordSendOtp({super.key});

  @override
  Widget build(BuildContext context) {
    return const ForgotPasswordSendOtpView();
  }
}

class ForgotPasswordSendOtpView extends StatelessWidget {
  const ForgotPasswordSendOtpView({super.key});

  @override
  Widget build(BuildContext context) {
    final emailController = TextEditingController();
    final formKey = GlobalKey<FormState>();

    return Scaffold(
      appBar: AppBar(
        leading: SizedBox(
          child: GestureDetector(
            onTap: () {
              context.pop();
            },
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: SvgPicture.asset(
                  height: 10, width: 10, SAppAssets.iconArrowBack),
            ),
          ),
        ),
        backgroundColor: AppColor.white,
      ),
      body: SafeArea(
        child: BlocConsumer<ForgotPasswordCubit, ForgotPasswordState>(
          listener: (context, state) {
            if (state.isLoading == AppState.success) {
              context.go(AppRoutes.forgotPasswordVerifyOtp, extra: state.email);
            } else if (state.isLoading == AppState.error) {
              AppSnackbar.showSnackBar(
                context: context,
                message: state.message ?? "Failed to send OTP",
                type: SnackBarType.error,
              );
            }
          },
          builder: (context, state) {
            return Stack(
              children: [
                SizedBox(
                  height: Utils.screenHeight(context),
                  width: Utils.screenWidth(context),
                  child: SingleChildScrollView(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 20.0, vertical: 10),
                      child: Form(
                        key: formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            SizedBox(
                              height: Utils.statusBarHeight(context),
                            ),
                            Text(
                              "Enter your Email",
                              style: TextStyle(
                                  fontWeight: FontWeight.bold, fontSize: 32),
                            ),
                            Text(
                              "Please enter your email address where you would like to receive the password reset instructions.",
                            ),
                            20.sBHh,
                            CustomFormFieldWidget(
                              hint: "Email",
                              controller: emailController,
                              validator: AppValidator.validateEmail,
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
                Positioned(
                  left: 0,
                  right: 0,
                  bottom: 20,
                  child: Padding(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 20, vertical: 10),
                    child: AppButton(
                      isDisabled: state.isLoading == AppState.loading,
                      onPressed: () {
                        if (formKey.currentState!.validate()) {
                          context
                              .read<ForgotPasswordCubit>()
                              .sendOtp(emailController.text);
                        }
                      },
                      title: "Continue",
                    ),
                  ),
                ),
              ],
            );
          },
        ),
      ),
    );
  }
}
