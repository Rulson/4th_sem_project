class ReceiverData {
  final dynamic beneficiaryId;
  final String? addedBy;
  final String? firstName;
  final String? lastName;
  final String? middleName;
  final String? companyName;
  final String? taxVatNumber;
  final String? number;
  final String? street;
  final String? district;
  final String? postcode;
  final String? state;
  final dynamic countryListId;
  final String? fullName;
  final String? accountNo;
  final String? accountName;
  final String? bsb;
  final String? bankName;
  final String? suburb;

  ReceiverData({
    required this.beneficiaryId,
    required this.countryListId,
    this.addedBy,
    this.firstName,
    this.lastName,
    this.middleName,
    this.companyName,
    this.taxVatNumber,
    this.number,
    this.street,
    this.district,
    this.postcode,
    this.state,
    this.fullName,
    this.accountNo,
    this.accountName,
    this.bsb,
    this.bankName,
    this.suburb,
  });

  factory ReceiverData.fromJson(Map<String, dynamic> json) {
    return ReceiverData(
      beneficiaryId: json['beneficiary_id']?.toString(),
      countryListId: json['country_list_id']?.toString(),
      firstName: json['first_name'],
      lastName: json['last_name'],
      middleName: json['middle_name'],
      companyName: json['company_name'],
      taxVatNumber: json['tax_vat_number'],
      street: json['street'],
      suburb: json['suburb'],
      fullName: json['full_name'],
      number: json['number'],
      // suburb: json['suburb'],
      postcode: json['postcode'],
      state: json['state'],
      accountNo: json['accountNo'],
      accountName: json['account_name'],
      bsb: json['bsb'],
      bankName: json['bankName'],
      district: json['district'],
      addedBy: json['added_by']?.toString(),
    );
  }

  Map<String, dynamic> toJson() => {
        "beneficiary_id": beneficiaryId,
        "added_by": addedBy,
        "first_name": firstName,
        "last_name": lastName,
        "middle_name": middleName,
        "company_name": companyName,
        "tax_vat_number": taxVatNumber,
        "number": number,
        "street": street,
        "district": district,
        "postcode": postcode,
        "state": state,
        "country_list_id": countryListId,
        "full_name": fullName,
        "accountNo": accountNo,
        "account_name": accountName,
        "bsb": bsb,
        "bankName": bankName,
        "suburb": suburb,
      };
}
