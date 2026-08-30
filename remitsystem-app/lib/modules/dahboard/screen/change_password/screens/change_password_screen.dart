import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/bloc/change_password_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/bloc/change_password_state.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/models/change_password_param.dart';

class ChangePasswordScreen extends StatelessWidget {
  const ChangePasswordScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => ChangePasswordCubit(),
      child: const ChangePasswordView(),
    );
  }
}

class ChangePasswordView extends StatefulWidget {
  const ChangePasswordView({super.key});

  @override
  State<ChangePasswordView> createState() => _ChangePasswordViewState();
}

class _ChangePasswordViewState extends State<ChangePasswordView> {
  final _formKey = GlobalKey<FormState>();
  final _oldPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  @override
  void dispose() {
    _oldPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColor.white,
      appBar: const CustomAppBar(),
      body: BlocConsumer<ChangePasswordCubit, ChangePasswordState>(
        listener: (context, state) {
          if (state.isLoading == AppState.success) {
            AppSnackbar.showSnackBar(
              context: context,
              message: state.message ?? "Password changed successfully",
              type: SnackBarType.success,
            );
            context.read<ChangePasswordCubit>().resetState();
            Navigator.pop(context);
          } else if (state.isLoading == AppState.error) {
            AppSnackbar.showSnackBar(
              context: context,
              message: state.message ?? "Failed to change password",
              type: SnackBarType.error,
            );
            context.read<ChangePasswordCubit>().resetState();
          }
        },
        builder: (context, state) {
          return Form(
            key: _formKey,
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Change Password",
                    style: AppText.headlineMedium400.copyWith(
                      color: AppColor.gray1000,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  8.sBHh,
                  Text(
                    "Please create a new secure password that’s unique and memorable.",
                    style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                  ),
                  // 16.sBHh,
                  // CustomFormFieldWidget(
                  //   title: "Old Password",
                  //   controller: _oldPasswordController,
                  //   obsecureText: !state.isOldPasswordVisible,
                  //   validator: (value) => AppValidator.validatePassword(_oldPasswordController.text),
                  //   suffixIcon: GestureDetector(
                  //     onTap: () => context.read<ChangePasswordCubit>().toggleOldPasswordVisibility(),
                  //     child: Icon(
                  //       state.isOldPasswordVisible ? Icons.visibility : Icons.visibility_off,
                  //       size: 20,
                  //     ),
                  //   ),
                  // ),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: "New Password",
                    controller: _newPasswordController,
                    obsecureText: !state.isNewPasswordVisible,
                    validator: (value) => AppValidator.validatePassword(_newPasswordController.text),
                    suffixIcon: GestureDetector(
                      onTap: () => context.read<ChangePasswordCubit>().toggleNewPasswordVisibility(),
                      child: Icon(
                        state.isNewPasswordVisible ? Icons.visibility : Icons.visibility_off,
                        size: 20,
                      ),
                    ),
                  ),
                  20.sBHh,
                  CustomFormFieldWidget(
                    title: "Confirm Password",
                    controller: _confirmPasswordController,
                    obsecureText: !state.isConfirmPasswordVisible,
                    validator: (value) {
                      if (value != _newPasswordController.text) {
                        return "Passwords do not match";
                      }
                      return AppValidator.validatePassword(value);
                    },
                    suffixIcon: GestureDetector(
                      onTap: () => context.read<ChangePasswordCubit>().toggleConfirmPasswordVisibility(),
                      child: Icon(
                        state.isConfirmPasswordVisible ? Icons.visibility : Icons.visibility_off,
                        size: 20,
                      ),
                    ),
                  ),
                  6.sBHh,
                  Text(
                    "At least 8 characters, containing a letter and a number",
                    style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                  ),
                  80.sBHh,
                  AppButton(
                    isDisabled: state.isLoading == AppState.loading,
                    onPressed: () {
                      if (_formKey.currentState!.validate()) {
                        context.read<ChangePasswordCubit>().changePassword(
                              ChangePasswordParam(
                                newPassword: _newPasswordController.text,
                                confirmPassword: _confirmPasswordController.text,
                              ),
                            );
                      }
                    },
                    title: "Change Password",
                    trailingIcon: SAppAssets.iconArrowRight,
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
