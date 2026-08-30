import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class ReferScreen extends StatelessWidget {
  const ReferScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: AppColor.white,
        leading: GestureDetector(
          onTap: () => context.pop(),
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Icon(
              Icons.arrow_back_ios_new,
              size: 20,
            ),
          ),
        ),
        title: Text(
          "Refer",
          style: AppText.titleMedium700,
        ),
        centerTitle: true,
        actions: [
          Container(
            padding: EdgeInsets.all(5),
            decoration: BoxDecoration(color: AppColor.color50, borderRadius: BorderRadius.circular(20)),
            child: GestureDetector(
                onTap: () {
                  context.push(AppRoutes.invitedFriends);
                },
                child: Text("Track Invites", style: AppText.labelMediumSemiBold.copyWith(color: AppColor.color700))),
          ),
          20.sBWw,
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
                child: Column(
              children: [
                Center(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 30.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        25.sBHh,
                        Text("Share and earn 5 AUD", style: AppText.headlineMedium400),
                        10.sBHh,
                        Text("Refer your friends and earn 5 AUD for each successful sign-up! Share the benefits and get rewarded today.",
                            textAlign: TextAlign.center, style: AppText.bodySmall500.copyWith(color: AppColor.gray700)),
                        Image.asset(
                          SAppAssets.imageReferShout,
                          height: 179,
                        )
                      ],
                    ),
                  ),
                ),
                Text(
                  "Invite Via",
                  style: AppText.titleMedium600,
                ),
                InviteWidget(
                  title: "Referral Code",
                  value: "5215465",
                ),
                InviteWidget(
                  title: "Referral Link",
                  value: "https://yourapp.com/referral?code=R8F3K2MZ",
                ),
              ],
            )),
            AppButton(onPressed: () {}, title: "Invite friends"),
            50.sBHh,
          ],
        ),
      ),
    );
  }
}

class InviteWidget extends StatelessWidget {
  final String title;
  final String value;
  const InviteWidget({
    super.key,
    required this.title,
    required this.value,
  });

  String truncateMiddle(String text, int maxLength) {
    if (text.length <= maxLength) return text;
    final half = ((maxLength - 3) / 2).floor();
    return '${text.substring(0, half)}...${text.substring(text.length - half)}';
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(20),
      margin: const EdgeInsets.symmetric(vertical: 15),
      decoration: BoxDecoration(border: Border.all(color: AppColor.gray200), borderRadius: BorderRadius.circular(10)),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: AppText.labelMedium500,
          ),
          Row(
            children: [
              Text(
                truncateMiddle(value, 25),
                style: AppText.bodyMedium600,
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
              10.sBWw,
              GestureDetector(onTap: () {}, child: SvgPicture.asset(SAppAssets.iconCopy))
            ],
          )
        ],
      ),
    );
  }
}
