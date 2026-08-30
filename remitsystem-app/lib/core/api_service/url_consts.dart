import 'package:remit_management/flavors.dart';

class UrlConsts {
  // static String baseUrl = 'https://remit.remitsystem.com.au/api1';
  static String baseUrl = AppUtils.baseUrl;
  static String gbgUrl = "$baseUrl/register/webview";

  static const String login = '/login';

  static const String setPin = '/set-pin';

  static const String validatePin = '/validate-pin';

  static const String profile = '/profile';

  static const String home = '/home';

  static const String beneficiaries = '/beneficiaries';

  static const String updateReceiver = '/beneficiary/update';

  static const String districts = '/districts';

  static const String province = '/np_state';

  static const String addReceiver = '/beneficiary/store';

  static const String storeTransaction = '/transaction/store';

  static const String transactions = '/transactions';

  static const String checkEmailAvailabilityAndSendOtp = "/check-email-availability-and-send-otp";

  static const String verifyEmailNew = "/verify-email-new";

  static const String register = "/register";

  static const String changePassword = "/password/change";

  static const String forgotPasswordOtp = '/forgot-password-otp';
  static const String forgotPasswordChange = '/forgot-password-change';

  static const String editProfile = "/profile/edit";

  static const String countries = "/countries";

  static const String ausState = "/aus_state";

  static const String getSuburb = "/getSuburb";


  static const String banks = "/banks";

// this needs to implemented later
  static const String verifyEmail ="/verify-email"; //payload: email, verification_code

  static const String resendOtp = "/resend-otp";


  static const String resendActivationCode = "/resend-activation-code";

  static const String getNotificationList = "/notification/list";

  static const String markNotificationAsRead = "/notification/mark-as-read";

















}
