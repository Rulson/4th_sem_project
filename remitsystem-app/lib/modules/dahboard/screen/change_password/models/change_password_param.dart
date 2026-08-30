import 'package:remit_management/core/common/params/params.dart';

class ChangePasswordParam extends Param {
  final String newPassword;
  final String confirmPassword;

  ChangePasswordParam({
    required this.newPassword,
    required this.confirmPassword,
  });

  @override
  Map<String, dynamic> toJson() {
    return {
      'password': newPassword,
      'password_confirmation': confirmPassword,
    };
  }
}
