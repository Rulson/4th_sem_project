
class BankListModel {
    final int? id;
    final String? name;

    BankListModel({
        this.id,
        this.name,
    });

    BankListModel copyWith({
        int? id,
        String? name,
    }) => 
        BankListModel(
            id: id ?? this.id,
            name: name ?? this.name,
        );

    factory BankListModel.fromJson(Map<String, dynamic> json) => BankListModel(
        id: json["id"],
        name: json["name"],
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
    };
}
