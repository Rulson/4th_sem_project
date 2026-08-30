import 'package:flutter/foundation.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_string_const.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/validator.dart';
import 'package:remit_management/flavors.dart';
import 'package:remit_management/modules/sign_in/bloc/sign_in_cubit.dart';
import 'package:remit_management/modules/sign_in/bloc/sign_in_state.dart';
import 'package:remit_management/modules/sign_in/models/param/sign_in_param.dart';

import '../../sign_up/bloc/create_ac_cubit.dart';

class SigninScreen extends StatelessWidget {
  const SigninScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => SignInCubit(),
      child: SigninView(),
    );
  }
}

class SigninView extends StatefulWidget {
  const SigninView({super.key});

  @override
  State<SigninView> createState() => _SigninViewState();
}

class _SigninViewState extends State<SigninView> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    // if (kDebugMode) {
    //   _emailController.text = AppUtils.isDevelopment? "sabinshrestha814+tn05@gmail.com":"mantraideasqa+liveuni@gmail.com" ;
    //   _passwordController.text = "Afer\$nq23Q#1";
    // }
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // backgroundColor: Colors.transparent,
      appBar: CustomAppBar(
        hideBackButton: true,
        // title: "Back",
      ),
      body: Form(
        key: _formKey,
        child: BlocConsumer<SignInCubit, SignInState>(
          listener: (context, state) {
            if (state.isLoading == AppState.success) {
              if (state.data?.data?.pinSet == false) {
                context.push("${AppRoutes.otpPage}/${AppStringConst.setPin}");
              } else {
                context.push("${AppRoutes.otpPage}/${AppStringConst.validatePin}");
              }
              AppSnackbar.showSnackBar(context: context, message: "Login Successful", type: SnackBarType.success);
              context.read<SignInCubit>().resetState();
            } else if (state.isLoading == AppState.error) {
              if (context.mounted) {
                context.read<CreateAccountCubit>().storeEmail(_emailController.text);
                if ((state.message?.contains("Your account has not been activated yet") ?? false) && state.message?.contains("status 401") == true) {
                  context.push(Uri(path: AppRoutes.verifyOtp, queryParameters: {"pageType": AppRoutes.emailActivation}).toString());
                  AppSnackbar.showSnackBar(context: context, message: state.message, type: SnackBarType.info);
                  return;
                }
                AppSnackbar.showSnackBar(context: currentGlobalContext?? context, message: state.message, type: SnackBarType.error);
              }
            }
          },
          builder: (context, state) {
            return SafeArea(
              child: SingleChildScrollView(
                child: Column(
                  children: [
                    ConstrainedBox(
                      constraints: BoxConstraints(maxHeight: 200.h, minWidth: double.infinity),
                      child: Center(
                        child: Image.asset(
                          SAppAssets.imageSinginIllustration,
                          width: double.infinity,
                          fit: BoxFit.cover,
                          // fit: BoxFit.contain,
                        ),
                      ),
                    ),
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.w),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Access your account and start sending money",
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
                            validator: (v) => AppValidator.validateEmail(_emailController.text ?? ""),
                          ),
                          16.sBHh,
                          CustomFormFieldWidget(
                            title: "Password",
                            obsecureText: !state.isPasswordVisible,
                            controller: _passwordController,
                            hint: "Enter your password",
                            suffixIcon: GestureDetector(
                              onTap: () => context.read<SignInCubit>().changePasswordVisibility(),
                              child: Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 14),
                                child: Icon(
                                  state.isPasswordVisible ? Icons.visibility : Icons.visibility_off,
                                  size: 20,
                                  color: AppColor.textSecondary,
                                ),
                              ),
                            ),
                            validator: (v) => AppValidator.validateRequired(_passwordController.text ?? "", "Password"),
                          ),
                          4.sBHh,
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  Transform.scale(
                                    scale: 0.8,
                                    child: Checkbox(
                                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(4.r)),
                                      side: BorderSide(color: AppColor.gray400, width: 1.5),
                                      activeColor: AppColor.primary,
                                      value: state.rememberMe,
                                      onChanged: (_) => context.read<SignInCubit>().setRememberMe(),
                                    ),
                                  ),
                                  Text(
                                    "Remember me",
                                    style: AppText.bodyMedium500.copyWith(color: AppColor.textSecondary),
                                  ),
                                ],
                              ),
                              TextButton(
                                onPressed: () => context.push(AppRoutes.forgotPassword),
                                child: Text(
                                  "Forgot Password?",
                                  style: AppText.bodySmall500.copyWith(color: AppColor.primary),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    32.sBHh,
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.w),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          BlocBuilder<SignInCubit, SignInState>(
                            builder: (context, state) {
                              return AppButton(
                                height: 54,
                                isLoading: state.isLoading == AppState.loading,
                                onPressed: () {
                                  if (_formKey.currentState!.validate()) {
                                    context.read<SignInCubit>().login(
                                          SignInParam(
                                            email: _emailController.text,
                                            password: _passwordController.text,
                                          ),
                                        );
                                  }
                                },
                                title: 'Log In',
                              );
                            },
                          ),
                          16.sBHh,
                          RichText(
                            textAlign: TextAlign.center,
                            text: TextSpan(
                              text: "Don't have an account? ",
                              style: AppText.bodyMedium400.copyWith(color: AppColor.gray800),
                              children: [
                                TextSpan(
                                  text: "Sign Up",
                                  style: AppText.bodyMedium600.copyWith(color: AppColor.primary),
                                  recognizer: TapGestureRecognizer()..onTap = () => context.go(AppRoutes.signup),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    16.sBHh
                  ],
                ),
              ),
            );
          },
        ),
      ),
    );
  }
}
