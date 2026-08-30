import 'package:flutter/material.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';

class AppLoaderOverlay extends StatelessWidget {
  final bool isLoading;
  final Widget child;

  const AppLoaderOverlay({
    super.key,
    required this.isLoading,
    required this.child,
  });

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        child,
        if (isLoading)
          Container(
            color: Colors.black.withAlpha(150),
            child: const Center(child: AppLoaderIndicator()),
          ),
      ],
    );
  }
}
