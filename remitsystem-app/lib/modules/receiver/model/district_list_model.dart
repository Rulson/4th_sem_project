class DistrictListModel {
  final int? id;
  final String? name;

  const DistrictListModel({this.id, this.name});

  factory DistrictListModel.fromJson(Map<String, dynamic> json) {
    return DistrictListModel(
      id: json['id'],
      name: json['name'],
    );
  }
}
