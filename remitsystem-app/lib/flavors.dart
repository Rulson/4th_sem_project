enum Flavor {
  local,
  development,
  production,
}


class AppUtils {
  AppUtils._();
  static Flavor _environment = Flavor.local;

  static void setEnvironment(Flavor env) {
    _environment = env;
  }

  static bool get isLocal => _environment == Flavor.local;
  static bool get isDevelopment => _environment == Flavor.development;
  static bool get isProduction => _environment == Flavor.production;

  static String get baseUrl {
    const map = {
      Flavor.local: "http://127.0.0.1:8000/api1",
      Flavor.development: "https://api-staging.remitsystem.com.au/api1",
      Flavor.production: "https://remit.remitsystem.com.au/api1",
    };
    return map[_environment]!;
  }
}