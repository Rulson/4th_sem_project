
import 'package:remit_management/flavors.dart';
import 'main.dart' as runner;

void main() {
  AppUtils.setEnvironment(Flavor.development);
  runner.main();
}
