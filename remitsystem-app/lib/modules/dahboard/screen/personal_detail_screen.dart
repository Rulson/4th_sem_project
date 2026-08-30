import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_state.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_loader_indicator.dart';
import 'package:remit_management/core/utils/utils.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_state.dart';
import 'package:remit_management/modules/dahboard/screen/widget/circular_profile_widget.dart';

import '../../../core/common/routes/app_routes.dart';

class PersonalDetailScreen extends StatelessWidget {
  const PersonalDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => ProfileCubit()..getProfile(),
      child: Scaffold(
        backgroundColor: AppColor.white,
        appBar: CustomAppBar(
          title: "Personal Details",
          centerTitle: true,
        ),
        body: Padding(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: BlocConsumer<ProfileCubit, ProfileState>(
            listener: (context, state) {},
            builder: (context, state) {
              return state.isLoading == AppState.loading
                  ? AppLoaderIndicator()
                  : SingleChildScrollView(
                      padding: EdgeInsets.only(bottom: 32.h),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Center(
                            child: Column(
                              children: [
                                CircularProfileWidget(
                                  title: context.read<ProfileCubit>().state.profileModel?.data?.fullName ?? "N/A",
                                  size: 64.w,
                                ),
                                12.sBHh,
                                Text(
                                  context.read<ProfileCubit>().state.profileModel?.data?.fullName ?? "N/A",
                                  style: AppText.bodyMedium600.copyWith(color: AppColor.gray800, fontSize: 18.sp),
                                ),
                                5.sBHh,
                                Text(
                                  context.read<ProfileCubit>().state.profileModel?.data?.email ?? "N/A",
                                  style: AppText.bodySmall400.copyWith(color: AppColor.textBody),
                                ),
                                32.sBHh,
                                InkWell(
                                  borderRadius: BorderRadius.circular(12.r),
                                  onTap: () {
                                    context.push(AppRoutes.editProfile);
                                  },
                                  child: Container(
                                    padding: EdgeInsets.symmetric(vertical: 8.h, horizontal: 12.w),
                                    decoration: BoxDecoration(
                                      border: Border.all(color: AppColor.gray200),
                                      borderRadius: BorderRadius.circular(12.r),
                                    ),
                                    child: Row(
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Icon(
                                          Icons.edit_outlined,
                                          color: AppColor.textBody,
                                          size: 16.sp,
                                        ),
                                        8.sBWw,
                                        Text("Edit Detail", style: AppText.bodyMedium500.copyWith(color: AppColor.textBody))
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                          32.sBHh,
                          Column(
                            spacing: 16.h,
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              ProfileWidget(
                                title: "ID Number",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.id.toString() ?? "N/A",
                              ),
                              ProfileWidget(
                                title: "Account Expiry Date",
                                value: getFormattedDate(context.read<ProfileCubit>().state.profileModel?.data?.expiryDate.toString() ?? "N/A"),
                              ),
                              ProfileWidget(
                                title: "Suburb",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.suburb ?? "N/A",
                              ),
                              ProfileWidget(
                                title: "Street",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.street.toString() ?? "N/A",
                              ),
                              ProfileWidget(
                                title: "Post Code",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.postcode.toString() ?? "N/A",
                              ),
                              ProfileWidget(
                                title: "Phone Number",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.number.toString() ?? "N/A",
                              ),
                              ProfileWidget(
                                title: "Date of Birth",
                                value: getFormattedDate(context.read<ProfileCubit>().state.profileModel?.data?.dob.toString() ?? "N/A"),
                              ),
                              ProfileWidget(
                                title: "Issued By",
                                value: context.read<ProfileCubit>().state.profileModel?.data?.issuedBy.toString() ?? "N/A",
                              ),
                            ],
                          ),
                        ],
                      ),
                    );
            },
          ),
        ),
      ),
    );
  }
}

class ProfileWidget extends StatelessWidget {
  final String title;
  final String value;
  const ProfileWidget({
    super.key,
    required this.title,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: AppText.bodyMedium400.copyWith(color: AppColor.textBody)),
        12.sBHh,
        Text(value, style: AppText.titleMedium500.copyWith(color: AppColor.textHeading))
      ],
    );
  }
}
