import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/core/utils/app_snackbar.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_state.dart';

import '../../../core/common/app_state.dart';
import '../../../core/utils/app_loader_indicator.dart';

class ReferralCodeScreen extends StatelessWidget {
  const ReferralCodeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const _ReferralCodeView();
  }
}

class _ReferralCodeView extends StatefulWidget {
  const _ReferralCodeView();

  @override
  State<_ReferralCodeView> createState() => _ReferralCodeViewState();
}

class _ReferralCodeViewState extends State<_ReferralCodeView> {
  void _copyToClipboard(String text) {
    Clipboard.setData(ClipboardData(text: text));
    AppSnackbar.showSnackBar(
      context: context,
      message: "Referral code copied!",
      type: SnackBarType.success,
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProfileCubit, ProfileState>(
      builder: (context, state) {
        if (state.isEditLoading == AppState.loading) {
          return AppLoaderIndicator();
        }
        final referralCode = state.profileModel?.data?.referralCode ?? "------";
        return Scaffold(
          appBar: CustomAppBar(),
          body: SingleChildScrollView(
            child: Column(
              children: [
          
                ConstrainedBox(
                  constraints: BoxConstraints(maxHeight: 200.h, minWidth: double.infinity),
                  child: Image.asset(
                    SAppAssets.imageSingupIllustration,
                    fit: BoxFit.cover,
                    width: double.infinity,
                  ),
                ),
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: 16.w),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      32.sBHh,
                      Text(
                        "Your Referral Code",
                        style: AppText.headlineSmall700.copyWith(color: AppColor.gray900),
                      ),
                      8.sBHh,
                      Text(
                        "Share your code with friends and earn rewards!",
                        style: AppText.bodyMedium400.copyWith(color: AppColor.gray500),
                        textAlign: TextAlign.center,
                      ),
                      32.sBHh,
                      Container(
                        width: double.infinity,
                        padding: EdgeInsets.symmetric(vertical: 20.h, horizontal: 24.w),
                        decoration: BoxDecoration(
                          color: AppColor.white,
                          borderRadius: BorderRadius.circular(16.r),
                          border: Border.all(color: AppColor.gray200),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(
                              child: Text(
                                referralCode,
                                style: AppText.s20_700.copyWith(
                                  color: AppColor.primary,
                                  letterSpacing: 2,
                                ),
                              ),
                            ),
                            GestureDetector(
                              onTap: () => _copyToClipboard(referralCode),
                              child: Container(
                                padding: EdgeInsets.all(10),
                                decoration: BoxDecoration(
                                  color: AppColor.bgBrandSoft,
                                  borderRadius: BorderRadius.circular(8.r),
                                ),
                                child: SvgPicture.asset(
                                  SAppAssets.iconCopy,
                                  width: 20.w,
                                  colorFilter: ColorFilter.mode(
                                    AppColor.primary,
                                    BlendMode.srcIn,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      32.sBHh,
                      AppButton(
                        onPressed: () => _copyToClipboard(referralCode),
                        title: "Copy Code",
                      ),
                      16.sBHh,
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}
