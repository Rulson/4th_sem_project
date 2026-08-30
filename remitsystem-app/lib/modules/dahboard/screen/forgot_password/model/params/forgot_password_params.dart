import 'package:remit_management/core/common/params/params.dart';

class ForgotPasswordParams extends Param {
  final String email;
  final String password;
  final String passwordConfirmation;
  final String otp;

  ForgotPasswordParams({
    required this.email,
    required this.password,
    required this.passwordConfirmation,
    required this.otp,
  });

  @override
  Map<String, dynamic> toJson() {
    return {
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'otp': otp,
    };
  }
}
