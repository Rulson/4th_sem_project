import 'package:flutter/material.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/utils.dart';

class CircularProfileWidget extends StatelessWidget {
  final String title;
  final double size;
  final bool highlight;
  final bool dimmed;

  const CircularProfileWidget({
    super.key,
    required this.title,
    this.size = 56,
    this.highlight = false,
    this.dimmed = false,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: size,
      width: size,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        height: size,
        width: size,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: highlight
              ? dimmed
                  ? const Color(0x332F6BFF)
                  : AppColor.primary
              : AppColor.gray200,
        ),
        child: Center(
          child: Text(
            Utils.getAbbreviation(title),
            style: AppText.bodySmall600.copyWith(
              color: highlight
                  ? dimmed
                      ? AppColor.primary
                      : AppColor.white
                  : AppColor.gray500,
              fontSize: size * 0.28,
            ),
          ),
        ),
      ),
    );
  }
}
