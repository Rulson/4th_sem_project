import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/svg.dart';
import 'package:remit_management/core/common/widget/custom_app_bar.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_state.dart';

class AboutUsScreen extends StatelessWidget {
  const AboutUsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        // title: "About Us",
        centerTitle: true,
      ),
      body: BlocBuilder<HomeCubit, HomeState>(
        builder: (context, state) {
          return Padding(
            padding: const EdgeInsets.all(16.0),
            child: SingleChildScrollView(
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Column(
                        children: [
                          Container(
                            width: 300,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              border: Border.all(
                                color: AppColor.gray450,
                                width: 1,
                                style: BorderStyle.solid, // Flutter does not support dotted borders natively
                              ),
                            ),
                            padding: const EdgeInsets.all(8.0),
                            child: ClipOval(child: Image.asset(SAppAssets.logoLogo)),
                          ),
                          20.sBHh,
                          Text('RemitSystemm', style: AppText.headlineSmall400.copyWith(fontWeight: FontWeight.w600)),
                          10.sBHh,
                          Text(
                            'Your trusted money transfer service',
                            style: AppText.titleMedium500.copyWith(color: AppColor.gray450),
                          ),
                          20.sBHh,
                        ],
                      ),
                    ],
                  ),
                  CustomRow(
                    title: state.homeModel?.data?.aboutUs?.phone ?? "",
                    icon: SvgPicture.asset(SAppAssets.iconViber),
                    subTitle: "Viber",
                  ),
                  CustomRow(
                    title: state.homeModel?.data?.aboutUs?.phone ?? "",
                    icon: SvgPicture.asset(SAppAssets.iconWhatsapp),
                    subTitle: "Whatsapp",
                  ),
                  CustomRow(
                    title: state.homeModel?.data?.aboutUs?.email ?? "",
                    icon: SvgPicture.asset(SAppAssets.iconEmail),
                    subTitle: "Email",
                  ),
                  CustomRow(
                    title: state.homeModel?.data?.aboutUs?.address ?? "",
                    icon: SvgPicture.asset(
                      SAppAssets.iconLocation,
                      colorFilter: ColorFilter.mode(AppColor.black, BlendMode.srcIn),
                    ),
                    subTitle: "Location",
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class CustomRow extends StatelessWidget {
  final String title;
  final Widget icon;
  final String subTitle;

  const CustomRow({super.key, required this.title, required this.icon, required this.subTitle});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 15),
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: AppColor.gray300)),
      ),
      child: Row(
        children: [
          icon,
          15.sBWw,
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: AppText.titleMedium600),
                10.sBHh,
                Text(subTitle, style: AppText.labelLarge600.copyWith(color: AppColor.gray450)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
