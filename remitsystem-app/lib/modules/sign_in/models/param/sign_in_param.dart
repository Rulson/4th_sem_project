import 'package:remit_management/core/common/params/params.dart';

class SignInParam extends Param {
  String email;
  String password;

  SignInParam({required this.email, required this.password});

  @override
  Map<String, dynamic> toJson() {
    return {
      'email': email,
      'password': password,
    };
  }
}
