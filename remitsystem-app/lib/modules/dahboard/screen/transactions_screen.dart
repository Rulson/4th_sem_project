import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_state.dart';
import 'package:remit_management/modules/dahboard/models/transaction_list_model.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';

class TransactionsScreen extends StatelessWidget {
  const TransactionsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const TransctionsView();
  }
}

class TransctionsView extends StatefulWidget {
  const TransctionsView({super.key});

  @override
  State<TransctionsView> createState() => _TransctionsViewState();
}

class _TransctionsViewState extends State<TransctionsView> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<TransactionListCubit, TransactionListState>(
      builder: (context, state) {
        if (state.transactionListLoading == AppState.loading) {
          return const AppLoaderIndicator();
        }
        return ListView.separated(
          separatorBuilder: (context, index) => 16.sBHh,
          itemCount: state.transactionList?.data?.length ?? 0,
          itemBuilder: (context, index) {
            final TransactionData? transaction = state.transactionList?.data?[index];
            if (transaction == null) {
              return const SizedBox.shrink();
            }
            return TransactionWidget(
              transaction: transaction,
            );
          },
        );
      },
    );
  }
}

class TransactionWidget extends StatelessWidget {
  final TransactionData transaction;
  const TransactionWidget({super.key, required this.transaction});

  @override
  Widget build(BuildContext context) {
    final isDelivered = transaction.status?.toLowerCase() == "confirmed" || transaction.status?.toLowerCase() == "delivered" || transaction.status?.toLowerCase() == "transferred";

    final statusBg = isDelivered ? AppColor.borderSuccessSubtle.withValues(alpha: 0.3) : AppColor.borderWarningSubtle;
    final statusText = isDelivered ? AppColor.bgSuccessStrong : AppColor.textFgWarning;
    final statusBorder = isDelivered ? AppColor.borderSuccessSubtle : AppColor.gray200;

    return Container(
      padding: EdgeInsets.all(16.r),
      decoration: BoxDecoration(
        color: AppColor.white,
        borderRadius: BorderRadius.circular(16.r),
        border: Border.all(color: AppColor.gray200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CircularProfileWidget(
                title: transaction.beneficiaryName ?? "N/A",
                size: 44.r,
                highlight: false,
              ),
              12.sBWw,
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      transaction.beneficiaryName ?? "N/A",
                      style: AppText.bodyMedium600.copyWith(color: AppColor.gray900),
                    ),
                    4.sBHh,
                    Text(
                      "ID: ${transaction.transactionId ?? "T0000"} • ${transaction.transactionDate ?? "Date"}",
                      style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
                    ),
                  ],
                ),
              ),
              Container(
                padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                decoration: BoxDecoration(
                  color: statusBg,
                  borderRadius: BorderRadius.circular(8.r),
                  border: Border.all(color: statusBorder),
                ),
                child: Text(
                  transaction.status ?? "Pending",
                  style: AppText.bodySmall600.copyWith(color: statusText, fontSize: 12.sp),
                ),
              ),
            ],
          ),
          16.sBHh,
          Container(
            padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 12.h),
            decoration: BoxDecoration(
              color: Color(0xFFEEF6FF),
              borderRadius: BorderRadius.circular(12.r),
            ),
            child: Column(
              children: [
                _buildSummaryRow("Sent", "${transaction.totalAmount ?? "0"} AUD", isValueBlue: false),
                10.sBHh,
                _buildSummaryRow("Recipient gets", "${transaction.paymentAmount ?? "0"} NPR", isValueBlue: true),
              ],
            ),
          ),
          8.sBHh,
          Divider(color: AppColor.gray200),
          8.sBHh,
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: AppButton(
                  onPressed: () => context.push(AppRoutes.transactionDetail, extra: transaction),
                  title: "Track Transfer",
                  height: 36.h,
                  borderRadius: 40.r,
                  customColor: AppColor.primary.withValues(alpha: 0.1),
                  textColor: AppColor.primary,
                  trailingIcon: SAppAssets.iconArrowForward,
                  trailingIconColor: AppColor.primary,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value, {required bool isValueBlue}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: AppText.bodyMedium400.copyWith(color: AppColor.gray600),
        ),
        Text(
          value,
          style: AppText.bodyMedium600.copyWith(
            color: isValueBlue ? AppColor.primary : AppColor.gray900,
          ),
        ),
      ],
    );
  }
}
