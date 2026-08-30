import 'dart:io';

import 'package:dotted_border/dotted_border.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class UploadWidget extends StatelessWidget {
  final String? imagePath;
  final VoidCallback? onTap;

  const UploadWidget({
    super.key,
    required this.imagePath,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: imagePath != null && imagePath!.isNotEmpty
          ? DottedBorder(
              options: RoundedRectDottedBorderOptions(radius: Radius.circular(12.r), strokeWidth: 1, color: AppColor.primary600, dashPattern: [6, 4]),
              child: Padding(
                padding: EdgeInsets.all(4.w),
                child: ClipRRect(
                    borderRadius: BorderRadius.circular(12.r), child: Image.file(File(imagePath!), width: double.infinity, height: 160.h, fit: BoxFit.cover)),
              ))
          : DottedBorder(
              options: RoundedRectDottedBorderOptions(radius: Radius.circular(12.r), strokeWidth: 1, color: AppColor.primary600, dashPattern: [6, 4]),
              child: Container(
                width: double.infinity,
                height: 160.h,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12.r),
                ),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Container(
                        decoration: const BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppColor.white,
                          boxShadow: [BoxShadow(color: AppColor.gray300, blurRadius: 5)],
                        ),
                        child: SvgPicture.asset(
                          SAppAssets.iconUpload,
                        )),
                    8.sBHh,
                    Text("Click to upload", style: AppText.bodyMedium400.copyWith(color: AppColor.gray1000)),
                  ],
                ),
              )),
    );
  }
}
