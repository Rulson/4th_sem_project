import 'package:remit_management/core/common/params/params.dart';

class ResendOtpParam extends Param {
  final String email;

  ResendOtpParam({required this.email});

  @override
  Map<String, dynamic> toJson() {
    return {"email": email};
  }
}
