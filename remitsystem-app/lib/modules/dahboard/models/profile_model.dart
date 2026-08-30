import '../../../core/common/params/params.dart';

class ProfileModel {
  final dynamic userId;
  final String? apiToken;
  final String? firstName;
  final String? lastName;
  final DateTime? dob;
  final int? id;
  final String? street;
  final String? suburb;
  final String? postcode;
  final dynamic stateId;
  final String? state;
  final dynamic locationId;
  final DateTime? createdAt;
  final dynamic countryListId;
  final String? email;
  final String? number;
  final String? role;
  final String? levelId;
  final String? levelid;
  final String? status;
  final DateTime? expiryDate;
  final String? issuedBy;
  final String? idNumber;
  final String? identificationTypesId;
  final String? image;
  final String? fullName;
  final int? totalTransactionCount;
  final int? successfulTransactionCount;
  final String? referralCode;

  ProfileModel({
    this.userId,
    this.apiToken,
    this.firstName,
    this.lastName,
    this.dob,
    this.id,
    this.street,
    this.suburb,
    this.postcode,
    this.stateId,
    this.state,
    this.locationId,
    this.createdAt,
    this.countryListId,
    this.email,
    this.number,
    this.role,
    this.levelId,
    this.levelid,
    this.status,
    this.expiryDate,
    this.issuedBy,
    this.idNumber,
    this.identificationTypesId,
    this.image,
    this.fullName,
    this.totalTransactionCount,
    this.successfulTransactionCount,
    this.referralCode,
  });

  factory ProfileModel.fromJson(Map<String, dynamic> json) {
    return ProfileModel(
      userId: json["user_id"],
      apiToken: json["api_token"],
      firstName: json["first_name"],
      lastName: json["last_name"],
      dob: json["dob"] == null ? null : DateTime.parse(json["dob"]),
      id: json["id"],
      street: json["street"],
      suburb: json["suburb"],
      postcode: json["postcode"],
      stateId: json["state_id"],
      state: json["state"],
      locationId: json["location_id"],
      createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
      countryListId: json["country_list_id"],
      email: json["email"],
      number: json["number"],
      role: json["role"],
      levelId: json["level_id"],
      levelid: json["levelid"]?.toString(),
      status: json["status"],
      expiryDate: json["expiry_date"] == null ? null : DateTime.parse(json["expiry_date"]),
      issuedBy: json["issued_by"],
      idNumber: json["id_number"],
      identificationTypesId: json["identification_types_id"],
      image: json["image"],
      fullName: json["full_name"],
      totalTransactionCount: json["total_transaction_count"],
      successfulTransactionCount: json["successful_transaction_count"],
      referralCode: json["referral_code"],
    );
  }
}

class ProfileParam extends Param {
  final String? email;
  final String? firstName;
  final String? lastName;
  final String? phone;
  final String? dob;
  final String? street;
  final String? suburb;
  final String? postcode;
  final String? state;
  final int? countryListId;

  ProfileParam({
    this.email,
    this.firstName,
    this.lastName,
    this.phone,
    this.dob,
    this.street,
    this.suburb,
    this.postcode,
    this.state,
    this.countryListId,
  });

  @override
  Map<String, dynamic> toJson() {
    return {
      'email': email,
      'first_name': firstName,
      'last_name': lastName,
      'phone_number': phone,
      'dob': dob,
      'street': street,
      'suburb': suburb,
      'postcode': postcode,
      'state': state,
      'country_list_id': countryListId,
    };
  }
}
