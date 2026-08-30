import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';

import '../common/routes/app_router.dart';

class AppSnackbar {
  AppSnackbar._();

// Singleton instance
  static final AppSnackbar instance = AppSnackbar._();

  static void showSnackBar({
    required BuildContext context,
    required String? message,
    String? key,
    int? snackBarDuration,
    bool snackBarAtTop = false,
    required SnackBarType type,
  }) {
    final messenger = rootScaffoldMessengerKey.currentState ??
        (context.mounted ? ScaffoldMessenger.maybeOf(context) : null);
    if (messenger == null) return;

    final snackbarContext = messenger.context;
    final mediaQuery = MediaQuery.maybeOf(snackbarContext) ??
        (context.mounted ? MediaQuery.maybeOf(context) : null);
    final textTheme = Theme.of(snackbarContext).textTheme;
    var isIos = false;

    if (kIsWeb) {
      isIos = false;
    } else {
      isIos = Platform.isIOS;
    }

    messenger.clearSnackBars();
    messenger.showSnackBar(
      SnackBar(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
        behavior: SnackBarBehavior.floating,
        margin: snackBarAtTop
            ? EdgeInsets.only(
                bottom: isIos
                    ? (mediaQuery?.size.height ?? 0) - 160
                    : (mediaQuery?.size.height ?? 0) - 580,
                left: 10,
                right: 10)
            : null,
        duration: Duration(seconds: snackBarDuration ?? 5),
        backgroundColor:
            type == SnackBarType.success ? AppColor.success : type == SnackBarType.info? AppColor.info : AppColor.redII,
        content: ListTile(
          dense: true,
          isThreeLine: false,
          contentPadding:
              const EdgeInsets.symmetric(horizontal: 0, vertical: 0),
          title: Text(
            message?.capitalizeFirstLetter() ?? "Something went wrong",
            style: textTheme.bodyMedium?.copyWith(color: AppColor.white),
            maxLines: 5,
          ),
          visualDensity: const VisualDensity(
            horizontal: 0,
            vertical: -4,
          ),
          // leading: SvgPicture.asset(
          //   icon,
          //   height: 24,
          //   width: 24,
          // ),
          trailing: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              const VerticalDivider(
                thickness: 1,
                color: AppColor.white,
                indent: 6,
                endIndent: 6,
              ),
              const SizedBox(width: 5),
              GestureDetector(
                onTap: () {
                  messenger.hideCurrentSnackBar();
                },
                child: Icon(
                  size: 24,
                  Icons.close,
                  color: AppColor.white,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  static void showSnackBarWithMessenger({
    required ScaffoldMessengerState messenger,
    required String? message,
    String? key,
    int? snackBarDuration,
    bool snackBarAtTop = false,
    required SnackBarType type,
  }) {
    final ctx = messenger.context;
    final mediaQuery = MediaQuery.maybeOf(ctx);
    var isIos = false;

    if (kIsWeb) {
      isIos = false;
    } else {
      isIos = Platform.isIOS;
    }

    messenger.clearSnackBars();
    messenger.showSnackBar(
      SnackBar(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
        behavior: SnackBarBehavior.floating,
        margin: snackBarAtTop
            ? EdgeInsets.only(
                bottom: isIos
                    ? (mediaQuery?.size.height ?? 0) - 160
                    : (mediaQuery?.size.height ?? 0) - 580,
                left: 10,
                right: 10)
            : null,
        duration: Duration(seconds: snackBarDuration ?? 5),
        backgroundColor:
            type == SnackBarType.success ? AppColor.success : AppColor.redII,
        content: ListTile(
          dense: true,
          isThreeLine: false,
          contentPadding:
              const EdgeInsets.symmetric(horizontal: 0, vertical: 0),
          title: Text(
            message?.toCapitalized() ?? "Something went wrong",
            style: Theme.of(ctx)
                .textTheme
                .bodyMedium
                ?.copyWith(color: AppColor.white),
            maxLines: 3,
          ),
          visualDensity: const VisualDensity(
            horizontal: 0,
            vertical: -4,
          ),
          trailing: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              const VerticalDivider(
                thickness: 1,
                color: AppColor.white,
                indent: 6,
                endIndent: 6,
              ),
              const SizedBox(width: 5),
              GestureDetector(
                onTap: () {
                  messenger.hideCurrentSnackBar();
                },
                child: Icon(
                  size: 24,
                  Icons.close,
                  color: AppColor.white,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

enum SnackBarType { info, warning, error, success }
