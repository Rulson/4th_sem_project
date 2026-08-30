import 'package:flutter/material.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

class InvitedFriendsScreen extends StatelessWidget {
  const InvitedFriendsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        // title: "Invited Friends",
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16.0),
        child: Column(
          children: [
            InvitedFriendWidget(name: "JOHN DOE", sendingAmount: "50.00", date: "Thu 21, Nov, 2024"),
            InvitedFriendWidget(name: "JOHN DOE", sendingAmount: "100.00", date: "Thu 21, Nov, 2024")
          ],
        ),
      ),
    );
  }
}

class InvitedFriendWidget extends StatelessWidget {
  final String name;
  final String sendingAmount;
  final String date;
  const InvitedFriendWidget({
    super.key,
    required this.name,
    required this.sendingAmount,
    required this.date,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.start,
            children: [
              Container(
                padding: EdgeInsets.all(10),
                decoration: BoxDecoration(shape: BoxShape.circle, color: AppColor.gray200),
                child: Icon(Icons.arrow_upward),
              ),
              10.sBWw,
              Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.start,
                children: [
                  Text(
                    name,
                    style: AppText.titleSmall,
                  ),
                  Text(
                    date,
                    style: AppText.bodySmall500.copyWith(color: AppColor.gray400),
                  ),
                ],
              ),
            ],
          ),
          Container(
            padding: EdgeInsets.all(10),
            decoration: BoxDecoration(color: AppColor.color700, borderRadius: BorderRadius.circular(20)),
            child: Text(
              sendingAmount,
              style: AppText.titleMedium500.copyWith(color: AppColor.white),
            ),
          ),
        ],
      ),
    );
  }
}
