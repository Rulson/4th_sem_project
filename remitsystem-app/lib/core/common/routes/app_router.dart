import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/modules/dahboard/models/transaction_list_model.dart';
import 'package:remit_management/modules/dahboard/screen/about_us_screen.dart';
import 'package:remit_management/modules/dahboard/screen/change_password/screens/change_password_screen.dart';
import 'package:remit_management/modules/dahboard/screen/dashboard_screen.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/bloc/forgot_password_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/screens/forget_password_screen.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/screens/forget_password_send_otp.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/screens/forget_password_verify_otp.dart';
import 'package:remit_management/modules/dahboard/screen/invited_friends_screen.dart';
import 'package:remit_management/modules/dahboard/screen/notification/notification_screen.dart';
import 'package:remit_management/modules/dahboard/screen/personal_detail_screen.dart';
import 'package:remit_management/modules/dahboard/screen/recipient_detail_screen.dart';
import 'package:remit_management/modules/dahboard/screen/transaction_detail_screen.dart';
import 'package:remit_management/modules/dahboard/screen/transactions_screen.dart';
import 'package:remit_management/modules/onboard/onboarding_screen.dart';
import 'package:remit_management/modules/pin/pin_page.dart';
import 'package:remit_management/modules/onboard/splash_screen.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/receiver/screen/add_receiver_screen.dart';
import 'package:remit_management/modules/send_money/screens/choose_receiver_screen.dart';
import 'package:remit_management/modules/send_money/screens/confirm_transfer_screen.dart';
import 'package:remit_management/modules/send_money/screens/send_money_screen.dart';
import 'package:remit_management/modules/send_money/screens/send_receipt_screen.dart';
import 'package:remit_management/modules/send_money/screens/successfully_transferred_screen.dart';
import 'package:remit_management/modules/sign_in/screens/sign_in_screen.dart';
import 'package:remit_management/modules/sign_up/screens/account_setup_screen.dart';
import 'package:remit_management/modules/sign_up/screens/email_verified_screen.dart';
import 'package:remit_management/modules/sign_up/screens/referral_code_screen.dart';
import 'package:remit_management/modules/sign_up/screens/sign_up_screen.dart';
import 'package:remit_management/modules/sign_up/screens/successful_acount_screen.dart';
import 'package:remit_management/modules/sign_up/screens/verify_email_otp_screen.dart';

import '../../../modules/dahboard/screen/edit_profile_screen.dart';
import '../../../modules/sign_up/screens/email_activated_screen.dart';

Page<T> _smoothPage<T>(Widget child, GoRouterState state) {
  return CustomTransitionPage<T>(
    key: state.pageKey,
    child: child,
    transitionDuration: const Duration(milliseconds: 300),
    reverseTransitionDuration: const Duration(milliseconds: 300),
    transitionsBuilder: (context, animation, secondaryAnimation, child) {
      return SlideTransition(
        position: Tween<Offset>(
          begin: const Offset(1.0, 0.0),
          end: Offset.zero,
        ).animate(CurvedAnimation(
          parent: animation,
          curve: Curves.easeOutCubic,
        )),
        child: FadeTransition(
          opacity: Tween<double>(
            begin: 0.85,
            end: 1.0,
          ).animate(CurvedAnimation(
            parent: animation,
            curve: Curves.easeOutCubic,
          )),
          child: child,
        ),
      );
    },
  );
}

final GoRouter router = GoRouter(
  navigatorKey: _navigatorKey,
  initialLocation: AppRoutes.initial,
  routes: [
    GoRoute(
      path: AppRoutes.initial,
      pageBuilder: (context, state) => _smoothPage(const SplashScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.onboarding,
      pageBuilder: (context, state) => _smoothPage(const OnboardingScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.signup,
      pageBuilder: (context, state) => _smoothPage(const SignupScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.signin,
      pageBuilder: (context, state) => _smoothPage(const SigninScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.verifyOtp,
      pageBuilder: (context, state) {
        final pageType = state.uri.queryParameters['pageType'];
        return _smoothPage(VerifyEmailOtpScreen(pageType: pageType), state);
      },
    ),
    GoRoute(
        path: "${AppRoutes.otpPage}/:pageType",
        pageBuilder: (context, state) {
          final String pageType = state.pathParameters["pageType"]!;
          return _smoothPage(OtpPage(pageType: pageType), state);
        }),
    GoRoute(
      path: AppRoutes.emailVerified,
      pageBuilder: (context, state) => _smoothPage(const EmailVerifiedScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.referralCode,
      pageBuilder: (context, state) => _smoothPage(const ReferralCodeScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.dashboard,
      pageBuilder: (context, state) => _smoothPage(const DashboardPage(), state),
    ),
    GoRoute(
      path: AppRoutes.sendMoney,
      pageBuilder: (context, state) {
        final receiver = state.extra as ReceiverData?;
        return _smoothPage(SendMoneyScreen(receiver: receiver), state);
      },
    ),
    GoRoute(
      path: AppRoutes.transactions,
      pageBuilder: (context, state) => _smoothPage(const TransactionsScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.accountSetup,
      pageBuilder: (context, state) => _smoothPage(const AccountSetupScreen(), state),
    ),
    GoRoute(path: AppRoutes.accountSetupSuccess, pageBuilder: (context, state) => _smoothPage(const SuccessfulAcountCreationScreen(), state)),
    GoRoute(
      path: AppRoutes.notification,
      pageBuilder: (context, state) => _smoothPage(const NotificationScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.transactionDetail,
      pageBuilder: (context, state) {
        final transactionListModel = state.extra as TransactionData;
        return _smoothPage(TransactionDetailScreen(transactionListModel: transactionListModel), state);
      },
    ),
    GoRoute(
      path: AppRoutes.invitedFriends,
      pageBuilder: (context, state) => _smoothPage(const InvitedFriendsScreen(), state),
    ),
    GoRoute(path: AppRoutes.personalDetails, pageBuilder: (context, state) => _smoothPage(const PersonalDetailScreen(), state)),
    GoRoute(path: AppRoutes.sendReceipt, pageBuilder: (context, state) => _smoothPage(const SendReceiptScreen(), state)),
    GoRoute(
      path: AppRoutes.addReceiver,
      pageBuilder: (context, state) => _smoothPage(const AddReceiverScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.chooseReceiver,
      pageBuilder: (context, state) {
        final receiver = state.extra as ReceiverData?;
        return _smoothPage(ChooseReceiverScreen(receiver: receiver), state);
      },
    ),
    GoRoute(path: AppRoutes.successfullyTransferred, pageBuilder: (context, state) => _smoothPage(const SuccessfullyTransferredScreen(), state)),
    GoRoute(
      path: AppRoutes.changePassword,
      pageBuilder: (context, state) => _smoothPage(const ChangePasswordScreen(), state),
    ),
    GoRoute(path: AppRoutes.aboutUs, pageBuilder: (context, state) => _smoothPage(const AboutUsScreen(), state)),
    GoRoute(
      path: AppRoutes.forgotPassword,
      pageBuilder: (context, state) => _smoothPage(
        BlocProvider(
          create: (context) => ForgotPasswordCubit(),
          child: const ForgotPasswordSendOtp(),
        ),
        state,
      ),
    ),
    GoRoute(
      path: AppRoutes.forgotPasswordVerifyOtp,
      pageBuilder: (context, state) {
        final email = state.extra as String? ?? '';
        return _smoothPage(
          BlocProvider(
            create: (context) => ForgotPasswordCubit(),
            child: ForgotPasswordVerifyOtp(email: email),
          ),
          state,
        );
      },
    ),
    GoRoute(
      name: AppRoutes.forgotPasswordReset,
      path: AppRoutes.forgotPasswordReset,
      pageBuilder: (context, state) {
        final extra = state.extra as Map<String, String>? ?? {};
        return _smoothPage(
          BlocProvider(
            create: (context) => ForgotPasswordCubit(),
            child: ForgotPassword(
              email: extra['email'] ?? '',
              otp: extra['otp'] ?? '',
            ),
          ),
          state,
        );
      },
    ),
    GoRoute(
      path: AppRoutes.recipientDetail,
      pageBuilder: (context, state) => _smoothPage(
        RecipientDetailScreen(receiver: state.extra as ReceiverData),
        state,
      ),
    ),
    GoRoute(
      path: AppRoutes.confirmTransfer,
      pageBuilder: (context, state) => _smoothPage(const ConfrimTransferScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.editProfile,
      pageBuilder: (context, state) => _smoothPage(const EditProfileScreen(), state),
    ),
    GoRoute(
      path: AppRoutes.emailActivation,
      pageBuilder: (context, state) => _smoothPage(const EmailActivatedScreen(), state),
    ),
  ],
);

final GlobalKey<NavigatorState> _navigatorKey = GlobalKey<NavigatorState>();
final GlobalKey<ScaffoldMessengerState> rootScaffoldMessengerKey = GlobalKey<ScaffoldMessengerState>();
BuildContext? get currentGlobalContext => _navigatorKey.currentContext;
