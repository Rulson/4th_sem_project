import 'package:remit_management/core/common/params/params.dart';

class ReceiverPm extends Param {
  String firstName;
  String lastName;
  String number;
  String suburb;
  String district;
  String state;
  String postcode;
  String countryListId;
  String bsb;
  String street;
  String accountName;
  String accountNo;
  String bankName;

  ReceiverPm({
    required this.firstName,
    required this.lastName,
    required this.number,
    required this.suburb,
    required this.district,
    required this.state,
    required this.postcode,
    required this.countryListId,
    required this.bsb,
    required this.street,
    required this.accountName,
    required this.accountNo,
    required this.bankName,
  });

  @override
  Map<String, dynamic> toJson() {
    return {
      'first_name': firstName,
      'last_name': lastName,
      'phone_number': number,
      'suburb': suburb,
      'district': district,
      'state': state,
      'postcode': postcode,
      'country_list_id': countryListId,
      'bsb': bsb,
      'account_name': accountName,
      'account_no': accountNo,
      'bank_name': bankName,
      'number': number
    };
  }
}
