import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:remit_management/core/configs/extensions/extensions.dart';
import 'package:remit_management/core/resource/app_assets.dart';
import 'package:remit_management/core/resource/app_colors.dart';
import 'package:remit_management/core/resource/app_text.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_cubit.dart';
import 'package:remit_management/modules/dahboard/bloc/home_cubit/home_state.dart';
import 'package:remit_management/modules/dahboard/bloc/transcation_list_cubit/transaction_list_state.dart';
import 'package:remit_management/modules/send_money/bloc/send_money_cubit.dart';

import '../../../core/common/app_state.dart';
import '../../../core/common/routes/app_routes.dart';
import '../../../core/utils/app_loader_indicator.dart';
import '../bloc/transcation_list_cubit/transaction_list_cubit.dart';
import '../models/transaction_list_model.dart';
import 'notification/bloc/notification_list_cubit.dart';
import 'notification/bloc/notification_list_state.dart';
import 'transactions_screen.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: AlwaysScrollableScrollPhysics(),
      padding: EdgeInsets.only(bottom: 90.h),
      child: BlocBuilder<HomeCubit, HomeState>(
        builder: (context, homeState) {
          final serviceCharge = homeState.homeModel?.data?.serviceCharge ?? "N/A";
          String rateChange = "";
          bool isPositive = true;
          // if (rates.length >= 2) {
          final first = double.tryParse("${homeState.homeModel?.data?.todayRate?.rate ?? 0.0}") ?? 0;
          // final last = double.tryParse(homeState.homeModel?.data?.todayRate?.yesterdayRate ?? "") ?? 0;
          if (first != 0) {
            final change = double.tryParse("${homeState.homeModel?.data?.todayRate?.changePercent ?? 0}") ?? 0;
            isPositive = change >= 0;
            rateChange = "${isPositive ? "+" : ""}${change.toStringAsFixed(2)}%";
          }
          // }

          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                color: Color(0xFF0F1115),
                padding: EdgeInsets.all(12.w),
                child: SafeArea(
                  child: Row(
                    children: [
                      Image.asset(
                        SAppAssets.imageLogo,
                        height: 32.h,
                        width: 32.w,
                      ),
                      8.sBWw,
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Transfer",
                            style: AppText.bodyMedium700.copyWith(color: AppColor.white),
                          ),
                          Text("NET", style: AppText.bodySmall500.copyWith(color: AppColor.primary600))
                        ],
                      ),
                      Spacer(),
                      //  10.sBW,
                      BlocBuilder<NotificationListCubit, NotificationListState>(
                        builder: (context, state) {
                          final unreadCount = state.notificationData?.count?.unreadCount ?? 0;

                          return GestureDetector(
                            onTap: () {
                              GoRouter.of(context).push(AppRoutes.notification);
                            },
                            child: Badge.count(
                              count: unreadCount,
                              isLabelVisible: unreadCount > 0,
                              child: const Icon(
                                Icons.notifications_none_rounded,
                                color: AppColor.white,
                                size: 24,
                              ),
                            ),
                          );
                        },
                      )
                      // Container(
                      //   padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 10.h),
                      //   decoration: BoxDecoration(
                      //     color: Color(0xFFF97316).withValues(alpha: 0.1),
                      //     borderRadius: BorderRadius.circular(12.r),
                      //   ),
                      //   child: Row(
                      //     children: [
                      //       Icon(
                      //         CupertinoIcons.gift,
                      //         color: Color(0xFFF97316),
                      //         size: 14.sp,
                      //       ),
                      //       4.sBWw,
                      //       Text("Rewards", style: AppText.bodySmall500.copyWith(color: Color(0xFFF97316))),
                      //     ],
                      //   ),
                      // )
                    ],
                  ),
                ),
              ),
              _AmountConverter(),
              Stack(
                children: [
                  Container(
                    height: 20.h,
                    color: AppColor.bgDarkStrong,
                  ),
                  Container(
                    decoration: BoxDecoration(
                      color: AppColor.white,
                      borderRadius: BorderRadius.only(topLeft: Radius.circular(20.r), topRight: Radius.circular(20.r)),
                      border: Border.all(color: AppColor.gray100),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Rate row
                        Padding(
                          padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
                          child: Container(
                            padding: EdgeInsets.all(16.w),
                            decoration: BoxDecoration(
                              color: Color(0x1A1447E6),
                              borderRadius: BorderRadius.circular(12.r),
                            ),
                            child: Row(
                              children: [
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      crossAxisAlignment: CrossAxisAlignment.baseline,
                                      textBaseline: TextBaseline.alphabetic,
                                      children: [
                                        Text(
                                          homeState.homeModel?.data?.todayRate?.rate?.toString() ?? "N/A",
                                          style: AppText.headlineSmall700.copyWith(color: AppColor.gray1000),
                                        ),
                                        6.sBWw,
                                        Text(
                                          "NPR",
                                          style: AppText.bodySmall500.copyWith(color: AppColor.gray500),
                                        ),
                                      ],
                                    ),
                                    8.sBHh,
                                    Row(
                                      children: [
                                        if (rateChange.isNotEmpty) ...[
                                          Text(
                                            rateChange,
                                            style: AppText.bodySmall600.copyWith(
                                              color: isPositive ? AppColor.green : AppColor.error,
                                            ),
                                          ),
                                          4.sBWw,
                                          Icon(
                                            isPositive ? Icons.arrow_upward : Icons.arrow_downward,
                                            size: 12.sp,
                                            color: isPositive ? AppColor.green : AppColor.error,
                                          ),
                                          4.sBWw,
                                        ],
                                        Text(
                                          "vs yesterday",
                                          style: AppText.bodySmall400.copyWith(color: AppColor.gray500),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                // const Spacer(),
                                // Column(
                                //   crossAxisAlignment: CrossAxisAlignment.end,
                                //   children: [
                                //     Row(
                                //       children: [
                                //         Text(
                                //           "Exclusive rate",
                                //           style: AppText.bodySmall500.copyWith(color: AppColor.gray600),
                                //         ),
                                //         4.sBWw,
                                //         Icon(Icons.trending_up, size: 14.sp, color: AppColor.green),
                                //       ],
                                //     ),
                                //   ],
                                // ),
                              ],
                            ),
                          ),
                        ),

                        Padding(
                          padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 4.h),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                "Transaction Fees",
                                style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                              ),
                              Row(
                                children: [
                                  Text(
                                    "$serviceCharge AUD",
                                    style: serviceCharge == "0"
                                        ? AppText.bodySmall400.copyWith(
                                            color: AppColor.gray400,
                                            decoration: TextDecoration.lineThrough,
                                            decorationColor: AppColor.gray400,
                                          )
                                        : AppText.bodySmall600.copyWith(
                                            color: AppColor.primary600,
                                          ),
                                  ),
                                  if (serviceCharge == "0") ...[
                                    6.sBWw,
                                    Text(
                                      "0.00 AUD",
                                      style: AppText.bodySmall600.copyWith(color: AppColor.primary600),
                                    ),
                                  ]
                                ],
                              ),
                            ],
                          ),
                        ),
                        Divider(height: 1, color: AppColor.gray100),
                        12.sBHh,
                        BlocBuilder<TransactionListCubit, TransactionListState>(
                          builder: (context, state) {
                            if (state.transactionListLoading == AppState.loading) {
                              return const AppLoaderIndicator();
                            }

                            final transactions = state.transactionList?.data ?? [];
                            final recentTransactions = transactions.take(5).toList(); // safe take 5

                            // if (recentTransactions.isEmpty) {
                            // return Center(
                            //   child: Text(
                            //     "No recent transactions",
                            //     style: AppText.bodyMedium400.copyWith(color: AppColor.gray400),
                            //   ),
                            // );

                            // }
                            if (recentTransactions.isEmpty) {
                              return SizedBox.shrink();
                            }
                            return Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Padding(
                                  padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 6.h),
                                  child: Text("Recent Transactions", style: AppText.bodyMedium600.copyWith(color: AppColor.gray700)),
                                ),
                                ListView.builder(
                                  shrinkWrap: true,
                                  padding: EdgeInsets.zero,
                                  physics: NeverScrollableScrollPhysics(), // ← fix scroll conflict
                                  itemCount: recentTransactions.length,
                                  itemBuilder: (context, index) {
                                    final TransactionData transaction = recentTransactions[index];
                                    return Padding(
                                      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 6.h),
                                      child: TransactionWidget(transaction: transaction),
                                    );
                                  },
                                ),
                              ],
                            );
                          },
                        ),
                        // Divider(height: 1, color: AppColor.gray100),

                        // InkWell(
                        //   onTap: () {},
                        //   child: Padding(
                        //     padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 14.h),
                        //     child: Row(
                        //       mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        //       children: [
                        //         Text(
                        //           "Available Coupons",
                        //           style: AppText.bodyMedium400.copyWith(color: AppColor.textBody),
                        //         ),
                        //         Row(
                        //           children: [
                        //             Text(
                        //               "Super rates under \$1000",
                        //               style: AppText.bodySmall600.copyWith(color: AppColor.gray900),
                        //             ),
                        //             4.sBWw,
                        //             Icon(Icons.arrow_forward_ios_rounded, size: 12.sp, color: AppColor.gray400),
                        //           ],
                        //         ),
                        //       ],
                        //     ),
                        //   ),
                        // ),
                      ],
                    ),
                  ),
                ],
              ),
            ],
          );
        },
      ),
    );
  }
}

class _AmountConverter extends StatefulWidget {
  // final HomeState homeState;
  const _AmountConverter();

  @override
  State<_AmountConverter> createState() => _AmountConverterState();
}

class _AmountConverterState extends State<_AmountConverter> {
  late final TextEditingController audController;
  late final TextEditingController nprController;

  @override
  void initState() {
    super.initState();
    audController = context.read<SendMoneyCubit>().audController;
    nprController = context.read<SendMoneyCubit>().nprController;
    // Initial sync
    audController.addListener(() {
      if (mounted) {
        context
            .read<SendMoneyCubit>()
            .syncAmounts(audController.text, double.tryParse(context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate?.toString() ?? "1") ?? 1);
      }
    });
    // initial setup based on current rate
    final initialRate = double.tryParse(context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate?.toString() ?? "1") ?? 1;
    context.read<SendMoneyCubit>().syncAmounts(audController.text, initialRate);
  }

  @override
  void dispose() {
    audController.removeListener(() {});
    super.dispose();
  }

  void _recalculate(String value) {
    final clean = value.replaceAll(RegExp(r'[^0-9.]'), '');
    final rate = double.tryParse(context.read<HomeCubit>().state.homeModel?.data?.todayRate?.rate?.toString() ?? "1") ?? 1;
    final aud = double.tryParse(clean) ?? 0;
    nprController.text = (aud * rate).toStringAsFixed(2);
    context.read<SendMoneyCubit>().syncAmounts(value, rate);
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<HomeCubit, HomeState>(listener: (context, state) {
      if (state.isLoading == AppState.success) {
        _recalculate("1");
      }
    }, builder: (context, state) {
      return Container(
        decoration: BoxDecoration(
          color: AppColor.bgDarkStrong,
        ),
        child: Column(
          children: [
            Padding(
              padding: EdgeInsets.all(20.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Amount sent
                  Text(
                    "Amount sent",
                    style: AppText.bodySmall400.copyWith(color: AppColor.white.withValues(alpha: 0.5)),
                  ),
                  8.sBHh,
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Expanded(
                        child: TextField(
                          onTapOutside: (_) {
                            //close keyboard
                            FocusManager.instance.primaryFocus?.unfocus();
                          },
                          controller: audController,
                          keyboardType: TextInputType.number,
                          style: AppText.titleExtraLargeBold.copyWith(
                            color: AppColor.white,
                            fontSize: 36.sp,
                          ),
                          decoration: const InputDecoration(
                            border: InputBorder.none,
                            isDense: true,
                            contentPadding: EdgeInsets.zero,
                          ),
                          onChanged: _recalculate,
                          // inputFormatters: [CurrencyInputFormatter()],
                        ),
                      ),
                      _CurrencyChip(
                        flag: SAppAssets.imageAus,
                        code: 'AUD',
                        onTap: () {},
                        dark: true,
                      ),
                    ],
                  ),
                  20.sBHh,

                  // Amount received
                  Text(
                    "Amount received",
                    style: AppText.bodySmall400.copyWith(color: AppColor.white.withValues(alpha: 0.5)),
                  ),
                  8.sBHh,
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Expanded(
                        child: Text(
                          nprController.text.isEmpty ? "0.00" : nprController.text,
                          style: AppText.titleExtraLargeBold.copyWith(
                            color: AppColor.white.withValues(alpha: 0.4),
                            fontSize: 36.sp,
                          ),
                        ),
                      ),
                      _CurrencyChip(
                        flag: SAppAssets.imageFlagNepal,
                        code: 'NPR',
                        onTap: () {},
                        dark: true,
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      );
    });
  }
}

class _CurrencyChip extends StatelessWidget {
  final String flag;
  final String code;
  final VoidCallback onTap;
  final bool dark;

  const _CurrencyChip({
    required this.flag,
    required this.code,
    required this.onTap,
    this.dark = false,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 6.h),
        // decoration: BoxDecoration(
        //   color: dark ? AppColor.white.withValues(alpha: 0.1) : AppColor.white,
        //   borderRadius: BorderRadius.circular(400.r),
        //   border: Border.all(
        //     color: dark ? AppColor.white.withValues(alpha: 0.2) : AppColor.gray200,
        //   ),
        // ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              // color: Colors.white,
              width: 32.w,
              height: 32.h,
              decoration: BoxDecoration(
                
                color: Colors.white, image: DecorationImage(
                  fit: BoxFit.cover,
                  image: AssetImage(flag)), borderRadius: BorderRadius.circular(100)),
            ),
            6.sBWw,
            Text(
              code,
              style: AppText.titleMedium500.copyWith(
                color: dark ? AppColor.white : AppColor.gray900,
              ),
            ),
            8.sBWw,
            Icon(
              Icons.arrow_forward_ios_rounded,
              size: 20.sp,
              color: dark ? AppColor.white.withValues(alpha: 0.5) : AppColor.gray400,
            ),
          ],
        ),
      ),
    );
  }
}
