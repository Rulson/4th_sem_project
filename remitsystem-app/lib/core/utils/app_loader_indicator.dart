import 'package:flutter/cupertino.dart';

class AppLoaderIndicator extends StatelessWidget {
  const AppLoaderIndicator({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return const Center(
        child: SizedBox(
            height: 40, width: 40, child: CupertinoActivityIndicator()));
  }
}
