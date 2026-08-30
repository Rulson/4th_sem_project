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

class ForgotPassword extends StatelessWidget {
  final String email;
  final String otp;

  const ForgotPassword({
    super.key,
    required this.email,
    required this.otp,
  });

  @override
  Widget build(BuildContext context) {
    return ForgotPasswordView(
      email: email,
      otp: otp,
    );
  }
}

class ForgotPasswordView extends StatefulWidget {
  final String email;
  final String otp;

  const ForgotPasswordView({
    super.key,
    required this.email,
    required this.otp,
  });

  @override
  State<ForgotPasswordView> createState() => _ForgotPasswordViewState();
}

class _ForgotPasswordViewState extends State<ForgotPasswordView> {
  final newPasswordController = TextEditingController();
  final confirmPasswordController = TextEditingController();
  final formKey = GlobalKey<FormState>();

  @override
  void dispose() {
    newPasswordController.dispose();
    confirmPasswordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: SizedBox(
          child: GestureDetector(
            onTap: () {
              if(context.canPop()) {
                context.pop();
              } else {
                context.go(AppRoutes.signin);
              }
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
            if (state.isPasswordChanged) {
              context.go(AppRoutes.signin);
               AppSnackbar.showSnackBar(
                context: context,
                message: state.message ?? "Password changed successfully",
                type: SnackBarType.success,
              );
            } else if (state.isLoading == AppState.error) {
              AppSnackbar.showSnackBar(
                context: context,
                message: state.message ?? "Failed to change password",
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
                              "Create new password",
                              style: TextStyle(
                                  fontWeight: FontWeight.bold, fontSize: 32),
                            ),
                            Text(
                              "Choose a password",
                            ),
                            20.sBHh,
                            CustomFormFieldWidget(
                              hint: "New Password",
                              controller: newPasswordController,
                              obsecureText: !state.isPasswordVisible,
                              validator: AppValidator.validatePassword,
                              suffixIcon: GestureDetector(
                                onTap: () {
                                  context
                                      .read<ForgotPasswordCubit>()
                                      .togglePasswordVisibility();
                                },
                                child: Icon(
                                  state.isPasswordVisible
                                      ? Icons.visibility
                                      : Icons.visibility_off,
                                ),
                              ),
                            ),
                            20.sBHh,
                            CustomFormFieldWidget(
                              hint: "Confirm Password",
                              controller: confirmPasswordController,
                              obsecureText: !state.isConfirmPasswordVisible,
                              validator: AppValidator.validatePassword,
                              suffixIcon: GestureDetector(
                                onTap: () {
                                  context
                                      .read<ForgotPasswordCubit>()
                                      .toggleConfirmPasswordVisibility();
                                },
                                child: Icon(
                                  state.isConfirmPasswordVisible
                                      ? Icons.visibility
                                      : Icons.visibility_off,
                                ),
                              ),
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
                        final email = (widget.email ?? '').toLowerCase().trim();
                        if (formKey.currentState!.validate()) {
                          context.read<ForgotPasswordCubit>().changePassword(
                               email,
                                widget.otp,
                                newPasswordController.text,
                                confirmPasswordController.text,
                              );
                        }
                      },
                      title: "Reset Password",
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
