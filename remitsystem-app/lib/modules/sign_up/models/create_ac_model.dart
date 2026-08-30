class CheckEmailAvailabilityAndSendOtpModel {
  CheckEmailAvailabilityAndSendOtpModel({
    required this.message,
    required this.status,
  });

  final String? message;
  final int? status;

  factory CheckEmailAvailabilityAndSendOtpModel.fromJson(Map<dynamic, dynamic> json) {
    return CheckEmailAvailabilityAndSendOtpModel(
      message: json["message"],
      status: json["status"],
    );
  }

  @override
  String toString() {
    return "$message, $status, ";
  }
}

class RegisterModel {
  RegisterModel({
    required this.response,
    required this.message,
    required this.status,
  });

  final Map<String, List<String>> response;
  final String? message;
  final int? status;

  factory RegisterModel.fromJson(Map<dynamic, dynamic>? json) {
    final responseJson = json?['response'] as Map?;

    return RegisterModel(
      response: responseJson?.map(
            (k, v) => MapEntry(
              k.toString(),
              v == null ? <String>[] : List<String>.from(v),
            ),
          ) ??
          <String, List<String>>{},
      message: json?['message'],
      status: json?['status'],
    );
  }

  @override
  String toString() {
    return "$response, $message, $status, ";
  }
}
