class TransactionData {
  final String? transactionId;
  final String? bankTransactionId;
  final String? transactionDate;
  final String? senderName;
  final String? beneficiaryName;
  final String? totalAmount;
  final String? serviceCharge;
  final String? sendingAmount;
  final String? paymentAmount;
  final String? exchangeRate;
  final String? addedBy;
  final String? statusId;
  final String? number;
  final String? accountName;
  final String? accountNo;
  final String? bsb;
  final String? bankName;
  final String? senderId;
  final String? beneficiaryId;
  final String? status;

  const TransactionData({
    this.transactionId,
    this.bankTransactionId,
    this.transactionDate,
    this.senderName,
    this.beneficiaryName,
    this.totalAmount,
    this.serviceCharge,
    this.sendingAmount,
    this.paymentAmount,
    this.exchangeRate,
    this.addedBy,
    this.statusId,
    this.number,
    this.accountName,
    this.accountNo,
    this.bsb,
    this.bankName,
    this.senderId,
    this.beneficiaryId,
    this.status,
  });

  factory TransactionData.fromJson(Map<String, dynamic> json) {
    String? toStr(dynamic v) => v?.toString();

    return TransactionData(
      transactionId: toStr(json["transaction_id"]),
      bankTransactionId: toStr(json["bank_transaction_id"]),
      transactionDate: toStr(json["transactionDate"] ?? json["transaction_date"]),
      senderName: toStr(json["sender_name"]),
      beneficiaryName: toStr(json["beneficiary_name"]),
      totalAmount: toStr(json["totalAmount"] ?? json["total_amount"]),
      serviceCharge: toStr(json["serviceCharge"] ?? json["service_charge"]),
      sendingAmount: toStr(json["sendingAmount"] ?? json["sending_amount"]),
      paymentAmount: toStr(json["paymentAmount"] ?? json["payment_amount"]),
      exchangeRate: toStr(json["exchangeRate"] ?? json["exchange_rate"]),
      addedBy: toStr(json["addedBy"] ?? json["added_by"]),
      statusId: toStr(json["status_id"]),
      number: toStr(json["number"]),
      accountName: toStr(json["account_name"]),
      accountNo: toStr(json["account_no"]),
      bsb: toStr(json["bsb"]),
      bankName: toStr(json["bank_name"]),
      senderId: toStr(json["sender_id"]),
      beneficiaryId: toStr(json["beneficiary_id"]),
      status: toStr(json["status"]),
    );
  }

  Map<String, dynamic> toJson() => {
        "transaction_id": transactionId,
        "bank_transaction_id": bankTransactionId,
        "transaction_date": transactionDate,
        "sender_name": senderName,
        "beneficiary_name": beneficiaryName,
        "total_amount": totalAmount,
        "service_charge": serviceCharge,
        "sending_amount": sendingAmount,
        "payment_amount": paymentAmount,
        "exchange_rate": exchangeRate,
        "added_by": addedBy,
        "status_id": statusId,
        "number": number,
        "account_name": accountName,
        "account_no": accountNo,
        "bsb": bsb,
        "bank_name": bankName,
        "sender_id": senderId,
        "beneficiary_id": beneficiaryId,
        "status": status,
      };
}
