import 'package:remit_management/core/common/params/params.dart';

class PinPm extends Param {
  final int pin;
  PinPm({required this.pin});
  @override
  Map<String, dynamic> toJson() {
    return {
      'pin': pin,
    };
  }
}
