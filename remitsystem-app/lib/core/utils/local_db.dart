import 'package:hive_flutter/adapters.dart';
import 'package:remit_management/core/resource/app_string_const.dart';

class LocalDb {
  LocalDb._();
  static Future<void> initStorage() async {
    await Hive.initFlutter();
    await Hive.openBox(AppStringConst.application);
  }

  static Future<void> saveData({required String key, dynamic value}) async {
    await Hive.box(AppStringConst.application).put(key, value);
  }

  static T? getData<T>({required String key}) {
    return Hive.box(AppStringConst.application).get(key);
  }

  static Future<void> deleteData({required String key}) async {
    await Hive.box(AppStringConst.application).delete(key);
  }

  static Future<void> removeToken() async {
    await Hive.box(AppStringConst.application).delete(AppStringConst.apiToken);
  }

///////////////////////
  // static Future<void> storeUser(LocalUserModel user) async {
  //   var box = Hive.box(AppStringConst.application);
  //   var userData = box.get(SConstKeys.localUserDetail);
  //   if (userData != null) {
  //     var updatedUser = LocalUserModel(
  //       userId: user.userId ?? userData['id'],
  //       username: user.username ?? userData['username'],
  //       fullName: user.fullName ?? userData['full_name'],
  //       image: user.image ?? userData['image'],
  //       accountType: user.accountType ?? userData['account_type'],
  //     );
  //     await box.put(SConstKeys.localUserDetail, updatedUser.toMap());
  //   } else {
  //     Hive.box(AppStringConst.application)
  //         .put(SConstKeys.localUserDetail, user.toMap());
  //   }
  // }

//   static LocalUserModel? getUser() {
//     var box = Hive.box(AppStringConst.application);
//     var userData = box.get(SConstKeys.localUserDetail);

//     if (userData != null) {
//       return LocalUserModel.fromMap(userData);
//     }
//     return null;
//   }

//   static Future<void> deleteUser() async {
//     var box = Hive.box(AppStringConst.application);
//     await box.delete(SConstKeys.localUserDetail);
//   }
}
