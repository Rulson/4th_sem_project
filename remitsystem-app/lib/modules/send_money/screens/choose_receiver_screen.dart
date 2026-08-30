import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/custom_form_widget.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';

import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';
import 'package:remit_management/modules/receiver/screen/widget/recipients_container_widget.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_state.dart';

class ChooseReceiverScreen extends StatelessWidget {
  final ReceiverData? receiver;
  const ChooseReceiverScreen({super.key, this.receiver});

  @override
  Widget build(BuildContext context) {
    return ChosseReceiverView(
      receiver: receiver,
    );
  }
}

class ChosseReceiverView extends StatefulWidget {
  final ReceiverData? receiver;
  const ChosseReceiverView({super.key, this.receiver});

  @override
  State<ChosseReceiverView> createState() => _ChosseReceiverViewState();
}

class _ChosseReceiverViewState extends State<ChosseReceiverView> {
  String? _selectedReceiverId;
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    context.read<SendMoneyCubit>().resetSelectedReceiver();
    context.read<ReceiverListingCubit>().getReceiverList();
    if (widget.receiver != null) {
      _selectReceiver('${widget.receiver?.beneficiaryId}');
    }
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _selectReceiver(String? id) {
    setState(() => _selectedReceiverId = id);
    final selected = context.read<ReceiverListingCubit>().state.receiverList?.data?.firstWhere((e) => '${e.beneficiaryId}' == id);
    context.read<SendMoneyCubit>().setSelectedReceiver(selected);
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<SendMoneyCubit, SendMoneyState>(
      builder: (context, state) {
        final hasSelection = state.selectedReceiver != null;

        return Scaffold(
          // backgroundColor: Colors.transparent,
          appBar: CustomAppBar(
            // title: "Back",
            actions: [
              Text("2/4", style: AppText.bodyMedium400.copyWith(color: AppColor.gray500)),
            ],
          ),
          body: Padding(
            padding: EdgeInsets.symmetric(horizontal: 16.w),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Who are you sending to?",
                  style: AppText.headlineMedium400.copyWith(
                    color: AppColor.gray1000,
                    fontWeight: FontWeight.w800,
                    height: 1.2,
                  ),
                ),
                24.sBHh,
                Expanded(
                  child: Container(
                    padding: EdgeInsets.all(12.w),
                    decoration: BoxDecoration(
                      color: AppColor.white,
                      borderRadius: BorderRadius.circular(16.r),
                      border: Border.all(color: AppColor.gray200),
                    ),
                    child: SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          CustomFormFieldWidget(
                            controller: _searchController,
                            hint: "Search existing receiver",
                            prefixIcon: CupertinoIcons.search,
                            hideParentBorder: true,
                            onChanged: (value) {
                              context.read<ReceiverListingCubit>().searchReceiver(value);
                            },
                          ),
                          12.sBHh,
                          RecipientsContainerWidget(
                            title: "Frequent Recipients",
                            hilightId: _selectedReceiverId,
                            onRecipientTap: (receiver) {
                              _selectReceiver('${receiver.beneficiaryId}');
                            },
                          ),
                          24.sBHh,

                          // Empty state or selected receiver card
                          if (!hasSelection) _buildEmptyState() else _buildReceiverCard(context, state),
                          64.sBHh,
                          AppButton(
                            height: 54.h,
                            trailingIcon: SAppAssets.iconArrowRight,
                            isDisabled: !hasSelection,
                            onPressed: () => context.push(AppRoutes.sendReceipt),
                            title: "Continue",
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
                24.sBHh,
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        children: [
          40.sBHh,
          Image.asset(
            SAppAssets.imageChooseReceiver,
            height: 140.h,
            fit: BoxFit.contain,
          ),
          24.sBHh,
          Text(
            "Choose a recipient",
            style: AppText.headlineSmall700.copyWith(
              color: AppColor.gray900,
              fontWeight: FontWeight.w700,
            ),
          ),
          8.sBHh,
          Text(
            "Choose from your Recipients or add a new one",
            style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
            textAlign: TextAlign.center,
          ),
          24.sBHh,
          GestureDetector(
            onTap: () => context.push(AppRoutes.addReceiver),
            child: Container(
              padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 12.h),
              decoration: BoxDecoration(
                color: AppColor.white,
                borderRadius: BorderRadius.circular(400.r),
                border: Border.all(color: AppColor.gray200),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.add, size: 18.sp, color: AppColor.gray700),
                  8.sBWw,
                  Text(
                    "Add new recipient",
                    style: AppText.bodyMedium500.copyWith(color: AppColor.gray700),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildReceiverCard(BuildContext context, SendMoneyState state) {
    final receiver = state.selectedReceiver!;

    return Container(
      padding: EdgeInsets.all(20.w),
      decoration: BoxDecoration(
        color: AppColor.white,
        borderRadius: BorderRadius.circular(16.r),
        border: Border.all(color: AppColor.gray200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                receiver.fullName ?? "",
                style: AppText.titleMedium700.copyWith(color: AppColor.gray1000),
              ),
              BlocListener<ReceiverListingCubit, ReceiverListingState>(
                listener: (context, rstate) {
                  if (rstate.isLoading == AppState.success) {
                    _selectReceiver(receiver.beneficiaryId?.toString());
                  }
                },
                child: GestureDetector(
                  onTap: () {
                    context.push(AppRoutes.addReceiver, extra: {
                      'receiver': receiver,
                      'isEdit': true,
                    }).then((_) {
                      if (context.mounted) {
                        context.read<ReceiverListingCubit>().getReceiverList();
                      }
                    });
                  },
                  child: Row(
                    children: [
                      SvgPicture.asset(
                        SAppAssets.iconEditProfile,
                        width: 16.w,
                        colorFilter: const ColorFilter.mode(AppColor.primary600, BlendMode.srcIn),
                      ),
                      6.sBWw,
                      Text(
                        "Edit",
                        style: AppText.bodySmall600.copyWith(color: AppColor.primary600),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
          12.sBHh,

          // Phone
          Row(
            children: [
              Icon(Icons.phone_outlined, size: 16.sp, color: AppColor.gray500),
              8.sBWw,
              Text(
                receiver.number ?? "",
                style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
              ),
            ],
          ),
          8.sBHh,

          // Location
          Row(
            children: [
              Icon(Icons.location_on_outlined, size: 16.sp, color: AppColor.gray500),
              8.sBWw,
              Expanded(
                child: Text(
                  [receiver.district, receiver.state].where((e) => e != null && e.isNotEmpty).join(", "),
                  style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                ),
              ),
            ],
          ),
          16.sBHh,

          // Bank details section
          Text(
            "BANK DETAILS",
            style: AppText.labelMedium600.copyWith(
              color: AppColor.primary600,
              letterSpacing: 0.8,
            ),
          ),
          8.sBHh,
          Container(
            width: double.infinity,
            padding: EdgeInsets.all(14.w),
            decoration: BoxDecoration(
              color: AppColor.primary600.withAlpha(12),
              borderRadius: BorderRadius.circular(10.r),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  receiver.bankName ?? "",
                  style: AppText.bodySmall500.copyWith(color: AppColor.gray800),
                ),
                6.sBHh,
                Text(
                  "Account: ${receiver.accountNo ?? ""}",
                  style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                ),
                6.sBHh,
                Text(
                  "Account Name: ${receiver.accountName ?? ""}",
                  style: AppText.bodySmall400.copyWith(color: AppColor.gray600),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
