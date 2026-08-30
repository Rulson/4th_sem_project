// import 'package:dio/dio.dart';
// import 'package:remit_management/core/common/params/params.dart';

// class SendMoneyPm extends Param {
//   final double sendingAmount;
//   final double exchangeRate;
//   final String paymentType;
//   final double paymentAmount;
//   final double serviceCharge;
//   final String beneficiaryId;
//   // final double referralDiscount;
//   final MultipartFile receipt;
//   final String resasonForSending;

//   SendMoneyPm(
//       {required this.sendingAmount,
//       required this.exchangeRate,
//       required this.paymentType,
//       required this.paymentAmount,
//       required this.serviceCharge,
//       required this.beneficiaryId,
//       // required this.referralDiscount,
//       required this.receipt ,
//       required this.resasonForSending});

//   // Convert to Map
//   @override
//   Map<String, dynamic> toJson() {
//     return {
//       'sending_amount': sendingAmount,
//       'exchange_rate': exchangeRate,
//       'payment_type': paymentType,
//       'payment_amount': paymentAmount,
//       'service_charge': serviceCharge,
//       'beneficiary_id': beneficiaryId,
//       // 'referral_discount': referralDiscount,
//       'receipt': receipt,
//       'reason_for_sending': resasonForSending
//     };
//   }
// }
