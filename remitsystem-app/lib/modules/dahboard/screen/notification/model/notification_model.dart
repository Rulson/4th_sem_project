import '../../../../../core/utils/utils.dart';

class NotificationListModel {
  final Response? response;
  final Count? count;
  final String? message;
  final int? status;

  NotificationListModel({
    this.response,
    this.count,
    this.message,
    this.status,
  });

  NotificationListModel copyWith({
    Response? response,
    Count? count,
    String? message,
    int? status,
  }) =>
      NotificationListModel(
        response: response ?? this.response,
        count: count ?? this.count,
        message: message ?? this.message,
        status: status ?? this.status,
      );

  factory NotificationListModel.fromJson(Map<String, dynamic> json) => NotificationListModel(
        response: json["response"] == null ? null : Response.fromJson(json["response"]),
        count: json["count"] == null ? null : Count.fromJson(json["count"]),
        message: json["message"],
        status: json["status"],
      );

  Map<String, dynamic> toJson() => {
        "response": response?.toJson(),
        "count": count?.toJson(),
        "message": message,
        "status": status,
      };
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

class Response {
  final int? currentPage;
  final List<NotificationData>? data;
  final String? firstPageUrl;
  final int? from;
  final int? lastPage;
  final String? lastPageUrl;
  final List<Link>? links;
  final String? nextPageUrl;
  final String? path;
  final int? perPage;
  final String? prevPageUrl;
  final int? to;
  final int? total;

  Response({
    this.currentPage,
    this.data,
    this.firstPageUrl,
    this.from,
    this.lastPage,
    this.lastPageUrl,
    this.links,
    this.nextPageUrl,
    this.path,
    this.perPage,
    this.prevPageUrl,
    this.to,
    this.total,
  });

  Response copyWith({
    int? currentPage,
    List<NotificationData>? data,
    String? firstPageUrl,
    int? from,
    int? lastPage,
    String? lastPageUrl,
    List<Link>? links,
    dynamic nextPageUrl,
    String? path,
    int? perPage,
    dynamic prevPageUrl,
    int? to,
    int? total,
  }) =>
      Response(
        currentPage: currentPage ?? this.currentPage,
        data: data ?? this.data,
        firstPageUrl: firstPageUrl ?? this.firstPageUrl,
        from: from ?? this.from,
        lastPage: lastPage ?? this.lastPage,
        lastPageUrl: lastPageUrl ?? this.lastPageUrl,
        links: links ?? this.links,
        nextPageUrl: nextPageUrl ?? this.nextPageUrl,
        path: path ?? this.path,
        perPage: perPage ?? this.perPage,
        prevPageUrl: prevPageUrl ?? this.prevPageUrl,
        to: to ?? this.to,
        total: total ?? this.total,
      );

  factory Response.fromJson(Map<String, dynamic> json) => Response(
        currentPage: json["current_page"],
        data: json["data"] == null ? [] : List<NotificationData>.from(json["data"]!.map((x) => NotificationData.fromJson(x))),
        firstPageUrl: json["first_page_url"],
        from: json["from"],
        lastPage: json["last_page"],
        lastPageUrl: json["last_page_url"],
        links: json["links"] == null ? [] : List<Link>.from(json["links"]!.map((x) => Link.fromJson(x))),
        nextPageUrl: json["next_page_url"],
        path: json["path"],
        perPage: json["per_page"],
        prevPageUrl: json["prev_page_url"],
        to: json["to"],
        total: json["total"],
      );

  Map<String, dynamic> toJson() => {
        "current_page": currentPage,
        "data": data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson())),
        "first_page_url": firstPageUrl,
        "from": from,
        "last_page": lastPage,
        "last_page_url": lastPageUrl,
        "links": links == null ? [] : List<dynamic>.from(links!.map((x) => x.toJson())),
        "next_page_url": nextPageUrl,
        "path": path,
        "per_page": perPage,
        "prev_page_url": prevPageUrl,
        "to": to,
        "total": total,
      };
}

class NotificationData {
  final String? id;
  final String? type;
  final String? notifiableType;
  final String? notifiableId;
  final NotificationDetail? data;
  final String? readAt;
  final String? createdAt;
  final String? updatedAt;

  NotificationData({
    this.id,
    this.type,
    this.notifiableType,
    this.notifiableId,
    this.data,
    this.readAt,
    this.createdAt,
    this.updatedAt,
  });

  NotificationData copyWith({
    String? id,
    String? type,
    String? notifiableType,
    String? notifiableId,
    NotificationDetail? data,
    String? readAt,
    String? createdAt,
    String? updatedAt,
  }) =>
      NotificationData(
        id: id ?? this.id,
        type: type ?? this.type,
        notifiableType: notifiableType ?? this.notifiableType,
        notifiableId: notifiableId ?? this.notifiableId,
        data: data ?? this.data,
        readAt: readAt ?? this.readAt,
        createdAt: createdAt ?? this.createdAt,
        updatedAt: updatedAt ?? this.updatedAt,
      );

  factory NotificationData.fromJson(Map<String, dynamic> json) => NotificationData(
        id: json["id"],
        type: json["type"],
        notifiableType: json["notifiable_type"],
        notifiableId: Utils.parseString(json['notifiable_id']),
        data: json["data"] == null ? null : NotificationDetail.fromJson(json["data"]),
        readAt: json["read_at"],
        createdAt: json["created_at"],
        updatedAt: json["updated_at"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "type": type,
        "notifiable_type": notifiableType,
        "notifiable_id": notifiableId,
        "data": data?.toJson(),
        "read_at": readAt,
        "created_at": createdAt,
        "updated_at": updatedAt,
      };
}

class NotificationDetail {
  final String? title;
  final String? email;
  final String? name;
  final int? transactionId;
  final String? message;

  NotificationDetail({
    this.title,
    this.email,
    this.name,
    this.transactionId,
    this.message,
  });

  NotificationDetail copyWith({
    String? title,
    String? email,
    String? name,
    int? transactionId,
    String? message,
  }) =>
      NotificationDetail(
        title: title ?? this.title,
        email: email ?? this.email,
        name: name ?? this.name,
        transactionId: transactionId ?? this.transactionId,
        message: message ?? this.message,
      );

  factory NotificationDetail.fromJson(Map<String, dynamic> json) => NotificationDetail(
        title: json["title"],
        email: json["email"],
        name: json["name"],
        transactionId: json["transaction_id"],
        message: json["message"],
      );

  Map<String, dynamic> toJson() => {
        "title": title,
        "email": email,
        "name": name,
        "transaction_id": transactionId,
        "message": message,
      };
}

class Link {
  final String? url;
  final String? label;
  final int? page;
  final bool? active;

  Link({
    this.url,
    this.label,
    this.page,
    this.active,
  });

  Link copyWith({
    String? url,
    String? label,
    int? page,
    bool? active,
  }) =>
      Link(
        url: url ?? this.url,
        label: label ?? this.label,
        page: page ?? this.page,
        active: active ?? this.active,
      );

  factory Link.fromJson(Map<String, dynamic> json) => Link(
        url: json["url"],
        label: json["label"],
        page: json["page"],
        active: json["active"],
      );

  Map<String, dynamic> toJson() => {
        "url": url,
        "label": label,
        "page": page,
        "active": active,
      };
}
