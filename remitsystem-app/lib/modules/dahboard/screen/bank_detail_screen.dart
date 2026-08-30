import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_state.dart';

class BankDetailScreen extends StatelessWidget {
  const BankDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<HomeCubit, HomeState>(
      builder: (context, state) {
        return Column(
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              margin: const EdgeInsets.symmetric(vertical: 20),
              decoration: BoxDecoration(border: Border.all(color: AppColor.gray200, width: 1), color: AppColor.white, borderRadius: BorderRadius.circular(10)),
              child: Column(
                children: [
                  DetailLine(
                    label: "Account Name",
                    value: state.homeModel?.data?.ourBank?.accountName ?? "",
                  ),
                  16.sBHh,
                  DetailLine(
                    label: "BSB",
                    value: state.homeModel?.data?.ourBank?.bsb ?? "",
                  ),
                  16.sBHh,
                  DetailLine(
                    label: "Account Number",
                    value: state.homeModel?.data?.ourBank?.accountNumber ?? "",
                  ),
                  16.sBHh,
                  DetailLine(
                    label: "Bank Name",
                    value: state.homeModel?.data?.ourBank?.bankName ?? "",
                  ),
                ],
              ),
            ),
            RichText(
                text: TextSpan(
                    style: AppText.bodySmall700.copyWith(color: AppColor.textSecondary),
                    text: "Before making the transfer, please do read the ",
                    children: [
                  TextSpan(
                      text: "Transfer Terms and Conditions.", style: AppText.bodySmall700.copyWith(color: AppColor.black, decoration: TextDecoration.underline))
                ]))
          ],
        );
      },
    );
  }
}

class DetailLine extends StatelessWidget {
  final String label;
  final String value;
  const DetailLine({
    super.key,
    required this.label,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label,
            style: AppText.bodyMedium400.copyWith(
              color: AppColor.gray700,
            )),
        Text(
          value,
          style: AppText.bodyMedium600.copyWith(color: AppColor.gray900),
        )
      ],
    );
  }
}

PreferredSizeWidget bankDetailAppBar() {
  return AppBar(
    leadingWidth: 109,
    automaticallyImplyLeading: false,
    actionsPadding: EdgeInsets.only(right: 16),
    title: Text(
      "Bank Details",
      style: AppText.titleMedium700,
    ),
    centerTitle: true,
  );
}
