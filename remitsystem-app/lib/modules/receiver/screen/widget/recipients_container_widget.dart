import 'package:dotted_border/dotted_border.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_state.dart';
import 'package:remit_management/modules/receiver/model/receiver_list_model.dart';

class RecipientsContainerWidget extends StatelessWidget {
  final String? title;
  final VoidCallback? onSeeAllTap;
  final Function(ReceiverData) onRecipientTap;
  final String? hilightId;

  const RecipientsContainerWidget({
    super.key,
    this.title,
    this.onSeeAllTap,
    required this.onRecipientTap,
    this.hilightId,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (title != null)
          Row(
            children: [
              Text(
                title!,
                style: AppText.titleMedium600.copyWith(color: AppColor.gray1000),
              ),
              const Spacer(),
              if (onSeeAllTap != null)
                GestureDetector(
                  onTap: onSeeAllTap,
                  child: Text(
                    "View all",
                    style: AppText.bodyMedium600.copyWith(
                      color: AppColor.primary,
                      decoration: TextDecoration.underline,
                      decorationColor: AppColor.primary,
                    ),
                  ),
                ),
            ],
          ),
        16.sBHh,
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            spacing: 20,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Add new recipient
              GestureDetector(
                onTap: () => context.push(AppRoutes.addReceiver),
                child: Column(
                  children: [
                    Container(
                      height: 64,
                      width: 64,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: AppColor.primary.withAlpha(15),
                      ),
                      child: DottedBorder(
                        options: RoundedRectDottedBorderOptions(
                          radius: Radius.circular(100.r),
                          color: AppColor.primary,
                          dashPattern: const [5, 4],
                          strokeWidth: 1.5,
                        ),
                        child: Center(
                          child: Icon(
                            Icons.add,
                            color: AppColor.primary,
                            size: 24,
                          ),
                        ),
                      ),
                    ),
                    8.sBHh,
                    Text(
                      "New",
                      style: AppText.labelMedium500.copyWith(
                        color: AppColor.primary,
                      ),
                    ),
                  ],
                ),
              ),

              // Recipients list
              BlocBuilder<ReceiverListingCubit, ReceiverListingState>(
                builder: (context, state) {
                  return Row(
                    spacing: 20,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      ...?state.receiverList?.data?.map((receiver) {
                        final isHighlighted = hilightId == receiver.beneficiaryId.toString();

                        return GestureDetector(
                          onTap: () => onRecipientTap(receiver),
                          child: Column(
                            children: [
                              CircularProfileWidget(
                                title: receiver.fullName ?? "",
                                size: 56,
                                highlight: isHighlighted,
                              ),
                              8.sBHh,
                              SizedBox(
                                width: 72,
                                child: Text(
                                  receiver.fullName ?? "",
                                  style: AppText.labelMedium500.copyWith(color: AppColor.gray900),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  textAlign: TextAlign.center,
                                ),
                              ),
                            ],
                          ),
                        );
                      }),
                    ],
                  );
                },
              ),
            ],
          ),
        ),
      ],
    );
  }
}
