import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/common/app_button.dart';
import 'package:remit_management/core/common/routes/app_routes.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/bloc/bottom_nav_cubit/bottom_nav_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/bottom_nav_cubit/bottom_nav_state.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/profile_cubit/profile_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_cubit.dart';
import 'package:remit_management/modules/dahboard/screen/recipients_screen.dart';
import 'package:remit_management/modules/dahboard/screen/home_screen.dart';
import 'package:remit_management/modules/dahboard/screen/menu_screen.dart';
import 'package:remit_management/modules/dahboard/screen/profile_screen.dart';
import 'package:remit_management/modules/dahboard/screen/transactions_screen.dart';
import 'package:remit_management/modules/receiver/bloc/receiver_listing_cubit.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';
import 'package:remit_management/modules/sign_in/bloc/sign_in_cubit.dart';

import 'notification/bloc/notification_list_cubit.dart';

class DashboardPage extends StatelessWidget {
  const DashboardPage({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (context) => BottomNavCubit(),
        ),
      ],
      child: DashboardView(),
    );
  }
}

class DashboardView extends StatefulWidget {
  const DashboardView({super.key});

  @override
  State<DashboardView> createState() => _DashboardViewState();
}

class _DashboardViewState extends State<DashboardView> {
  final List<Widget> _screens = [
    const HomeScreen(),
    const RecipientsScreen(),
    const MenuScreen(),
    const TransactionsScreen(),
    const ProfileScreen(),
  ];

  @override
  void initState() {
    context.read<ProfileCubit>().getProfile();
    context.read<HomeCubit>().getHomeData();
    context.read<ReceiverListingCubit>().getReceiverList();
    context.read<TransactionListCubit>().getTransactionList();
    context.read<NotificationListCubit>().getNotificationList();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BottomNavCubit, BottomNavState>(
      builder: (context, state) {
        return Scaffold(
          appBar: state.bottomIndex == 0
              ? null
              : AppBar(
                  backgroundColor: Colors.transparent,
                  surfaceTintColor: Colors.transparent,
                  actions: [],
                  title: Text(
                    _getTitle(context, state.bottomIndex),
                    style: AppText.titleExtraLargeBold,
                  ),
                  automaticallyImplyActions: false,
                  automaticallyImplyLeading: false,
                  centerTitle: false,
                ),
          backgroundColor: state.bottomIndex == 0 ? AppColor.white : Colors.transparent,
          bottomNavigationBar: BottomNavigationBar(
            elevation: 1,
            backgroundColor: AppColor.white,
            selectedItemColor: AppColor.primary,
            unselectedItemColor: AppColor.textBgDisabled,
            selectedLabelStyle: AppText.bodySmall500,
            unselectedLabelStyle: AppText.bodySmall500,
            currentIndex: state.bottomIndex,
            type: BottomNavigationBarType.fixed,
            items: [
              BottomNavigationBarItem(
                icon: SvgPicture.asset(
                  SAppAssets.iconHome,
                  colorFilter: ColorFilter.mode(state.bottomIndex == 0 ? AppColor.primary : AppColor.textBgDisabled, BlendMode.srcIn),
                ),
                label: 'Home',
              ),
              BottomNavigationBarItem(
                icon: SvgPicture.asset(SAppAssets.iconRecipients,
                    colorFilter: ColorFilter.mode(state.bottomIndex == 1 ? AppColor.primary : AppColor.textBgDisabled, BlendMode.srcIn)),
                label: 'Recipients',
              ),
              BottomNavigationBarItem(
                icon: SvgPicture.asset(SAppAssets.iconSendArrow),
                label: '',
              ),
              BottomNavigationBarItem(
                icon: SvgPicture.asset(SAppAssets.iconTransactions,
                    colorFilter: ColorFilter.mode(state.bottomIndex == 3 ? AppColor.primary : AppColor.textBgDisabled, BlendMode.srcIn)),
                label: 'Transactions',
              ),
              BottomNavigationBarItem(
                icon: SvgPicture.asset(SAppAssets.iconProfile,
                    colorFilter: ColorFilter.mode(state.bottomIndex == 4 ? AppColor.primary : AppColor.textBgDisabled, BlendMode.srcIn)),
                label: 'More',
              ),
            ],
            onTap: (index) {
              if (index == 2) {
                context.push(AppRoutes.sendMoney);
              } else {
                context.read<BottomNavCubit>().toggleBottomNav(index);
              }
            },
          ),
          body: RefreshIndicator.adaptive(
            onRefresh: () async {
              context.read<ProfileCubit>().getProfile();
              context.read<HomeCubit>().getHomeData();
              context.read<ReceiverListingCubit>().getReceiverList();
              context.read<TransactionListCubit>().getTransactionList();
              context.read<NotificationListCubit>().getNotificationList();
              context.read<SendMoneyCubit>().resetSendMoneyState();
            },
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: state.bottomIndex == 0 ? 0 : 16.w),
              child: Stack(
                children: [
                  _screens[state.bottomIndex],
                  state.bottomIndex == 0
                      ? Positioned(
                          bottom: 0,
                          left: 0,
                          right: 0,
                          child: Container(
                            width: double.infinity,
                            height: 70.h,
                            padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 12.h),
                            decoration: BoxDecoration(
                              color: AppColor.white,
                              border: Border(top: BorderSide(color: AppColor.gray200), bottom: BorderSide(color: AppColor.gray200)),
                            ),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Subtotal",
                                      style: AppText.bodyMedium400.copyWith(color: AppColor.textBodySubtle),
                                    ),
                                    Row(
                                      crossAxisAlignment: CrossAxisAlignment.baseline,
                                      textBaseline: TextBaseline.alphabetic,
                                      children: [
                                        Text(
                                          context.watch<SendMoneyCubit>().state.subTotalConvertedAmt,
                                          style: AppText.titleMedium700.copyWith(color: AppColor.gray1000),
                                        ),
                                        6.sBWw,
                                        Text(
                                          "AUD",
                                          style: AppText.bodyMedium400.copyWith(color: AppColor.textBodySubtle),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                SizedBox(
                                  width: 140.w,
                                  child: AppButton(
                                    height: 46.h,
                                    onPressed: () => context.push(AppRoutes.sendMoney),
                                    title: "Send Now",
                                  ),
                                ),
                              ],
                            ),
                          ))
                      : const SizedBox.shrink()
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}

String _getTitle(ctx, int index) {
  switch (index) {
    case 0:
      return "";
    case 1:
      return "Recipients";
    case 3:
      return "Transactions";
    case 4:
      return "Profile";

    default:
      return "";
  }
}
