import 'package:remit_management/core/common/params/params.dart';

class CheckEmailAvailabilityAndSendOtpParam extends Param {
  final String email;

  CheckEmailAvailabilityAndSendOtpParam({required this.email});

  @override
  Map<String, dynamic> toJson() {
    return {"email": email};
  }
}

class VerifyEmailNewParam extends Param {
  final String email;
  final String otp;

  VerifyEmailNewParam({required this.email, required this.otp});

  @override
  Map<String, dynamic> toJson() {
    return {"email": email, "otp": otp};
  }
}

class EmailActivationParam extends Param {
  final String email;
  final String verificationCode;

  EmailActivationParam({required this.email, required this.verificationCode});

  @override
  Map<String, dynamic> toJson() {
    return {"email": email, "verification_code": verificationCode};
  }
}


class RegisterParam extends Param {
  final String firstName;
  final String lastName;
  final String email;
  final String password;
  final String passwordConfirmation;
  final String phoneNumber;
  final String dob;
  final String issuedBy;
  final String idNumber;
  final String idType;
  final String expiryDate;
  final String image;
  final String image1;
  final String street;
  final String suburb;
  final String postcode;
  final String state;
  final String countryListId;
  final String addressProof;
  // final String phone;
  final String invividualRemarks;
  final String otp;

  RegisterParam(
      {required this.firstName,
      required this.lastName,
      required this.password,
      required this.passwordConfirmation,
      required this.phoneNumber,
      required this.dob,
      required this.issuedBy,
      required this.idNumber,
      required this.idType,
      required this.expiryDate,
      required this.image,
      required this.image1,
      required this.street,
      required this.suburb,
      required this.postcode,
      required this.state,
      required this.countryListId,
      required this.addressProof,
      // required this.phone,
      required this.invividualRemarks,
      required this.email,
      required this.otp});

  @override
  Map<String, dynamic> toJson() {
    return {
      "first_name": firstName,
      "last_name": lastName,
      "password": password,
      "password_confirmation": passwordConfirmation,
      "phone_number": phoneNumber,
      "dob": dob,
      "issued_by": issuedBy,
      "id_number": idNumber,
      "id_type": idType == "Passport"
          ? 1
          : idType == "Driver's License"
              ? 2
              : 3,
      "expiry_date": expiryDate,
      "image": image,
      "image1": image1,
      "street": street,
      "suburb": suburb,
      "postcode": postcode,
      "state": state,
      "country_list_id": countryListId,
      "address_proof": addressProof,
      // "phone": phone,
      "invividual_remarks": invividualRemarks,
      "email": email,
      "otp": otp
    };
  }
}
