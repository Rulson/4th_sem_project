import 'package:flutter/foundation.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_cubit.dart';
import 'package:remit_management/modules/sign_up/bloc/create_ac_state.dart';
import 'package:remit_management/modules/sign_up/models/param/create_ac_param.dart';

class SignupScreen extends StatelessWidget {
  const SignupScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SignupView();
  }
}

class SignupView extends StatefulWidget {
  const SignupView({super.key});

  @override
  State<SignupView> createState() => _SignupViewState();
}

class _SignupViewState extends State<SignupView> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _passwordConfirmationController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    if (kDebugMode) {
      _emailController.text = "kriteshjoshi@gmail.com";
      _passwordController.text = "Password@123";
      _passwordConfirmationController.text = "Password@123";
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        hideBackButton: true,
          // title: "Sign Up",
          ),
      body: BlocConsumer<CreateAccountCubit, CreateAccountState>(
        listener: (ctx, state) {
          if (state.checkStatus == true && state.isCheckLoading == AppState.success) {
            AppSnackbar.showSnackBar(context: context, message: "Otp sent to the email", type: SnackBarType.success);
            context.push(AppRoutes.verifyOtp);
            context.read<CreateAccountCubit>().resetCheckLoading();
          } else if (state.checkStatus == false && state.isCheckLoading == AppState.error) {
            AppSnackbar.showSnackBar(context: context, message: "Email already exists", type: SnackBarType.error);
            context.read<CreateAccountCubit>().resetCheckLoading();
          }
        },
        builder: (context, state) {
          return SingleChildScrollView(
            child: Column(
              children: [
                ConstrainedBox(
                  constraints: BoxConstraints(maxHeight: 200.h, minWidth: double.infinity),
                  child: Image.asset(
                    SAppAssets.imageSingupIllustration,
                    fit: BoxFit.cover,
                    width: double.infinity,
                  ),
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 16.w),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Create your account and start sending money",
                          style: AppText.headlineSmall700.copyWith(
                            color: AppColor.gray900,
                            fontWeight: FontWeight.w800,
                          ),
                          textAlign: TextAlign.center,
                        ),
                        28.sBHh,
                        CustomFormFieldWidget(
                          title: "Email",
                          controller: _emailController,
                          hint: "Enter your email address",
                          keyboardType: TextInputType.emailAddress,
                          validator: (value) => AppValidator.validateEmail(_emailController.text),
                        ),
                        16.sBHh,
                        CustomFormFieldWidget(
                          title: "Password",
                          hint: "Create a new password",
                          controller: _passwordController,
                          obsecureText: !state.isPasswordVisible,
                          validator: (value) => AppValidator.validatePassword(_passwordController.text),
                          suffixIcon: GestureDetector(
                            onTap: () => context.read<CreateAccountCubit>().changePasswordVisibility(),
                            child: Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 14),
                              child: Icon(
                                state.isPasswordVisible ? Icons.visibility : Icons.visibility_off,
                                size: 20,
                                color: AppColor.textSecondary,
                              ),
                            ),
                          ),
                        ),
                        16.sBHh,
                        CustomFormFieldWidget(
                          title: "Confirm Password",
                          hint: "Re-enter your new password",
                          controller: _passwordConfirmationController,
                          obsecureText: !state.isPasswordConfirmationVisible,
                          validator: (value) => AppValidator.validatePasswordConfirmation(_passwordConfirmationController.text, _passwordController.text),
                          suffixIcon: GestureDetector(
                            onTap: () => context.read<CreateAccountCubit>().changePasswordConfirmationVisibility(),
                            child: Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 14),
                              child: Icon(
                                state.isPasswordConfirmationVisible ? Icons.visibility : Icons.visibility_off,
                                size: 20,
                                color: AppColor.textSecondary,
                              ),
                            ),
                          ),
                        ),
                        // 16.sBHh,
                        // GestureDetector(
                        //   onTap: () {},
                        //   child: Text(
                        //     "Have referral code?",
                        //     style: AppText.bodyMedium500.copyWith(color: AppColor.primary),
                        //   ),
                        // ),
                        24.sBHh,
                        Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            AppButton(
                              height: 54,
                              isLoading: state.isCheckLoading == AppState.loading,
                              onPressed: () {
                                if (_formKey.currentState!.validate()) {
                                  context.read<CreateAccountCubit>().storePassword(_passwordController.text);
                                  context.read<CreateAccountCubit>().storePasswordConfrimation(_passwordConfirmationController.text);
                                  context.read<CreateAccountCubit>().storeEmail(_emailController.text);
                                  context
                                      .read<CreateAccountCubit>()
                                      .checkEmailAvailabilityAndSendOtp(CheckEmailAvailabilityAndSendOtpParam(email: _emailController.text));
                                }
                              },
                              title: 'Sign Up',
                            ),
                            16.sBHh,
                            RichText(
                              textAlign: TextAlign.center,
                              text: TextSpan(
                                text: "Already have an account? ",
                                style: AppText.bodyMedium400.copyWith(color: AppColor.gray800),
                                children: [
                                  TextSpan(
                                    text: "Log In",
                                    style: AppText.bodyMedium600.copyWith(color: AppColor.primary),
                                    recognizer: TapGestureRecognizer()..onTap = () => context.go(AppRoutes.signin),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                        12.sBHh,
                      ],
                    ),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    _passwordConfirmationController.dispose();
    super.dispose();
  }
}
