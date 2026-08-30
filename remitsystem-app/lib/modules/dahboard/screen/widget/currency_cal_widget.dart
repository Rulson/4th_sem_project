import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class CurrencyCalculatorWidget extends StatefulWidget {
  final String rate;
  final Function onSendMessageTap;

  const CurrencyCalculatorWidget({super.key, required this.rate, required this.onSendMessageTap});

  @override
  State<CurrencyCalculatorWidget> createState() => _CurrencyCalculatorWidgetState();
}

class _CurrencyCalculatorWidgetState extends State<CurrencyCalculatorWidget> {
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      margin: EdgeInsets.symmetric(vertical: 16.0),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [BoxShadow(color: AppColor.gray200, blurRadius: 5)],
      ),
      child: Column(children: [
        SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "You send",
                  style: AppText.labelLarge400,
                ),
                Text(
                  // "100.00",
                  "1",
                  style: AppText.titleMedium700,
                )
              ],
            ),
            Row(
              children: [
                Image.asset(
                  SAppAssets.imageAus,
                  height: 23,
                  width: 32,
                ),
                10.sBWw,
                Text(
                  "AUD",
                  style: AppText.titleMedium700,
                ),
              ],
            ),
          ],
        ),
        SizedBox(height: 16),
        16.sBHh,
        Row(
          children: [
            Expanded(
              // width: Utils.screenWidth(context),
              child: Divider(color: AppColor.black),
            ),
            Transform.rotate(angle: 2.1, child: SvgPicture.asset(SAppAssets.iconConverter)),
          ],
        ),
        10.sBHh,
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Receiver gets",
                  style: AppText.labelLarge400,
                ),
                Text(
                  // "8788.06",
                  widget.rate,
                  style: AppText.titleMedium700,
                ),
              ],
            ),
            Row(
              children: [
                Image.asset(
                  SAppAssets.imageNep,
                  height: 23,
                  width: 32,
                ),
                5.sBWw,
                Text(
                  "NPR",
                  style: AppText.titleMedium700,
                ),
              ],
            )
          ],
        ),
        SizedBox(height: 16),
      ]),
    );
  }
}
