class GlobalState {
  GlobalState._();

  static final GlobalState _instance = GlobalState._();

  static GlobalState get instance => _instance;

  String? token;
}
