import 'package:device_preview/device_preview.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:get_it/get_it.dart';
import 'package:remit_management/core/locator/locator.dart';
import 'package:remit_management/core/utils/local_db.dart';
import 'package:remit_management/modules/dahboard/screen/forgot_password/repository/forgot_password_repository.dart';

import 'app.dart';

final sl = GetIt.instance;

void setupLocator() {
  sl.registerLazySingleton<ForgotPasswordRepository>(() => ForgotPasswordRepositoryImpl());
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  await LocalDb.initStorage();
  await initializeDependencies();
  SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(statusBarColor: Colors.transparent, statusBarIconBrightness: Brightness.dark, statusBarBrightness: Brightness.light));
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
  ]);
  setupLocator();

  runApp(
    DevicePreview(
      enabled: false,
      builder: (context) => const MyApp(),
    ),
  );
}
