import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_state.dart';

import '../../../core/common/widget/custom_app_bar.dart';

class SendMoneyScreen extends StatelessWidget {
  final ReceiverData? receiver;
  const SendMoneyScreen({super.key, this.receiver});

  @override
  Widget build(BuildContext context) {
    return SendMoneyView(
      receiver: receiver,
    );
  }
}

class SendMoneyView extends StatefulWidget {
  final ReceiverData? receiver;
  const SendMoneyView({super.key, this.receiver});

  @override
  State<SendMoneyView> createState() => _SendMoneyViewState();
}

class _SendMoneyViewState extends State<SendMoneyView> {
  late final TextEditingController nprController;
  late final TextEditingController audController;
  final FocusNode audFocusNode = FocusNode();

  @override
  void initState() {
    super.initState();
    audController = context.read<SendMoneyCubit>().audController;
    nprController = context.read<SendMoneyCubit>().nprController;
    
    if (widget.receiver != null) {
      context.read<SendMoneyCubit>().setSelectedReceiver(widget.receiver!);
    }
    WidgetsBinding.instance.addPostFrameCallback((_) {
      audFocusNode.requestFocus();
    });
  }

  @override
  void dispose() {
    audFocusNode.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // backgroundColor: Colors.transparent,
      appBar: CustomAppBar(
        // title: "Back",
        actions: [
          Text("1/4", style: AppText.bodyMedium400.copyWith(color: AppColor.gray500)),
        ],
      ),
      body: BlocBuilder<HomeCubit, HomeState>(
        builder: (context, homeState) {
          final fee = context.read<HomeCubit>().state.homeModel?.data?.serviceCharge ?? "0.0";
          final exchangeRate = homeState.homeModel?.data?.todayRate?.rate ?? 0;

          return SafeArea(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  12.sBHh,
                  Text(
                    "How much do you\nwant to send?",
                    style: AppText.headlineMedium400.copyWith(
                      color: AppColor.gray900,
                      fontWeight: FontWeight.w800,
                      height: 1.2,
                    ),
                  ),
                  20.sBHh,
                  Expanded(
                    child: Container(
                      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16.r), color: AppColor.white),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(16.r),
                        child: SingleChildScrollView(
                          padding: EdgeInsets.all(16.w),
                          child: BlocBuilder<SendMoneyCubit, SendMoneyState>(
                            builder: (context, moneyState) {
                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  // YOU SEND
                                  Text(
                                    "You will Send",
                                    style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                                  ),
                                  8.sBHh,
                                  _AmountField(
                                    controller: audController,
                                    focusNode: audFocusNode,
                                    flagEmoji: '🇦🇺',
                                    currencyCode: 'AUD',
                                    onChanged: (value) {
                                      String clean = value.replaceAll(RegExp(r'[^0-9.]'), '');
                                      if (clean.isEmpty) clean = '0';
                                      nprController.text = Utils.convertAudToNpr(
                                        aud: clean,
                                        todayRate: '$exchangeRate',
                                      ).toString();
                                      context.read<SendMoneyCubit>().setSubTotalConvertedAmt(
                                            subTotalConvertedAmt: '${double.parse(clean) + double.parse(fee)}',
                                            sendingAmount: clean,
                                          );
                                      setState(() {});
                                    },
                                  ),
                                  16.sBHh,

                                  // THEY RECEIVE
                                  Text(
                                    "They will receive",
                                    style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                                  ),
                                  8.sBHh,
                                  _AmountField(
                                    controller: nprController,
                                    flagEmoji: '🇳🇵',
                                    currencyCode: 'NPR',
                                    readOnly: true,
                                  ),
                                  16.sBHh,

                                  // Rate row
                                  Row(
                                    children: [
                                      Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Row(
                                            crossAxisAlignment: CrossAxisAlignment.baseline,
                                            textBaseline: TextBaseline.alphabetic,
                                            children: [
                                              Text(
                                                "$exchangeRate",
                                                style: AppText.headlineSmall700.copyWith(
                                                  color: AppColor.gray900,
                                                ),
                                              ),
                                              6.sBWw,
                                              Text(
                                                "NPR",
                                                style: AppText.bodySmall500.copyWith(
                                                  color: AppColor.gray500,
                                                ),
                                              ),
                                            ],
                                          ),
                                          4.sBHh,
                                        ],
                                      ),
                                      const Spacer(),
                                      Column(
                                        crossAxisAlignment: CrossAxisAlignment.end,
                                        children: [
                                          Row(
                                            children: [
                                              Text(
                                                "Exclusive rate",
                                                style: AppText.bodySmall500.copyWith(color: AppColor.gray600),
                                              ),
                                              4.sBWw,
                                              Icon(Icons.trending_up, size: 14.sp, color: AppColor.green),
                                            ],
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                  Divider(height: 24.h, color: AppColor.gray200),

                                  // Delivery method
                                  Text(
                                    "Delivery Method",
                                    style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                                  ),
                                  8.sBHh,
                                  Container(
                                    padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 14.h),
                                    decoration: BoxDecoration(
                                      color: AppColor.gray100,
                                      borderRadius: BorderRadius.circular(12.r),
                                    ),
                                    child: Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Bank Deposit",
                                          style: AppText.bodyMedium600.copyWith(color: AppColor.gray900),
                                        ),
                                        // Icon(Icons.keyboard_arrow_down, color: AppColor.gray600, size: 20.sp),
                                      ],
                                    ),
                                  ),
                                  Divider(height: 24.h, color: AppColor.gray200),

                                  // Fees
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        "Transaction Fees",
                                        style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
                                      ),
                                      Row(
                                        children: [
                                          Text(
                                            "$fee AUD",
                                            style: AppText.bodySmall400.copyWith(
                                              color: AppColor.gray400,
                                              decoration: (fee == "0") ? TextDecoration.lineThrough : null,
                                              decorationColor: AppColor.gray400,
                                            ),
                                          ),
                                          // 6.sBWw,
                                          // Text(
                                          //   "${moneyState.sendingAmount?? 0} AUD",
                                          //   style: AppText.bodySmall600.copyWith(color: AppColor.gray900),
                                          // ),
                                        ],
                                      ),
                                    ],
                                  ),
                                  12.sBHh,
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        "Subtotal",
                                        style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
                                      ),
                                      Text(
                                        "${moneyState.subTotalConvertedAmt.isEmpty ? fee : moneyState.subTotalConvertedAmt} AUD",
                                        style: AppText.bodyMedium700.copyWith(color: AppColor.primary),
                                      ),
                                    ],
                                  ),
                                  48.sBHh,
                                  AppButton(
                                    height: 54.h,
                                    trailingIcon: SAppAssets.iconArrowRight,
                                    isDisabled: moneyState.subTotalConvertedAmt.isEmpty ||
                                        moneyState.subTotalConvertedAmt == '0.0' ||
                                        (double.tryParse(moneyState.sendingAmount ?? "") ?? 0) <= 5,
                                    onPressed: () => context.push(AppRoutes.chooseReceiver, extra: widget.receiver),
                                    title: "Continue",
                                  ),
                                ],
                              );
                            },
                          ),
                        ),
                      ),
                    ),
                  ),
                  16.sBHh,
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class CurrencyInputFormatter extends TextInputFormatter {
  @override
  TextEditingValue formatEditUpdate(TextEditingValue oldValue, TextEditingValue newValue) {
    if (newValue.selection.baseOffset == 0) {
      return newValue;
    }

    String cleanedText = newValue.text.replaceAll(RegExp(r'[^0-9]'), '');

    double value = double.parse(cleanedText);
    final formatter = NumberFormat("#,###", "en_US");
    String newText = formatter.format(value);

    return newValue.copyWith(
      text: newText,
      selection: TextSelection.collapsed(offset: newText.length - 4),
    );
  }
}

class _AmountField extends StatelessWidget {
  final TextEditingController controller;
  final FocusNode? focusNode;
  final String flagEmoji;
  final String currencyCode;
  final bool readOnly;
  final Function(String)? onChanged;

  const _AmountField({
    required this.controller,
    required this.flagEmoji,
    required this.currencyCode,
    this.focusNode,
    this.readOnly = false,
    this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 4.h),
      decoration: BoxDecoration(
        color: AppColor.gray100,
        borderRadius: BorderRadius.circular(12.r),
      ),
      child: Row(
        children: [
          Expanded(
            child: TextFormField(
              controller: controller,
              focusNode: focusNode,
              onTapOutside: (event) {
                focusNode?.unfocus();
              },
              readOnly: readOnly,
              keyboardType: TextInputType.number,
              onChanged: onChanged,
              style: AppText.titleMedium400.copyWith(color: AppColor.gray900),
              decoration: InputDecoration(
                border: InputBorder.none,
                hintText: "0",
                hintStyle: AppText.titleMedium400.copyWith(color: AppColor.gray400),
                isDense: true,
                contentPadding: EdgeInsets.symmetric(vertical: 14.h),
              ),
            ),
          ),
          Container(
            padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 6.h),
            decoration: BoxDecoration(
              color: AppColor.white,
              borderRadius: BorderRadius.circular(400.r),
              border: Border.all(color: AppColor.gray200),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(flagEmoji, style: TextStyle(fontSize: 16.sp)),
                6.sBWw,
                Text(
                  currencyCode,
                  style: AppText.bodySmall600.copyWith(color: AppColor.gray900),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
