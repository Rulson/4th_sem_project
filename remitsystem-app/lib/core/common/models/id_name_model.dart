class IdNameModel {
  final String id;
  final String name;

  IdNameModel({
    required this.id,
    required this.name,
  });

  factory IdNameModel.fromJson(Map<String, dynamic> json) {
    return IdNameModel(
      id: '${json['id']}',
      name: json['name'] ?? json['text'],
    );
  }
}
