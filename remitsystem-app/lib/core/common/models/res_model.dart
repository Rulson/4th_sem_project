class ResModel<T> {
  final String? message;
  final int? error;
  final T? data;
  final Count? count;

  ResModel({this.message, this.error, required this.data, this.count});

  ResModel.withError({
    required this.error,
    required this.message,
    this.data,
    this.count,
  });
}


class Count {
  final int? totalCount;
  final int? unreadCount;

  Count({
    this.totalCount,
    this.unreadCount,
  });

  Count copyWith({
    int? totalCount,
    int? unreadCount,
  }) =>
      Count(
        totalCount: totalCount ?? this.totalCount,
        unreadCount: unreadCount ?? this.unreadCount,
      );

  factory Count.fromJson(Map<String, dynamic> json) => Count(
        totalCount: json["total_count"],
        unreadCount: json["unread_count"],
      );

  Map<String, dynamic> toJson() => {
        "total_count": totalCount,
        "unread_count": unreadCount,
      };
}

class ApiResModel<T> {
  final int? error;
  final String? message;
  final T? data;
  final Count? count;

  const ApiResModel({
    this.error,
    this.message,
    this.data,
    this.count,
  });

  factory ApiResModel.fromJson(Map<String, dynamic> json) {
    // Handle both wrapped (response/data) and direct payloads. Also allow missing
    // status to be treated as success.
    final status = json['status'];
    final bool isSuccess = status == null || (status is int && status >= 200 && status < 300);

    if (!isSuccess) {
      final data = json['response'] ?? json['data'];
      final error = json['error'] ?? status;
      return ApiResModel(
        error: error,
        message: json["message"],
        count: Count.fromJson(json["count"] ?? {}),
        data: data,
      );
    }

    // Success path
    final payload = json['response'] ?? json['data'] ?? json;
    return ApiResModel(
      error: 0,
      message: json["message"],
      count: Count.fromJson(json["count"] ?? {}),
      data: payload,
    );
  }

  Map<String, dynamic> toJson() {
    Map<String, dynamic> res = <String, dynamic>{};
    res['error'] = error;
    res['message'] = message;
    res['count'] = count;
    res['response'] = data;
    return res;
  }
}
