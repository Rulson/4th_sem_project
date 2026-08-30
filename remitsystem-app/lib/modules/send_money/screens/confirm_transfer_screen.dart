import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:multi_image_picker_view/multi_image_picker_view.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/common/widget/app_loader_overlay.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_state.dart';
import 'package:remit_management/modules/send_money/widget/upload_widget.dart';

class ConfrimTransferScreen extends StatelessWidget {
  const ConfrimTransferScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const SendReceiptView();
  }
}

class SendReceiptView extends StatefulWidget {
  const SendReceiptView({super.key});

  @override
  State<SendReceiptView> createState() => _SendReceiptViewState();
}

class _SendReceiptViewState extends State<SendReceiptView> {
  List<ImageFile> resultList = <ImageFile>[];

  List<ImageFile> images = <ImageFile>[];

  Map<String, dynamic> _beneficiaryDetails = {};

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<SendMoneyCubit, SendMoneyState>(
      listener: (context, state) {
        if (state.transactionLoading == AppState.success) {
          context.read<SendMoneyCubit>().resetSendMoneyState();
          context.go(AppRoutes.successfullyTransferred);
        } else if (state.transactionLoading == AppState.error) {
          AppSnackbar.showSnackBar(context: context, message: state.message ?? "Something went wrong", type: SnackBarType.error);
        }
      },
      builder: (context, state) {
        final bankDetail = context.read<HomeCubit>().state.homeModel?.data?.ourBank;

        return AppLoaderOverlay(
          isLoading: state.transactionLoading == AppState.loading,
          child: Scaffold(
            appBar: CustomAppBar(
              // title: 'Back',
              actions: [
                Text("4/4", style: AppText.bodyMedium400.copyWith(color: AppColor.gray500)),
              ],
            ),
            body: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Confirm Transfer",
                    style: AppText.headlineMedium400.copyWith(color: AppColor.gray1000, fontWeight: FontWeight.w700, height: 1.2.h),
                  ),
                  20.sBHh,
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
                              // _buildTotalToPayCard(context, state),
                              Center(
                                child: Text("You're sending", style: AppText.bodySmall600.copyWith(color: AppColor.textBody)),
                              ),
                              4.sBHh,
                              Center(
                                child: Text("${state.subTotalConvertedAmt} AUD", style: AppText.s20_600.copyWith(color: AppColor.textHeading)),
                              ),
                              // Row(
                              //   // mainAxisSize: MainAxisSize.min,
                              //   mainAxisAlignment: MainAxisAlignment.center,
                              //   children: [
                              //     12.sBWw,
                              // Icon(Icons.arrow_forward_rounded, size: 16.sp, color: AppColor.gray400),
                              // 12.sBWw,
                              // Text(
                              //     "${double.tryParse(state.subTotalConvertedAmt ?? "0")! * (double.tryParse(context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate ?? "0") ?? 1)} NPR",
                              //     style: AppText.s20_600.copyWith(color: AppColor.textHeading)),
                              //   ],
                              // ),
                              12.sBHh,
                              Container(
                                decoration: BoxDecoration(
                                  border: Border.all(color: AppColor.gray200),
                                  borderRadius: BorderRadius.circular(12.r),
                                ),
                                child: Column(
                                  children: [
                                    Container(
                                      decoration: BoxDecoration(
                                          borderRadius: BorderRadius.only(
                                            topLeft: Radius.circular(12.r),
                                            topRight: Radius.circular(12.r),
                                          ),
                                          gradient: RadialGradient(colors: [Color(0x0DFF44F6), Color(0x1A7092FF)], radius: 1.5)),
                                      padding: EdgeInsets.all(12.w),
                                      child: Row(
                                        children: [
                                          CircularProfileWidget(title: state.selectedReceiver?.fullName ?? "N/A", highlight: true, size: 40.r),
                                          12.sBWw,
                                          Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text(state.selectedReceiver?.fullName ?? "N/A", style: AppText.bodyMedium600.copyWith(color: AppColor.gray1000)),
                                              4.sBHh,
                                              Text(state.selectedReceiver?.bankName ?? "N/A", style: AppText.bodySmall400.copyWith(color: AppColor.gray600)),
                                            ],
                                          )
                                        ],
                                      ),
                                    ),
                                    Container(
                                      padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
                                      decoration: BoxDecoration(),
                                      child: Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        children: [
                                          Text(
                                            "Fee ${context.read<HomeCubit>().state.homeModel?.data?.serviceCharge ?? 0} AUD",
                                            style: AppText.bodySmall400.copyWith(color: AppColor.textBody),
                                          ),
                                          Text(
                                            "Rate 1 AUD = ${context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate ?? 0} NPR",
                                            style: AppText.bodySmall400.copyWith(color: AppColor.textBody),
                                          ),
                                          // 24.sBWw,
                                          // Text(
                                          //   "${state.subTotalConvertedAmt} AUD",
                                          //   style: AppText.titleMedium600.copyWith(color: AppColor.primary600),
                                          // ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              12.sBHh,
                              Text("Payment Method", style: AppText.bodyMedium400.copyWith(color: AppColor.textBody)),
                              8.sBHh,
                              Container(
                                padding: EdgeInsets.all(12.w),
                                decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(12),
                                    border: Border.all(
                                      color: AppColor.gray200,
                                    )),
                                child: Column(
                                  spacing: 12.h,
                                  children: [
                                    _DetailRow(title: "Account Name", value: bankDetail?.accountName ?? "N/A"),
                                    _DetailRow(title: "BSB", value: "${bankDetail?.bsb}"),
                                    _DetailRow(title: "Account Number", value: "${bankDetail?.accountNumber}"),
                                  ],
                                ),
                              ),
                              12.sBHh,
                              Text(
                                "Payment Receipt",
                                style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                              ),
                              12.sBHh,
                              UploadWidget(imagePath: state.images.firstOrNull?.path, onTap: () {}),
                              44.sBHh,
                              AppButton(
                                height: 54.h,
                                trailingIcon: SAppAssets.iconArrowRight,
                                isDisabled: state.images.isEmpty,
                                onPressed: () => sendMoney(context),
                                title: "Continue",
                              ),
                            ],
                          )))),
                  24.sBHh,
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  void sendMoney(BuildContext context) async {
    _beneficiaryDetails = {
      'sending_amount': context.read<SendMoneyCubit>().state.sendingAmount,
      'exchange_rate': context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate ?? "0.0",
      'payment_type': 'Bank Transfer',
      'payment_amount': Utils.convertAudToNpr(
          aud: context.read<SendMoneyCubit>().state.sendingAmount ?? "0.0",
          todayRate: context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate ?? "0.0"),
      'service_charge': context.read<HomeCubit>().state.homeModel?.data?.serviceCharge ?? "0.0",
      'beneficiary_id': context.read<SendMoneyCubit>().state.selectedReceiver?.beneficiaryId ?? "0",
      'referral_discount': false,
      "reason_for_sending": "transfer",
      'receipt': await _getFileList(),
    };
    if (!context.mounted) return;
    print("Beneficiary Details: $_beneficiaryDetails");
    context.read<SendMoneyCubit>().storeTransaction(_beneficiaryDetails);
  }

  Future<dynamic> _getFileList() async {
    if (context.read<SendMoneyCubit>().state.images.isEmpty) return {};
    final Map<String, dynamic> beneficiaryDetails = {};

    final firstPath = context.read<SendMoneyCubit>().state.images[0].path;
    if (firstPath != null) {
      final tempFile = await MultipartFile.fromFile(
        firstPath,
        filename: firstPath.split('/').last,
      );
      beneficiaryDetails["0"] = tempFile;
    }

    return beneficiaryDetails["0"];
  }
}

class _DetailRow extends StatelessWidget {
  final String title;
  final String value;

  const _DetailRow({
    required this.title,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          title,
          style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
        ),
        Row(
          children: [
            Text(value, style: AppText.bodyMedium600.copyWith(color: AppColor.textHeading)),
            8.sBWw,
            GestureDetector(
              onTap: () {
                Clipboard.setData(ClipboardData(text: value));
                AppSnackbar.showSnackBar(context: context, message: "Copied to clipboard", type: SnackBarType.success, snackBarDuration: 1);
              },
              child: Icon(Icons.copy_rounded, size: 14.sp, color: AppColor.gray400),
            ),
          ],
        ),
      ],
    );
  }
}

// Widget _buildTotalToPayCard(BuildContext context, SendMoneyState state) {
//   return Container(
//     width: double.infinity,
//     decoration: BoxDecoration(
//       color: AppColor.bgBrandSoft,
//       borderRadius: BorderRadius.circular(16.r),
//     ),
//     child: Column(
//       children: [
//         16.sBHh,
//         Text("Total to Pay", style: AppText.bodySmall500.copyWith(color: AppColor.textBody)),
//         8.sBHh,
//         Text("${state.sendingAmount} AUD", style: AppText.s20_600.copyWith(color: AppColor.textHeading)),
//         8.sBHh,
//         Text("To", style: AppText.bodySmall500.copyWith(color: AppColor.textBody)),
//         12.sBHh,
//         CircularProfileWidget(
//           title: state.selectedReceiver?.fullName ?? "N/A",
//           highlight: true,
//           size: 56.r,
//         ),
//         12.sBHh,
//         Text(state.selectedReceiver?.fullName ?? "N/A", style: AppText.bodyMedium500.copyWith(color: AppColor.gray1000)),
//         4.sBHh,
//         Text("+977 ${state.selectedReceiver?.number ?? "N/A"}", style: AppText.bodySmall400.copyWith(color: AppColor.gray600)),
//         16.sBHh,
//         Container(
//           padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
//           decoration: BoxDecoration(
//             color: const Color(0xFFD6E4FF),
//             borderRadius: BorderRadius.only(
//               bottomLeft: Radius.circular(16.r),
//               bottomRight: Radius.circular(16.r),
//             ),
//           ),
//           child: Row(
//             mainAxisAlignment: MainAxisAlignment.spaceBetween,
//             children: [
//               Expanded(
//                 child: Text(
//                   "Fee ${context.read<HomeCubit>().state.homeModel?.data?.serviceCharge ?? 0} AUD  ·  Rate 1 AUD = ${context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate ?? 0} NPR",
//                   style: AppText.bodySmall400.copyWith(color: AppColor.textBody),
//                 ),
//               ),
//               24.sBWw,
//               Text(
//                 "${state.sendingAmount} AUD",
//                 style: AppText.titleMedium600.copyWith(color: AppColor.primary600),
//               ),
//             ],
//           ),
//         ),
//       ],
//     ),
//   );
// }
