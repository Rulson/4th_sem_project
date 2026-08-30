import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/dahboard/models/transaction_list_model.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';

class TransactionDetailScreen extends StatelessWidget {
  final TransactionData transactionListModel;

  const TransactionDetailScreen({super.key, required this.transactionListModel});

  @override
  Widget build(BuildContext context) {
    final isFree = double.tryParse(transactionListModel.serviceCharge ?? '0') == 0;
    final receivingAmount = transactionListModel.paymentAmount ?? "N/A";

    return Scaffold(
      backgroundColor: Color(0xFFf9fafb),
      appBar: CustomAppBar(
        centerTitle: true,
        title: "Transaction Details",
        actions: [
          // IconButton(
          //   icon: Icon(Icons.headset_mic_outlined, color: AppColor.gray600, size: 22.sp),
          //   onPressed: () {},
          // ),
        ],
      ),
      body: SingleChildScrollView(
        // padding: EdgeInsets.symmetric(horizontal: 20.w),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            20.sBHh,
            Center(
              child: Column(
                children: [
                  // Currency icon
                  Container(
                    padding: EdgeInsets.all(14.w),
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: AppColor.primary600.withAlpha(20),
                    ),
                    child: Icon(
                      Icons.currency_exchange_rounded,
                      color: AppColor.primary600,
                      size: 28.sp,
                    ),
                  ),
                  16.sBHh,
                  Text(
                    "${transactionListModel.totalAmount ?? "N/A"} AUD",
                    style: AppText.headlineMedium400.copyWith(
                      color: AppColor.gray1000,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  8.sBHh,
                  Text(
                    "≈ $receivingAmount NPR",
                    style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                  ),
                  8.sBHh,
                  RichText(
                    text: TextSpan(
                      text: "To ",
                      style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                      children: [
                        TextSpan(
                          text: transactionListModel.beneficiaryName ?? "N/A",
                          style: AppText.bodyMedium600.copyWith(color: AppColor.gray900),
                        ),
                      ],
                    ),
                  ),
                  16.sBHh,
                  // Status pill
                  _StatusPill(status: transactionListModel.status ?? ""),
                ],
              ),
            ),
            32.sBHh,
            Container(
              padding: EdgeInsets.all(16.w),
              decoration: BoxDecoration(
                  color: AppColor.white,
                  // borderRadius: BorderRadius.circular(12.r),
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(24.r),
                    topRight: Radius.circular(24.r),
                  ),
                  border: Border.all(color: AppColor.gray100)),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Status Timeline",
                    style: AppText.bodyMedium600.copyWith(color: AppColor.gray700),
                  ),
                  16.sBHh,
                  _buildTimeline(transactionListModel.status ?? ""),
                  _SectionCard(
                    title: "Transfer Details",
                    child: Column(
                      children: [
                        _DetailRow(
                          title: "Exchange Rate",
                          value: "1 AUD = ${transactionListModel.exchangeRate ?? "N/A"} NPR",
                        ),
                        _DetailRow(
                          title: "Transfer Fee",
                          value: isFree ? "Free" : "${transactionListModel.serviceCharge} AUD",
                          valueColor: isFree ? AppColor.green : null,
                        ),
                        _DetailRow(
                          title: "Created",
                          value: getDateName(transactionListModel.transactionDate ?? "", label: false),
                        ),
                        _DetailRow(
                          title: "Transaction ID",
                          value: transactionListModel.transactionId ?? "N/A",
                          copyable: true,
                        ),
                        _DetailRow(
                          title: "Platform",
                          value: "iOS",
                        ),
                      ],
                    ),
                  ),
                  20.sBHh,
                  Text("Transfer Parties", style: AppText.bodyMedium600.copyWith(color: AppColor.gray700)),
                  16.sBHh,
                  Row(
                    children: [
                      Expanded(
                        child: _PartyCard(
                          label: "Sender",
                          name: transactionListModel.senderName ?? "N/A",
                          subtitle: "Personal Account",
                        ),
                      ),
                      12.sBWw,
                      Expanded(
                        child: _PartyCard(
                          label: "Recipient",
                          name: transactionListModel.beneficiaryName ?? "N/A",
                          subtitle: transactionListModel.bankName ?? "N/A",
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            24.sBHh,
          ],
        ),
      ),
    );
  }

  Widget _buildTimeline(String status) {
    final isCancelled = status.toLowerCase().contains("cancel");
    if (isCancelled) {
      return StatusStep(
        title: "Cancelled",
        subtitle: "",
        isFirst: true,
        isLast: true,
        state: TransactionProgressState.cancelled,
      );
    }

    final isDelivered = status.toLowerCase() == "confirmed" || status.toLowerCase() == "delivered" || status.toLowerCase() == "transferred";
    final isOnHold = status.toLowerCase().contains("hold");

    status = status.contains("Process")
        ? "processing"
        : status.contains("Collect")
            ? "processing"
            : status.contains("Awaiting")
                ? "Confirmed"
                : status;
    final steps = [
      "Transfer Created",
      "Payment Process",
      "Sent for Collection",
      "Processing Transfer",
      "Delivered",
    ];

    final currentIndex = switch (status.toLowerCase()) {
      "unconfirmed" => 0,
      "confirmed" => 1,
      "onhold" => 3, // Processing Transfer step

      "processing" => 3,
      "delivered" || "transferred" => 4,
      _ => 0,
    };

    return Column(
      children: List.generate(steps.length, (index) {
        final isCompleted = isDelivered ? true : index < currentIndex;
        final isCurrent = index == currentIndex;
        final isLast = index == steps.length - 1;
        final state = isOnHold && isCurrent
            ? TransactionProgressState.onHold
            : isCompleted
                ? TransactionProgressState.completed
                : isCurrent
                    ? TransactionProgressState.processing
                    : TransactionProgressState.pending;
        final stepTitle = isOnHold && isCurrent
            ? "On Hold"
            : steps[index];
        return StatusStep(
          title: stepTitle,
          subtitle: "",
          isFirst: index == 0,
          isLast: isLast,
          state: state,
        );
      }),
    );
  }
}

class _StatusPill extends StatelessWidget {
  final String status;
  const _StatusPill({required this.status});

  @override
  Widget build(BuildContext context) {
    final isDelivered = status.toLowerCase() == "delivered" || status.toLowerCase() == "transferred";
    final isProcessing = status.toLowerCase() == "processing";
    final isCancelled = status.toLowerCase().contains("cancel");
    final isOnHold = status.toLowerCase().contains("hold");

    final displayText = isCancelled
        ? "Cancelled"
        : isOnHold
            ? "On Hold"
            : status;

    final color = isDelivered
        ? AppColor.green
        : isCancelled
            ? AppColor.errorRed
            : isOnHold
                ? const Color(0xFFF59E0B)
                : isProcessing
                    ? const Color(0xFFF59E0B)
                    : AppColor.primary600;

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
      decoration: BoxDecoration(
        color: color.withAlpha(20),
        borderRadius: BorderRadius.circular(400.r),
        border: Border.all(color: color.withAlpha(60)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 7.w,
            height: 7.h,
            decoration: BoxDecoration(shape: BoxShape.circle, color: color),
          ),
          8.sBWw,
          Text(
            displayText,
            style: AppText.bodySmall600.copyWith(color: color),
          ),
        ],
      ),
    );
  }
}

class _SectionCard extends StatelessWidget {
  final String title;
  final Widget child;

  const _SectionCard({required this.title, required this.child});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: AppText.bodyMedium600.copyWith(color: AppColor.gray700)),
        12.sBHh,
        Container(
          decoration: BoxDecoration(
            color: AppColor.bgSecondaryMedium,
            borderRadius: BorderRadius.circular(12.r),
          ),
          child: child,
        ),
      ],
    );
  }
}

class _DetailRow extends StatelessWidget {
  final String title;
  final String value;
  final Color? valueColor;
  final bool copyable;

  const _DetailRow({
    required this.title,
    required this.value,
    this.valueColor,
    this.copyable = false,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 14.h),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
          ),
          Row(
            children: [
              Text(value, style: AppText.bodyMedium600.copyWith(color: valueColor ?? AppColor.textHeading)),
              if (copyable) ...[
                4.sBWw,
                GestureDetector(
                  onTap: () {
                    Clipboard.setData(ClipboardData(text: value));
                    AppSnackbar.showSnackBar(context: context, message: "Copied to clipboard", type: SnackBarType.success, snackBarDuration: 1);
                  },
                  child: Icon(Icons.copy_rounded, size: 14.sp, color: AppColor.gray400),
                ),
              ],
            ],
          ),
        ],
      ),
    );
  }
}

class _PartyCard extends StatelessWidget {
  final String label;
  final String name;
  final String subtitle;

  const _PartyCard({
    required this.label,
    required this.name,
    required this.subtitle,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(12.w),
      decoration: BoxDecoration(
        color: AppColor.gray50,
        borderRadius: BorderRadius.circular(10.r),
        border: Border.all(color: AppColor.gray200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
          ),
          10.sBHh,
          Row(
            children: [
              CircularProfileWidget(
                title: name,
                size: 32,
                highlight: true,
                dimmed: true,
              ),
              8.sBWw,
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      name,
                      style: AppText.bodySmall600.copyWith(color: AppColor.gray900),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    Text(
                      subtitle,
                      style: AppText.labelMedium400.copyWith(color: AppColor.gray500),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class StatusStep extends StatelessWidget {
  final String title;
  final String subtitle;
  final bool isFirst;
  final bool isLast;
  final TransactionProgressState state;

  const StatusStep({
    super.key,
    required this.title,
    required this.subtitle,
    this.isFirst = false,
    this.isLast = false,
    required this.state,
  });

  @override
  Widget build(BuildContext context) {
    return IntrinsicHeight(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          SizedBox(
            width: 40.w,
            child: Column(
              children: [
                _buildIndicator(),
                Expanded(
                  child: Container(
                    width: 2.w,
                    color: isLast ? Colors.transparent : AppColor.gray200,
                  ),
                ),
              ],
            ),
          ),
          16.sBHh,
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  title,
                  style: AppText.bodyMedium600.copyWith(
                    color: state == TransactionProgressState.pending
                        ? AppColor.textBodySubtle
                        : (state == TransactionProgressState.processing ? AppColor.primary : AppColor.black),
                    decoration: state == TransactionProgressState.completed ? TextDecoration.lineThrough : null,
                  ),
                ),
                Text(subtitle, style: AppText.bodyMedium400.copyWith(color: AppColor.gray700)),
                30.sBHh,
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildIndicator() {
    switch (state) {
      case TransactionProgressState.completed:
        return CircleAvatar(radius: 12.r, backgroundColor: const Color(0xFF00D20E), child: const Icon(Icons.check, size: 16, color: Colors.white));
      case TransactionProgressState.processing:
        return CircleAvatar(radius: 12.r, backgroundColor: AppColor.primary, child: const Icon(Icons.circle, size: 12, color: Colors.white));
      case TransactionProgressState.pending:
        return Container(
          height: 24.h,
          width: 24.w,
          decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: AppColor.gray200, width: 3.w), color: AppColor.gray200),
        );
      case TransactionProgressState.cancelled:
        return CircleAvatar(radius: 12.r, backgroundColor: AppColor.errorRed, child: const Icon(Icons.cancel, size: 16, color: Colors.white));
      case TransactionProgressState.onHold:
        return CircleAvatar(radius: 12.r, backgroundColor: AppColor.locationIconColor, child: const Icon(Icons.pause, size: 16, color: Colors.white));
    }
  }
}

enum TransactionProgressState { completed, processing, pending, cancelled, onHold }
