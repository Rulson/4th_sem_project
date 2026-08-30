import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';
import 'package:remit_management/core/utils/debouncer.dart';

class AppButton extends StatelessWidget {
  AppButton({
    super.key,
    required this.onPressed,
    required this.title,
    this.isLoading = false,
    this.isDisabled = false,
    this.icon,
    this.trailingIcon,
    this.customColor,
    this.textColor,
    this.iconColor,
    this.trailingIconColor,
    this.secondaryText,
    this.subTitleText,
    this.borderRadius,
    this.customBorder,
    this.height,
    this.variant = AppButtonVariant.primary,
  });

  final VoidCallback onPressed;
  final String title;
  final bool isLoading;
  final bool isDisabled;
  final String? icon;
  final String? trailingIcon;
  final Color? customColor;
  final Color? textColor;
  final Color? iconColor;
  final Color? trailingIconColor;
  final String? secondaryText;
  final String? subTitleText;
  final double? borderRadius;
  final Border? customBorder;
  final double? height;
  final AppButtonVariant variant;
  final DeBouncer deBouncer = DeBouncer(milliseconds: 500);

  @override
  Widget build(BuildContext context) {
    return TextButton(
      style: TextButton.styleFrom(
        minimumSize: const Size(10.0, 10.0),
        padding: EdgeInsets.zero,
        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
        alignment: Alignment.center,
      ).copyWith(overlayColor: WidgetStateProperty.all(Colors.transparent)),
      onPressed: () {
        if (!isLoading && !isDisabled) {
          deBouncer.run(onPressed);
        }
      },
      child: Container(
        height: height ?? 50,
        decoration: _buildDecoration(),
        child: isLoading ? const AppLoaderIndicator() : _buildContent(context),
      ),
    );
  }

  BoxDecoration _buildDecoration() {
    final bool inactive = isLoading || isDisabled;

    return switch (variant) {
      AppButtonVariant.primary => BoxDecoration(
          color: inactive ? AppColor.disabledColor : customColor ?? AppColor.primary,
          borderRadius: BorderRadius.circular(borderRadius ?? 15.0),
          border: customBorder,
        ),
      AppButtonVariant.outline => BoxDecoration(
          color: inactive ? AppColor.disabledColor : customColor ?? AppColor.white,
          borderRadius: BorderRadius.circular(borderRadius ?? 15.0),
          border: customBorder ?? Border.all(color: AppColor.gray200),
        ),
      AppButtonVariant.ghost => BoxDecoration(
          color: Colors.transparent,
          borderRadius: BorderRadius.circular(borderRadius ?? 15.0),
          border: customBorder,
        ),
    };
  }

  Widget _buildContent(BuildContext context) {
    final bool inactive = isLoading || isDisabled;

    final Color resolvedTextColor = textColor ??
        switch (variant) {
          AppButtonVariant.primary => inactive ? AppColor.labelTextColor : AppColor.white,
          AppButtonVariant.outline => inactive ? AppColor.labelTextColor : AppColor.gray700,
          AppButtonVariant.ghost => inactive ? AppColor.labelTextColor : AppColor.primary,
        };

    if (icon != null) {
      return Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          SvgPicture.asset(
            icon!,
            height: 24,
            width: 24,
            colorFilter: ColorFilter.mode(
              inactive ? AppColor.backgroundColor : iconColor ?? resolvedTextColor,
              BlendMode.srcIn,
            ),
          ),
          const SizedBox(width: 10),
          Flexible(
            child: Text(
              title,
              maxLines: 2,
              style: AppText.labelLarge600.copyWith(color: resolvedTextColor),
            ),
          ),
          if (trailingIcon != null) ...[
            const SizedBox(width: 10),
            SvgPicture.asset(
              trailingIcon!,
              height: 24,
              width: 24,
              colorFilter: ColorFilter.mode(
                inactive ? AppColor.backgroundColor : trailingIconColor ?? resolvedTextColor,
                BlendMode.srcIn,
              ),
            ),
          ],
        ],
      );
    }

    if (secondaryText != null) {
      return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 8),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: AppText.labelLarge600.copyWith(color: resolvedTextColor)),
            Text(secondaryText!, style: AppText.labelLarge600.copyWith(color: resolvedTextColor)),
          ],
        ),
      );
    }

    if (subTitleText != null) {
      return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 8),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              title,
              overflow: TextOverflow.ellipsis,
              style: AppText.labelLarge600.copyWith(color: resolvedTextColor),
            ),
            const SizedBox(height: 2),
            Text(
              subTitleText!,
              overflow: TextOverflow.ellipsis,
              style: AppText.bodyMedium500.copyWith(color: resolvedTextColor),
            ),
          ],
        ),
      );
    }

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          if (trailingIcon != null) const SizedBox(width: 34),
          Flexible(
            child: Text(
              title,
              textAlign: TextAlign.center,
              overflow: TextOverflow.ellipsis,
              style: AppText.labelLarge600.copyWith(color: resolvedTextColor),
            ),
          ),
          if (trailingIcon != null) ...[
            10.sBWw,
            SvgPicture.asset(
              trailingIcon!,
              height: 8.h,
              width: 8.w,
              colorFilter: ColorFilter.mode(
                inactive ? AppColor.backgroundColor : trailingIconColor ?? resolvedTextColor,
                BlendMode.srcIn,
              ),
            ),
          ],
        ],
      ),
    );
  }
}

enum AppButtonVariant { primary, outline, ghost }

class TwoButton extends StatelessWidget {
  const TwoButton({super.key, required this.buttonI, required this.buttonII});

  final AppButton buttonI;
  final AppButton buttonII;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: AppColor.white,
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        spacing: 10,
        children: [
          Expanded(
            flex: 1,
            child: buttonI,
          ),
          Expanded(
            flex: 1,
            child: buttonII,
          ),
        ],
      ),
    );
  }
}
