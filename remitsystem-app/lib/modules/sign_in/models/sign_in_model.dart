class SignInModel {
  SignInModel(
      {required this.userId,
      required this.apiToken,
      required this.firstName,
      required this.lastName,
      required this.dob,
      required this.id,
      required this.street,
      required this.suburb,
      required this.postcode,
      required this.stateId,
      required this.state,
      required this.locationId,
      required this.createdAt,
      required this.countryListId,
      required this.email,
      required this.number,
      required this.role,
      required this.levelId,
      required this.levelid,
      required this.status,
      required this.expiryDate,
      required this.issuedBy,
      required this.idNumber,
      required this.identificationTypesId,
      required this.senderId,
      required this.fullName,
      required this.image,
      required this.image1,
      required this.totalTransactionCount,
      required this.successfulTransactionCount,
      required this.pinSet});

  final String? userId;
  final String? apiToken;
  final String? firstName;
  final String? lastName;
  final DateTime? dob;
  final int? id;
  final String? street;
  final String? suburb;
  final String? postcode;
  final int? stateId;
  final String? state;
  final int? locationId;
  final DateTime? createdAt;
  final int? countryListId;
  final String? email;
  final String? number;
  final String? role;
  final String? levelId;
  final int? levelid;
  final String? status;
  final DateTime? expiryDate;
  final String? issuedBy;
  final String? idNumber;
  final int? identificationTypesId;
  final int? senderId;
  final String? fullName;
  final String? image;
  final String? image1;
  final int? totalTransactionCount;
  final int? successfulTransactionCount;
  final bool? pinSet;
  factory SignInModel.fromJson(Map<String, dynamic> json) {
    return SignInModel(
      userId: json["user_id"]?.toString(),
      apiToken: json["api_token"],
      firstName: json["first_name"],
      lastName: json["last_name"],
      dob: DateTime.tryParse(json["dob"] ?? ""),
      id: _toInt(json["id"]),
      street: json["street"],
      suburb: json["suburb"],
      postcode: json["postcode"],
      stateId: _toInt(json["state_id"]),
      state: json["state"],
      locationId: _toInt(json["location_id"]),
      createdAt: DateTime.tryParse(json["created_at"] ?? ""),
      countryListId: _toInt(json["country_list_id"]),
      email: json["email"],
      number: json["number"],
      role: json["role"],
      levelId: json["level_id"],
      levelid: _toInt(json["levelid"]),
      status: json["status"],
      expiryDate: DateTime.tryParse(json["expiry_date"] ?? ""),
      issuedBy: json["issued_by"],
      idNumber: json["id_number"],
      identificationTypesId: _toInt(json["identification_types_id"]),
      senderId: _toInt(json["sender_id"]),
      fullName: json["full_name"],
      image: json["image"],
      image1: json["image1"],
      totalTransactionCount: _toInt(json["total_transaction_count"]),
      successfulTransactionCount: _toInt(json["successful_transaction_count"]),
      pinSet: json["pin_set"] as bool?,
    );
  }

  static int? _toInt(dynamic value) {
    if (value == null) return null;
    if (value is int) return value;
    if (value is String) return int.tryParse(value);
    return null;
  }
}
