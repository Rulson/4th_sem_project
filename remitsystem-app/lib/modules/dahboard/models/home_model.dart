class HomeModel {
  HomeModel({
    required this.todayRate,
    required this.sliders,
    required this.serviceCharge,
    required this.noOfTransaciton,
    required this.notification,
    required this.thisMonthAu,
    required this.thisMonthNp,
    required this.allTimeNp,
    required this.allTimeAu,
    required this.ourBank,
    required this.aboutUs,
    required this.exchangeRate,
    required this.idType,
    required this.notices,
    required this.homeModelReferralPoints,
    required this.referralPoints,
    required this.paymentTypes,
  });

  final TodayRate? todayRate;
  final List<dynamic> sliders;
  final String? serviceCharge;
  final int? noOfTransaciton;
  final List<dynamic> notification;
  final int? thisMonthAu;
  final int? thisMonthNp;
  final int? allTimeNp;
  final int? allTimeAu;
  final OurBank? ourBank;
  final AboutUs? aboutUs;
  final ExchangeRate? exchangeRate;
  final List<IdType> idType;
  final dynamic notices;
  final ReferralPointsClass? homeModelReferralPoints;
  final ReferralPoints? referralPoints;
  final PaymentTypes? paymentTypes;

  factory HomeModel.fromJson(Map<String, dynamic> json) {
    return HomeModel(
      todayRate: json["today_rate"] == null ? null : TodayRate.fromJson(json["today_rate"]),
      sliders: json["sliders"] == null ? [] : List<dynamic>.from(json["sliders"]!.map((x) => x)),
      serviceCharge: json["service_charge"],
      noOfTransaciton: json["no_of_transaciton"],
      notification: json["notification"] == null ? [] : List<dynamic>.from(json["notification"]!.map((x) => x)),
      thisMonthAu: json["this_month_au"],
      thisMonthNp: json["this_month_np"],
      allTimeNp: json["all_time_np"],
      allTimeAu: json["all_time_au"],
      ourBank: json["our_bank"] == null ? null : OurBank.fromJson(json["our_bank"]),
      aboutUs: json["about_us"] == null ? null : AboutUs.fromJson(json["about_us"]),
      exchangeRate: json["exchange_rate"] == null ? null : ExchangeRate.fromJson(json["exchange_rate"]),
      idType: json["id_type"] == null ? [] : List<IdType>.from(json["id_type"]!.map((x) => IdType.fromJson(x))),
      notices: json["notices"],
      homeModelReferralPoints: json["referral_points"] == null ? null : ReferralPointsClass.fromJson(json["referral_points"]),
      referralPoints: json["referralPoints"] == null ? null : ReferralPoints.fromJson(json["referralPoints"]),
      paymentTypes: json["payment_types"] == null ? null : PaymentTypes.fromJson(json["payment_types"]),
    );
  }
//convert to str
}

class AboutUs {
  AboutUs({
    required this.header,
    required this.address,
    required this.email,
    required this.phone,
  });

  final dynamic header;
  final String? address;
  final String? email;
  final String? phone;

  factory AboutUs.fromJson(Map<String, dynamic> json) {
    return AboutUs(
      header: json["header"],
      address: json["address"],
      email: json["email"],
      phone: json["phone"],
    );
  }
}

class ExchangeRate {
  ExchangeRate({
    required this.the05May,
    required this.the06May,
    required this.the07May,
    required this.the08May,
    required this.the09May,
    required this.the10May,
    required this.the11May,
    required this.the12May,
  });

  final String? the05May;
  final String? the06May;
  final String? the07May;
  final String? the08May;
  final String? the09May;
  final String? the10May;
  final String? the11May;
  final String? the12May;

  factory ExchangeRate.fromJson(Map<String, dynamic> json) {
    return ExchangeRate(
      the05May: json["05 May"],
      the06May: json["06 May"],
      the07May: json["07 May"],
      the08May: json["08 May"],
      the09May: json["09 May"],
      the10May: json["10 May"],
      the11May: json["11 May"],
      the12May: json["12 May"],
    );
  }
}

class ReferralPointsClass {
  ReferralPointsClass({
    required this.totalClaimedPoints,
    required this.totalUsedPoints,
    required this.totalRemainingPoints,
  });

  final int? totalClaimedPoints;
  final int? totalUsedPoints;
  final int? totalRemainingPoints;

  factory ReferralPointsClass.fromJson(Map<String, dynamic> json) {
    return ReferralPointsClass(
      totalClaimedPoints: json["total_claimed_points"],
      totalUsedPoints: json["total_used_points"],
      totalRemainingPoints: json["total_remaining_points"],
    );
  }
}

class IdType {
  IdType({
    required this.id,
    required this.name,
  });

  final int? id;
  final String? name;

  factory IdType.fromJson(Map<String, dynamic> json) {
    return IdType(
      id: json["id"],
      name: json["name"],
    );
  }
}

class OurBank {
  OurBank({
    required this.accountName,
    required this.bsb,
    required this.accountNumber,
    required this.bankName,
    required this.note,
  });

  final String? accountName;
  final String? bsb;
  final String? accountNumber;
  final String? bankName;
  final String? note;

  factory OurBank.fromJson(Map<String, dynamic> json) {
    return OurBank(
      accountName: json["Account Name"],
      bsb: json["BSB"],
      accountNumber: json["Account Number"],
      bankName: json["Bank Name"],
      note: json["Note"],
    );
  }
}

class PaymentTypes {
  PaymentTypes({
    required this.localRemit,
    required this.bankTransfer,
  });

  final BankTransfer? localRemit;
  final BankTransfer? bankTransfer;

  factory PaymentTypes.fromJson(Map<String, dynamic> json) {
    return PaymentTypes(
      localRemit: json["local_remit"] == null ? null : BankTransfer.fromJson(json["local_remit"]),
      bankTransfer: json["bank_transfer"] == null ? null : BankTransfer.fromJson(json["bank_transfer"]),
    );
  }
}

class BankTransfer {
  BankTransfer({
    required this.max,
    required this.min,
  });

  final String? max;
  final int? min;

  factory BankTransfer.fromJson(Map<String, dynamic> json) {
    return BankTransfer(
      max: json["max"],
      min: json["min"],
    );
  }
}

class ReferralPoints {
  ReferralPoints({
    required this.totalRemainingPoints,
  });

  final int? totalRemainingPoints;

  factory ReferralPoints.fromJson(Map<String, dynamic> json) {
    return ReferralPoints(
      totalRemainingPoints: json["totalRemainingPoints"],
    );
  }
}

class TodayRate {
  TodayRate({required this.date, required this.rates, required this.rate, this.changePercent, this.changeDirection, this.yesterdayRate});

  final DateTime? date;
  final List<Rate> rates;
  final String? rate;
  final String? changePercent;
  final String? changeDirection;
  final String? yesterdayRate;

  factory TodayRate.fromJson(Map<String, dynamic> json) {
    return TodayRate(
      date: DateTime.tryParse(json["date"] ?? ""),
      rates: json["rates"] == null ? [] : List<Rate>.from(json["rates"]!.map((x) => Rate.fromJson(x))),
      rate: json["rate"],
      changePercent: json["change_percent"]?.toString(),
      changeDirection: json["change_direction"]?.toString(),
      yesterdayRate: json["yesterday_rate"]?.toString(),
    );
  }
}

class Rate {
  Rate({
    required this.sendingAmount,
    required this.rate,
  });

  final String? sendingAmount;
  final String? rate;

  factory Rate.fromJson(Map<String, dynamic> json) {
    return Rate(
      sendingAmount: json["sending_amount"],
      rate: json["rate"],
    );
  }
}
