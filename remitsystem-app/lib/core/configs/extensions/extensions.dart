import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

extension SpacedColumnExtension on List<Widget> {
  List<Widget> verticalSpacing({double spacing = 8.0}) {
    List<Widget> spacedChildren = [];

    for (int i = 0; i < length; i++) {
      spacedChildren.add(this[i]);
      if (i != length - 1) {
        spacedChildren.add(SizedBox(height: spacing));
      }
    }

    return spacedChildren;
  }
}

extension Sizedbox on num {
  SizedBox get sBHh => SizedBox(height: toDouble().h);
  SizedBox get sBHw => SizedBox(height: toDouble().w);

  SizedBox get sBWw => SizedBox(width: toDouble().w);
  SizedBox get sBWh => SizedBox(width: toDouble().h);
}

extension StringCasingExtension on String {
  String toCapitalized() => length > 0 ? '${this[0].toUpperCase()}${substring(1).toLowerCase()}' : '';
  String toTitleCase() => replaceAll(RegExp(' +'), ' ').split(' ').map((str) => str.toCapitalized()).join(' ');

  String capitalizeFirstLetter() {
    if (isEmpty) {
      return this;
    }
    return this[0].toUpperCase() + substring(1);
  }
}

extension SpacedRowExtension on List<Widget> {
  List<Widget> horizontalSpacing({double spacing = 8.0}) {
    List<Widget> spacedChildren = [];

    for (int i = 0; i < length; i++) {
      spacedChildren.add(this[i]);
      if (i != length - 1) {
        spacedChildren.add(SizedBox(width: spacing));
      }
    }

    return spacedChildren;
  }
}
